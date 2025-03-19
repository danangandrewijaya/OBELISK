<?php

namespace App\Http\Controllers;

use App\DataTables\MksCplDataTable;

class ReportController extends Controller
{
    public function index(MksCplDataTable $dataTable)
    {
        return $dataTable->render('report.list');
    }
}
