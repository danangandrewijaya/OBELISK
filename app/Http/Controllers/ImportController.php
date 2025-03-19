<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CpmkCplImport;
use App\Imports\CpmkImport;
use App\Models\MataKuliahSemester;
use Maatwebsite\Excel\Excel as ExcelFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Settings;

class ImportController extends Controller
{
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            // Load the Excel file and convert to array
            // $array = Excel::toArray(new CPMKCPLImport, $request->file('file'));

            // Dump and die to see the contents
            // dd($array);


            // Save array to JSON file
            // $jsonData = json_encode($array, JSON_PRETTY_PRINT);
            // $filename = 'excel_data_' . date('Y-m-d_His') . '.json';
            // Storage::put('excel_imports/' . $filename, $jsonData);

            $filePath = $request->file('file');

            // Original import code commented out
            Excel::import(new CpmkCplImport, $filePath);
            return back()->with('success', 'Data berhasil diimpor!');

            // $data = Excel::toArray([], $filePath, null, ExcelFormat::XLSX);
            // dd($data);

            // $spreadsheet = IOFactory::load($filePath);

            // // Pastikan semua formula dihitung ulang sebelum membaca data
            // $spreadsheet->getActiveSheet()->calculateWorksheetFormula();

            // $sheet = $spreadsheet->getActiveSheet();
            // $data = [];

            // foreach ($sheet->getRowIterator() as $row) {
            //     $rowData = [];
            //     foreach ($row->getCellIterator() as $cell) {
            //         $rowData[] = $cell->getCalculatedValue(); // Pastikan hanya nilai yang diambil
            //     }
            //     $data[] = $rowData;
            // }

            // dd($data); // Debug hasil baca Excel, seharusnya hanya berisi nilai


        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showImportForm()
    {
        return view('import.form');
    }

    public function index(Request $request)
    {

        $mks = MataKuliahSemester::find(4);
        $cpmk_cpl = $mks->cpmk()->get()->flatten();
        dd($cpmk_cpl);
    }
}
