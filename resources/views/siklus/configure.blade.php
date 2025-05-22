<x-default-layout>
    @section('title', 'Konfigurasi Siklus')

    @section('breadcrumbs')
        {{ Breadcrumbs::render('siklus.configure', $siklus) }}
    @endsection

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="card-title">Konfigurasi Siklus: {{ $siklus->nama }}</div>
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

            <div class="alert alert-info">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fs-2"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">Petunjuk Konfigurasi</h4>
                        <p>Pilih mata kuliah untuk setiap CPL yang akan digunakan dalam perhitungan ketercapaian CPL pada siklus ini. Hanya mata kuliah yang memiliki keterhubungan dengan CPL yang akan ditampilkan.</p>
                        <p>Perhitungan rata-rata CPL akan menggunakan nilai CPMK-CPL dari mata kuliah yang dipilih dalam rentang tahun siklus ({{ $siklus->tahun_mulai }} - {{ $siklus->tahun_selesai }}).</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('siklus.save-cpl-selections', $siklus) }}" method="POST" id="configure-form">
                @csrf

                @foreach($cpls as $cpl)
                    <div class="card mb-6">
                        <div class="card-header">
                            <h3 class="card-title">CPL {{ $cpl->nomor }}: {{ $cpl->nama }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="text-gray-600">{!! nl2br(e($cpl->deskripsi)) !!}</p>
                            </div>

                            <div class="mb-5">
                                <label class="form-label">Pilih Mata Kuliah untuk CPL ini:</label>
                                <div class="table-responsive">
                                    <table class="table table-row-bordered table-striped gy-4 gs-4">
                                        <thead>
                                            <tr class="fw-bold fs-6 text-gray-800">
                                                <th class="w-50px">
                                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                        <input class="form-check-input cpl-select-all" type="checkbox" data-cpl-id="{{ $cpl->id }}"/>
                                                    </div>
                                                </th>
                                                <th>Kode</th>
                                                <th>Nama Mata Kuliah</th>
                                                <th>SKS</th>
                                                <th>Semester</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Group MKSs by their MKK ID
                                                $groupedMkss = [];
                                                foreach($availableMkss as $mks) {
                                                    if(in_array($cpl->id, $mksCplConnections[$mks->id] ?? [])) {
                                                        if(!isset($groupedMkss[$mks->mkk->id])) {
                                                            $groupedMkss[$mks->mkk->id] = [
                                                                'mkk' => $mks->mkk,
                                                                'items' => []
                                                            ];
                                                        }
                                                        $groupedMkss[$mks->mkk->id]['items'][] = $mks;
                                                    }
                                                }
                                            @endphp

                                            @if(count($groupedMkss) > 0)
                                                @foreach($groupedMkss as $mkkId => $group)
                                                    <tr class="bg-light">
                                                        <td colspan="5" class="fw-bold">
                                                            {{ $group['mkk']->kode }} - {{ $group['mkk']->nama }} ({{ $group['mkk']->sks }} SKS)
                                                        </td>
                                                    </tr>
                                                    @foreach($group['items'] as $mks)
                                                        <tr>
                                                            <td>
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input cpl-mkk-checkbox"
                                                                        type="checkbox"
                                                                        name="cpl_selections[{{ $cpl->id }}][]"
                                                                        value="{{ $mks->id }}"
                                                                        data-cpl-id="{{ $cpl->id }}"
                                                                        {{ in_array($mks->id, $selections[$cpl->id] ?? []) ? 'checked' : '' }}
                                                                    />
                                                                </div>
                                                            </td>
                                                            <td>{{ $mks->mkk->kode }}</td>
                                                            <td>{{ $mks->mkk->nama }}</td>
                                                            <td>{{ $mks->mkk->sks }}</td>
                                                            <td>{{ $mks->tahun }}-{{ $mks->semester == 1 ? 'Ganjil' : 'Genap' }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">
                                                        Tidak ada mata kuliah yang terhubung dengan CPL ini
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="separator separator-dashed my-8"></div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('siklus.index') }}" class="btn btn-light me-3">Kembali</a>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Simpan Konfigurasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {            // Handle select all checkboxes
            document.querySelectorAll('.cpl-select-all').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const cplId = this.dataset.cplId;
                    const isChecked = this.checked;

                    document.querySelectorAll(`.cpl-mkk-checkbox[data-cpl-id="${cplId}"]`).forEach(cb => {
                        cb.checked = isChecked;
                    });
                });
            });

            // Update select all checkbox state based on individual checkboxes
            document.querySelectorAll('.cpl-mkk-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const cplId = this.dataset.cplId;
                    const totalCheckboxes = document.querySelectorAll(`.cpl-mkk-checkbox[data-cpl-id="${cplId}"]`).length;
                    const checkedCheckboxes = document.querySelectorAll(`.cpl-mkk-checkbox[data-cpl-id="${cplId}"]:checked`).length;

                    const selectAllCheckbox = document.querySelector(`.cpl-select-all[data-cpl-id="${cplId}"]`);
                    selectAllCheckbox.checked = totalCheckboxes === checkedCheckboxes;
                    selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
                });
            });

            // Initialize select all checkbox states
            document.querySelectorAll('.cpl-select-all').forEach(checkbox => {
                const cplId = checkbox.dataset.cplId;
                const totalCheckboxes = document.querySelectorAll(`.cpl-mkk-checkbox[data-cpl-id="${cplId}"]`).length;
                const checkedCheckboxes = document.querySelectorAll(`.cpl-mkk-checkbox[data-cpl-id="${cplId}"]:checked`).length;

                checkbox.checked = totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes;
                checkbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
            });
        });
    </script>
    @endpush
</x-default-layout>
