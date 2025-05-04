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
            <form id="import-form" action="{{ route('import.preview') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-5">
                    <label class="form-label required" for="file">Pilih file Excel</label>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls" class="form-control form-control-solid">
                </div>

                <div class="mb-5">
                    <label class="form-label required" for="pengampu_ids">Pilih Pengampu</label>
                    <select name="pengampu_ids[]" id="pengampu_ids" class="form-select form-select-solid" multiple="multiple" data-control="select2" data-placeholder="Pilih pengampu...">
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->nama }} {{ $dosen->nip ? '(' . $dosen->nip . ')' : '' }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('pengampu_ids'))
                        <div class="text-danger mt-2">{{ $errors->first('pengampu_ids') }}</div>
                    @endif
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

    @include('components.loading-modal')

    @push('scripts')
    <script>
        // Handle form submission
        const form = document.getElementById('import-form');
        const submitButton = document.getElementById('import-button');
        const loadingModal = new bootstrap.Modal(document.getElementById('loading-modal'), {
            backdrop: 'static',
            keyboard: false
        });

        // Initialize Select2
        $(document).ready(function() {
            $('#pengampu_ids').select2({
                dropdownParent: $('#import-form'),
                allowClear: true,
                closeOnSelect: false
            });
        });

        // Initialize loading modal
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading modal if it was somehow left open
            loadingModal.hide();
        });

        form.addEventListener('submit', function(e) {
            // Validate required fields
            const fileInput = document.getElementById('file');
            const pengampuSelect = document.getElementById('pengampu_ids');
            let hasError = false;

            // Check if file is selected
            if (fileInput.files.length === 0) {
                fileInput.classList.add('is-invalid');
                hasError = true;
            } else {
                fileInput.classList.remove('is-invalid');
            }

            // Check if at least one pengampu is selected
            if (pengampuSelect.selectedOptions.length === 0) {
                pengampuSelect.parentElement.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-danger mt-2';
                errorDiv.innerText = 'Pilih minimal satu pengampu';

                // Remove any existing error message first
                const existingError = pengampuSelect.parentElement.nextElementSibling;
                if (existingError && existingError.classList.contains('text-danger')) {
                    existingError.remove();
                }

                pengampuSelect.parentElement.after(errorDiv);
                hasError = true;
            } else {
                pengampuSelect.parentElement.classList.remove('is-invalid');
                const existingError = pengampuSelect.parentElement.nextElementSibling;
                if (existingError && existingError.classList.contains('text-danger')) {
                    existingError.remove();
                }
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            // Prevent double submission
            if (submitButton.disabled) {
                e.preventDefault();
                return false;
            }

            // Show loading modal
            loadingModal.show();

            // Disable button
            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            // Enable button after 1 minute (failsafe)
            setTimeout(function() {
                submitButton.removeAttribute('data-kt-indicator');
                submitButton.disabled = false;

                // Hide loading modal
                loadingModal.hide();
            }, 60000);
        });
    </script>
    @endpush
</x-default-layout>
