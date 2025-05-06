<?php

namespace App\DataTables;

use App\Models\ImportLog;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ImportLogDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return view('import-log.action', compact('row'));
            })
            ->editColumn('user.name', function ($row) {
                return $row->user ? $row->user->name : 'Unknown';
            })
            ->editColumn('action_type', function ($row) {
                $class = match($row->action) {
                    'submit' => 'badge-light-primary',
                    'preview' => 'badge-light-info',
                    'confirm' => 'badge-light-success',
                    'cancel' => 'badge-light-warning',
                    default => 'badge-light-dark',
                };
                return '<span class="badge fs-7 ' . $class . '">' . ucfirst($row->action) . '</span>';
            })
            ->editColumn('status', function ($row) {
                $class = match($row->status) {
                    'success' => 'badge-light-success',
                    'failed' => 'badge-light-danger',
                    'pending' => 'badge-light-warning',
                    default => 'badge-light-dark',
                };
                return '<span class="badge fs-7 ' . $class . '">' . ucfirst($row->status) . '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y, H:i') : '-';
            })
            ->rawColumns(['action_type', 'status', 'action'])
            ->setRowId('id');
    }

    public function query(ImportLog $model): QueryBuilder
    {
        return $model->newQuery()->with('user');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('import-logs-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(5, 'desc') // Sort by created_at by default
            ->selectStyleSingle();
    }

    protected function getColumns(): array
    {
        return [
            // Column::make('id'),
            Column::make('user.name')->title('User'),
            Column::computed('action_type')
                ->title('Action')
                ->searchable(false)
                ->orderable(false),
            Column::make('file_name')
                ->title('File Name')
                ->searchable(true),
            Column::make('status')
                ->title('Status')
                ->searchable(false)
                ->orderable(false),
            Column::make('created_at')
                ->title('Date')
                ->searchable(true),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }
}
