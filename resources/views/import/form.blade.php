<x-default-layout>
    @section('title')
        Import Data
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('import.form') }}
    @endsection

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Import Data</h3>
        </div>

        <div class="card-body">
            <form id="import-form" action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-5">
                    <label class="form-label required" for="file">Pilih file Excel</label>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls" class="form-control form-control-solid">
                </div>

                <div class="mb-5">
                    <label class="form-label required" for="file">Pilih Kurikulum</label>
                    <select name="kurikulum" id="kurikulum" class="form-select form-select-solid">
                        <option value="">--Pilih Kurikulum--</option>
                        @foreach ($kurikulums as $kurikulum)
                            <option value="{{ $kurikulum->id }}">{{ $kurikulum->nama }}</option>
                        @endforeach
                    </select>
                </div>

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-success me-4">
                            <i class="fas fa-check-circle fs-2"></i>
                        </span>
                        <div class="d-flex flex-column">
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                            <i class="fas fa-exclamation-circle fs-2"></i>
                        </span>
                        <div class="d-flex flex-column">
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                            <i class="fas fa-exclamation-circle fs-2"></i>
                        </span>
                        <div class="d-flex flex-column">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="text-end mt-8">
                    <button type="submit" class="btn btn-primary" id="import-button">
                        <span class="indicator-label">
                            <i class="fas fa-file-import me-2"></i>
                            Import Excel
                        </span>
                        <span class="indicator-progress">
                            Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Handle form submission
        const form = document.getElementById('import-form');
        const submitButton = document.getElementById('import-button');

        form.addEventListener('submit', function(e) {
            // Disable button
            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            // Enable button after 1 minute (failsafe)
            setTimeout(function() {
                submitButton.removeAttribute('data-kt-indicator');
                submitButton.disabled = false;
            }, 60000);
        });
    </script>
    @endpush
</x-default-layout>
