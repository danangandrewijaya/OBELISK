<?php

namespace App\Http\Controllers;

use App\Models\ImportLog;
use App\DataTables\ImportLogDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ImportLogController extends Controller
{
    /**
     * Display a listing of import logs.
     *
     * @param ImportLogDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(ImportLogDataTable $dataTable)
    {
        // Get unique actions and statuses for filters in view, scoped by prodi if set
        $prodiId = session('prodi_id');
        $actionsQuery = ImportLog::distinct()->select('action');
        $statusesQuery = ImportLog::distinct()->select('status');
        if ($prodiId) {
            $actionsQuery->whereHas('mataKuliahSemester.mkk.kurikulum', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
            $statusesQuery->whereHas('mataKuliahSemester.mkk.kurikulum', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }
        $actions = $actionsQuery->pluck('action');
        $statuses = $statusesQuery->pluck('status');

        return $dataTable->render('import-log.index', compact('actions', 'statuses'));
    }

    /**
     * Display the specified import log.
     *
     * @param ImportLog $importLog
     * @return \Illuminate\View\View
     */
    public function show(ImportLog $importLog)
    {
        $importLog->load(['user', 'mataKuliahSemester']);
        return view('import-log.show', compact('importLog'));
    }
}
