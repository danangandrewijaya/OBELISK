<?php

namespace App\DataTables;

use App\Models\Mahasiswa;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Column;

class MahasiswaDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Mahasiswa $mahasiswa) {
                return '<a href="' . route('report.mahasiswa.show', $mahasiswa) . '" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm">View</a>';
            })
            ->filterColumn('angkatan', function($query, $keyword) {
                $query->where('angkatan', 'like', "%$keyword%");
            })
            ->setRowId('id');
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(Mahasiswa $model): QueryBuilder
    {
        $query = $model->with('prodi')->newQuery();
        $prodiId = session('prodi_id');
        if ($prodiId) {
            $query->where('prodi_id', $prodiId);
        }
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('mahasiswa-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12'tr>><'d-flex justify-content-between'<'col-sm-12 col-md-5'i><'d-flex justify-content-between'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('nim')->title('NIM')->addClass('text-nowrap text-start'),
            Column::make('nama')->title('Nama')->addClass('text-nowrap'),
            Column::make('angkatan')->title('Angkatan')->addClass('text-nowrap text-start')->name('angkatan'),
            Column::make('prodi.nama')->title('Prodi')->addClass('text-nowrap'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->title('Action')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Mahasiswa_' . date('YmdHis');
    }
}
