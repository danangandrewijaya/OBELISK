<x-default-layout>
    @section('title', 'Edit Mata Kuliah Semester')

    @section('breadcrumbs')
        {{-- Breadcrumbs can be added here if needed --}}
    @endsection

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="card-title">Form Edit Data</div>
            <div class="card-toolbar">
                <a href="{{ route('master.matakuliah-semester.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <form action="{{ route('master.matakuliah-semester.update', $matakuliahSemester) }}" method="POST" id="matakuliahSemesterForm">
            @csrf
            @method('PUT')
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-5">
                    <label for="mkk_id" class="form-label required">Mata Kuliah</label>
                    <select name="mkk_id" id="mkk_id" class="form-select @error('mkk_id') is-invalid @enderror" required data-control="select2" data-placeholder="-- Pilih Mata Kuliah --">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach ($matakuliahs as $matakuliah)
                            <option value="{{ $matakuliah->id }}" {{ (old('mkk_id', $matakuliahSemester->mkk_id) == $matakuliah->id) ? 'selected' : '' }}>
                                {{ $matakuliah->kode }} - {{ $matakuliah->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('mkk_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="tahun" class="form-label required">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select @error('tahun') is-invalid @enderror" required>
                            <option value="">-- Pilih Tahun --</option>
                            @foreach ($tahunOptions as $tahun)
                                <option value="{{ $tahun }}" {{ (old('tahun', $matakuliahSemester->tahun) == $tahun) ? 'selected' : '' }}>{{ $tahun }}</option>
                            @endforeach
                        </select>
                        @error('tahun')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="semester" class="form-label required">Semester</label>
                        <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror" required>
                            <option value="">-- Pilih Semester --</option>
                            @foreach ($semesterOptions as $semester)
                                <option value="{{ $semester }}" {{ (old('semester', $matakuliahSemester->semester) == $semester) ? 'selected' : '' }}>{{ $semester }}</option>
                            @endforeach
                        </select>
                        @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label for="pengampu_ids" class="form-label required">Dosen Pengampu</label>
                    <select name="pengampu_ids[]" id="pengampu_ids" class="form-select @error('pengampu_ids') is-invalid @enderror" required multiple data-control="select2" data-placeholder="-- Pilih Dosen Pengampu --">
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ in_array($dosen->id, old('pengampu_ids', $selectedPengampuIds)) ? 'selected' : '' }}>
                                {{ $dosen->nama }} ({{ $dosen->nip }})
                            </option>
                        @endforeach
                    </select>
                    @error('pengampu_ids')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Pilih satu atau lebih dosen pengampu</div>

                    {{-- Hidden input to ensure the form submits an empty array if no options are selected --}}
                    <input type="hidden" name="pengampu_ids[]" value="" disabled id="empty-pengampu">
                </div>

                <div class="mb-5">
                    <label for="koord_pengampu_id" class="form-label required">Koordinator Pengampu</label>
                    <select name="koord_pengampu_id" id="koord_pengampu_id" class="form-select @error('koord_pengampu_id') is-invalid @enderror" required data-control="select2" data-placeholder="-- Pilih Koordinator Pengampu --">
                        <option value="">-- Pilih Koordinator Pengampu --</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ (old('koord_pengampu_id', $matakuliahSemester->koord_pengampu_id) == $dosen->id) ? 'selected' : '' }}>
                                {{ $dosen->nama }} ({{ $dosen->nip }})
                            </option>
                        @endforeach
                    </select>
                    @error('koord_pengampu_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="gpm_id" class="form-label required">GPM</label>
                    <select name="gpm_id" id="gpm_id" class="form-select @error('gpm_id') is-invalid @enderror" required data-control="select2" data-placeholder="-- Pilih GPM --">
                        <option value="">-- Pilih GPM --</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ (old('gpm_id', $matakuliahSemester->gpm_id) == $dosen->id) ? 'selected' : '' }}>
                                {{ $dosen->nama }} ({{ $dosen->nip }})
                            </option>
                        @endforeach
                    </select>
                    @error('gpm_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button type="button" id="btnReset" class="btn btn-secondary me-2">Reset</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Store original values for reset
            const originalValues = {
                mkk_id: '{{ $matakuliahSemester->mkk_id }}',
                tahun: '{{ $matakuliahSemester->tahun }}',
                semester: '{{ $matakuliahSemester->semester }}',
                pengampu_ids: {!! json_encode($selectedPengampuIds) !!},
                koord_pengampu_id: '{{ $matakuliahSemester->koord_pengampu_id }}',
                gpm_id: '{{ $matakuliahSemester->gpm_id }}'
            };

            // Handle reset button click
            $('#btnReset').on('click', function() {
                // Reset to original values instead of clearing
                $('#mkk_id').val(originalValues.mkk_id).trigger('change');
                $('#tahun').val(originalValues.tahun);
                $('#semester').val(originalValues.semester);
                $('#pengampu_ids').val(originalValues.pengampu_ids).trigger('change');
                $('#koord_pengampu_id').val(originalValues.koord_pengampu_id).trigger('change');
                $('#gpm_id').val(originalValues.gpm_id).trigger('change');
            });

            // Ensure empty-pengampu is enabled only when nothing is selected
            $('#pengampu_ids').on('change', function() {
                const hasSelection = $(this).val() && $(this).val().length > 0;
                $('#empty-pengampu').prop('disabled', hasSelection);
            });

            // Run once on page load to set initial state
            const initialSelection = $('#pengampu_ids').val() && $('#pengampu_ids').val().length > 0;
            $('#empty-pengampu').prop('disabled', initialSelection);

            // Handle form submission - extra validation
            $('#matakuliahSemesterForm').on('submit', function(e) {
                const pengampuIds = $('#pengampu_ids').val();
                if (!pengampuIds || pengampuIds.length === 0) {
                    e.preventDefault();
                    alert('Minimal satu dosen pengampu harus dipilih');
                    return false;
                }
                return true;
            });
        });
    </script>
    @endpush
</x-default-layout>
