<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CpmkCplImport;
use App\Imports\CpmkImport;
use App\Models\Dosen;
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
        $dosens = Dosen::orderBy('nama')->get();
        return view('import.form', compact('kurikulums', 'dosens'));
    }

    public function importExcel(Request $request)
    {
        $kurikulum = Kurikulum::find($request->kurikulum);
        $pengampus = $request->pengampu_ids ?? [];

        try {
            $validator = Validator::make($request->all(), [
                'file' => $request->has('confirm') ? 'nullable' : 'required|mimes:xlsx,xls|max:10240',
                'pengampu_ids' => 'required|array|min:1',
                'pengampu_ids.*' => 'exists:mst_dosen,id'
            ], [
                'pengampu_ids.required' => 'Pilihan pengampu wajib diisi',
                'pengampu_ids.min' => 'Pilih minimal satu pengampu'
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

            if (!$request->has('confirm')) {
                // Preview mode - don't save to database
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $filePath = $file->storeAs('temp', $fileName, 'public');

                $importer = new CpmkCplImport($kurikulum, true);
                $preview = Excel::toArray($importer, $file);

                return view('import.preview', [
                    'preview' => $preview,
                    'kurikulum' => $kurikulum,
                    'tempFile' => $filePath,
                    'pengampu_ids' => $pengampus
                ]);
            }

            // Confirmation received - get file from temp storage
            $tempFile = $request->input('temp_file');
            if (!$tempFile || !Storage::disk('public')->exists($tempFile)) {
                return back()->with('error', 'File tidak ditemukan. Silakan upload ulang.');
            }

            $filePath = Storage::disk('public')->path($tempFile);
            Excel::import(new CpmkCplImport($kurikulum, false, $pengampus), $filePath);

            // Clean up temp file
            Storage::disk('public')->delete($tempFile);

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
                    'message' => $failures
                ], 422);
            }
            return back()->with('error', $failures);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $mks = MataKuliahSemester::find(4);
        $cpmk_cpl = $mks->cpmk()->get()->flatten();
        dd($cpmk_cpl);
    }
}
