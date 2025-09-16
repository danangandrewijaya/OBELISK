<x-default-layout>
    @section('title', 'Master Mata Kuliah Semester')

    @section('breadcrumbs')
        {{-- Breadcrumbs can be added here if needed --}}
    @endsection

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="card-title">Data Mata Kuliah Semester</div>
            <div class="card-toolbar">
                @if(session('active_role') !== App\Core\Constants::ROLE_DOSEN)
                <a href="{{ route('master.matakuliah-semester.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle"></i> Tambah Data
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form id="filter-form" class="row mb-3">
                <div class="col-md-3">
                    <label for="kurikulum" class="form-label">Kurikulum</label>
                    <select id="kurikulum" name="kurikulum" class="form-select form-select-sm">
                        <option value="">Semua Kurikulum</option>
                        @foreach ($kurikulums as $kurikulum)
                            <option value="{{ $kurikulum->id }}" {{ request('kurikulum') == $kurikulum->id ? 'selected' : '' }}>{{ $kurikulum->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select id="tahun" name="tahun" class="form-select form-select-sm">
                        <option value="">Semua Tahun</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="semester" class="form-label">Semester</label>
                    <select id="semester" name="semester" class="form-select form-select-sm">
                        <option value="">Semua Semester</option>
                        @foreach ($semesters as $semester)
                            <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>{{ $semester }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-sm btn-primary d-block">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                </div>
            </form>
            <br>
            {{ $dataTable->table(['class' => 'table table-row-bordered table-striped gy-5']) }}
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            let dataTable;

            $(document).ready(function() {
                // Initialize select values from URL
                let currentUrl = new URL(window.location.href);
                if (currentUrl.searchParams.has('kurikulum')) {
                    document.getElementById('kurikulum').value = currentUrl.searchParams.get('kurikulum');
                }
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

                    // Get form values
                    let kurikulum = document.getElementById('kurikulum').value;
                    let tahun = document.getElementById('tahun').value;
                    let semester = document.getElementById('semester').value;

                    // Apply filters to DataTable
                    dataTable.ajax.reload(function() {
                        // Generate new URL with parameters
                        let url = new URL(window.location.href);

                        // Update URL parameters
                        if (kurikulum) url.searchParams.set('kurikulum', kurikulum);
                        else url.searchParams.delete('kurikulum');

                        if (tahun) url.searchParams.set('tahun', tahun);
                        else url.searchParams.delete('tahun');

                        if (semester) url.searchParams.set('semester', semester);
                        else url.searchParams.delete('semester');

                        // Update browser URL without reload
                        window.history.pushState({}, '', url.toString());
                    });
                });
            });

            // Override Ajax data to include filters
            $(document).on('preXhr.dt', function(e, settings, data) {
                // Add filters to ajax request
                let kurikulum = document.getElementById('kurikulum').value;
                let tahun = document.getElementById('tahun').value;
                let semester = document.getElementById('semester').value;

                if (kurikulum) data.kurikulum = kurikulum;
                if (tahun) data.tahun = tahun;
                if (semester) data.semester = semester;
            });

            // Apply filters on page load if they exist in URL
            $(document).ready(function() {
                let url = new URL(window.location.href);
                if (url.searchParams.has('tahun') || url.searchParams.has('semester')) {
                    setTimeout(function() {
                        document.getElementById('filter-form').dispatchEvent(new Event('submit'));
                    }, 500);
                }
            });
        </script>
    @endpush
</x-default-layout>
