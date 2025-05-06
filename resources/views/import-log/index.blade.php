<x-default-layout>
    @section('title')
        Import Logs
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">Filter</div>
        </div>
        <div class="card-body">
            <form id="filter-form" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" id="status-filter">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Action</label>
                    <select class="form-select" name="action" id="action-filter">
                        <option value="">Semua Action</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}">{{ ucfirst($action) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Import Logs</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">History of all data imports</span>
                </h3>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    @push('scripts')
    {{ $dataTable->scripts() }}

    <script>
        let dataTable;

        // Wait for DataTable to be initialized
        $('#import-logs-table').on('init.dt', function() {
            dataTable = window.LaravelDataTables['import-logs-table'];

            // Handle form submission
            document.getElementById('filter-form').addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form values
                let status = document.getElementById('status-filter').value;
                let action = document.getElementById('action-filter').value;

                // Apply filters to DataTable
                if (status) dataTable.column(4).search(status);
                else dataTable.column(4).search('');

                if (action) dataTable.column(2).search(action);
                else dataTable.column(2).search('');

                dataTable.draw();
            });
        });
    </script>
    @endpush
</x-default-layout>
