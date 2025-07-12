<?php

namespace App\DataTables;

use App\Models\MataKuliahSemester;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MatakuliahSemesterDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return view('matakuliah-semester.action', compact('row'));
                // for development purposes
                // return view('report.matakuliah-semester.action', compact('row'));
            })
            ->addColumn('nama_matakuliah', function ($row) {
                return $row->mkk->kode . ' - ' . $row->mkk->nama;
            })
            ->addColumn('pengampu', function ($row) {
                $pengampuList = $row->pengampuDosens->pluck('nama')->toArray();
                return !empty($pengampuList) ? implode('<br>', $pengampuList) : '-';
            })
            // for development purposes
            // ->addColumn('cpl', function ($row) {
            //     $cplList = collect();

            //     // Mendapatkan semua CPMK terkait mata kuliah semester
            //     $cpmks = $row->cpmk;

            //     // Untuk setiap CPMK, ambil CPL terkait
            //     foreach ($cpmks as $cpmk) {
            //         // Mendapatkan semua CPL terkait CPMK ini melalui relasi cpmkCpl
            //         $cpls = $cpmk->cpmkCpl->map(function($cpmkCpl) {
            //             $cpl = $cpmkCpl->cpl;
            //             if ($cpl) {
            //                 return "CPL-{$cpl->nomor}: {$cpl->nama}";
            //             }
            //             return null;
            //         })->filter();

            //         $cplList = $cplList->merge($cpls);
            //     }

            //     // Hapus duplikat dan format output
            //     $cplList = $cplList->unique()->values();
            //     return $cplList->count() > 0 ? $cplList->implode('<br>') : '-';
            // })
            ->rawColumns(['action', 'pengampu', 'cpl'])
            ->setRowId('id');
    }

    public function query(MataKuliahSemester $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['mkk', 'pengampuDosens']);

        // Apply year filter if present
        if ($this->request()->has('tahun')) {
            $query->where('tahun', $this->request()->get('tahun'));
        }

        // Apply semester filter if present
        if ($this->request()->has('semester')) {
            $query->where('semester', $this->request()->get('semester'));
        }

        $role = session('active_role');
        $dosenId = session('dosen_id');
        if ($role === 'dosen') {
            $query->whereHas('pengampuDosens', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            });
        }

        $search = $this->request()->input('search.value');   // NULL jika tidak ada

        if ($search) {
            $query->whereHas('mkk', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')->orWhere('kode', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('matakuliah-semester-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtilp')
            ->orderBy(1)
            ->selectStyleSingle()
            // ->buttons([
            //     Button::make('reload')
            // ])
            ;
    }

    protected function getColumns(): array
    {
        return [
            Column::computed('nama_matakuliah')
                ->title('Mata Kuliah')
                ->searchable(true)
                ->orderable(true),
            Column::make('tahun')
                ->title('Tahun')
                ->searchable(false)
                ->orderable(true),
            Column::make('semester')
                ->title('Semester')
                ->searchable(false)
                ->orderable(true),
            Column::computed('pengampu')
                ->title('Pengampu')
                ->searchable(false)
                ->orderable(false)
                ->width(300)
                ->addClass('text-start'),
            // for development purposes
            // Column::computed('cpl')
            //     ->title('CPL')
            //     ->searchable(false)
            //     ->orderable(false)
            //     ->width(300)
            //     ->addClass('text-start'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'MatakuliahSemester_' . date('YmdHis');
    }
}
