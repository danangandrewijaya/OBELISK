<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CpmkCplImport;
use App\Imports\CpmkImport;
use App\Models\Kurikulum;
use App\Models\MataKuliahSemester;
use Maatwebsite\Excel\Excel as ExcelFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Settings;

class ImportController extends Controller
{
    public function showImportForm()
    {
        $kurikulums = Kurikulum::all();
        return view('import.form', compact('kurikulums'));
    }

    public function importExcel(Request $request)
    {
        $kurikulum = Kurikulum::find($request->kurikulum);

        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:xlsx,xls|max:10240' // max 10MB
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first()
                    ], 422);
                }
                return back()->withErrors($validator);
            }

            $filePath = $request->file('file');
            Excel::import(new CpmkCplImport($kurikulum), $filePath);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil diimpor!'
                ]);
            }
            return back()->with('success', 'Data berhasil diimpor!');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = collect($e->failures())->map(function($failure) {
                return $failure->getMessage();
            })->join(', ');

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi Excel gagal: ' . $failures
                ], 422);
            }
            return back()->with('error', 'Validasi Excel gagal: ' . $failures);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {

        $mks = MataKuliahSemester::find(4);
        $cpmk_cpl = $mks->cpmk()->get()->flatten();
        dd($cpmk_cpl);
    }
}
