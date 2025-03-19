<x-default-layout>
    @section('title')
        Laporan Matakuliah Semester
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">Filter</div>
        </div>
        <div class="card-body">
            <form id="filter-form" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <select class="form-select" name="tahun" id="tahun">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Semester</label>
                    <select class="form-select" name="semester" id="semester">
                        <option value="">Semua Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>{{ $semester }}</option>
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
            <div class="card-title">Data Matakuliah Semester</div>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            let dataTable;
            
            $(document).ready(function() {
                // Initialize select values from URL
                let currentUrl = new URL(window.location.href);
                if (currentUrl.searchParams.has('tahun')) {
                    document.getElementById('tahun').value = currentUrl.searchParams.get('tahun');
                }
                if (currentUrl.searchParams.has('semester')) {
                    document.getElementById('semester').value = currentUrl.searchParams.get('semester');
                }
            });

            // Wait for DataTable to be initialized
            $('#matakuliah-semester-table').on('init.dt', function() {
                dataTable = window.LaravelDataTables['matakuliah-semester-table'];
                
                // Handle form submission
                document.getElementById('filter-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    let url = new URL(dataTable.ajax.url());
                    
                    // Get form values
                    let tahun = document.getElementById('tahun').value;
                    let semester = document.getElementById('semester').value;

                    // Update URL parameters
                    if (tahun) url.searchParams.set('tahun', tahun);
                    else url.searchParams.delete('tahun');
                    
                    if (semester) url.searchParams.set('semester', semester);
                    else url.searchParams.delete('semester');

                    // Update table URL and reload
                    dataTable.ajax.url(url.toString()).load();

                    // Update browser URL without reload
                    window.history.pushState({}, '', url.toString());
                });

                // Apply filters on page load if they exist in URL
                let url = new URL(window.location.href);
                if (url.searchParams.has('tahun') || url.searchParams.has('semester')) {
                    document.getElementById('filter-form').dispatchEvent(new Event('submit'));
                }
            });
        </script>
    @endpush
</x-default-layout>
