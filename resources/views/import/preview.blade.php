<x-default-layout>

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Preview Data Import</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Informasi</h5>
                        Berikut adalah preview data yang akan diimpor. Silakan periksa data berikut sebelum melanjutkan.
                    </div>

                    @php
                        $dosens = App\Models\Dosen::whereIn('id', $pengampu_ids)->get();

                        // Helper function untuk mengambil data dari session yang sudah diseragamkan
                        function getSessionData($key, $default = null) {
                            // Hanya menggunakan key yang seragam - import_preview_data
                            if (session()->has('import_preview_data.' . $key)) {
                                return session('import_preview_data.' . $key);
                            }
                            return $default;
                        }
                    @endphp


                    <!-- Tampilkan Pengampu dari File Excel (Session) -->
                    @if(isset($pengampu_session) && $pengampu_session)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="card-title">Pengampu dari File Excel</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Dosen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $pengampu_session['nama'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if (session('active_role') !== App\Core\Constants::ROLE_DOSEN)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="card-title">Pengampu Terpilih</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Dosen</th>
                                            <th>NIP/NIDN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dosens as $dosen)
                                            <tr>
                                                <td>{{ $dosen->nama }}</td>
                                                <td>{{ $dosen->nip ?? 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center">Tidak ada pengampu yang dipilih</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    @foreach($preview as $sheetName => $sheetData)

                    @if($sheetName === 'CPMK-CPL' || $sheetName === 'FORM NILAI SIAP')
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ $sheetName }}</h4>
                            </div>
                            <div class="card-body">
                                @if($sheetName === 'CPMK-CPL')
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="200">Mata Kuliah</th>
                                                    <td>{{ getSessionData('mata_kuliah_kode') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tahun</th>
                                                    <td>{{ getSessionData('tahun') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Semester</th>
                                                    <td>{{ getSessionData('semester') == 1 ? 'Ganjil' : 'Genap' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Kelas</th>
                                                    <td>{{ getSessionData('kelas') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <h5 class="mb-3">Data CPMK-CPL/PI</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Kode CPMK</th>
                                                    <th>Deskripsi CPMK</th>
                                                    <th>Kode CPL/PI (Bobot%)</th>
                                                    <th>Level Taksonomi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cpmkCplData = getSessionData('cpmk_cpl');
                                                @endphp

                                                @if($cpmkCplData)
                                                    @foreach($cpmkCplData as $cpmkCpl)
                                                        <tr>
                                                            <td>{{ $cpmkCpl['kode'] }}</td>
                                                            <td>{{ $cpmkCpl['deskripsi'] }}</td>
                                                            <td>{{ (is_string($cpmkCpl['cpl_bobot']) && str_contains($cpmkCpl['cpl_bobot'], 'PI')) ? $cpmkCpl['cpl_bobot'].' (100%)' : ((!empty($cpmkCpl['cpl_number']) && isset($cpmkCpl['cpl_bobot'])) ? 'CPL'.$cpmkCpl['cpl_number'].' ('.(floatval($cpmkCpl['cpl_bobot'])*100).'%)' : '-') }}</td>
                                                            <td>{{ $cpmkCpl['level_taksonomi'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    @foreach(array_slice($sheetData, 19, 14) as $row)
                                                        @if(!empty($row[1]))
                                                            <tr>
                                                                <td>{{ $row[1] }}</td>
                                                                <td>{{ $row[2] }}</td>
                                                                <td>{{ collect(array_slice($row, 3, 13))->search(fn($value) => $value == 1) !== false ? 'CPL' . (collect(array_slice($row, 3, 13))->search(fn($value) => $value == 1) + 1) : '-' }}</td>
                                                                <td>{{ $row[19] }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                @elseif($sheetName === 'FORM NILAI SIAP')
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Mata Kuliah</th>
                                                    <td>
                                                        @if(getSessionData('mata_kuliah_kode'))
                                                            {{ getSessionData('mata_kuliah_kode') }}
                                                        @elseif(isset($sheetData[0]) && isset($sheetData[0][1]))
                                                            {{ explode(' ', $sheetData[0][1])[0] }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Tahun</th>
                                                    <td>
                                                        @if(getSessionData('tahun'))
                                                            {{ getSessionData('tahun') }}
                                                        @elseif(isset($sheetData[1]) && isset($sheetData[1][1]))
                                                            {{ substr($sheetData[1][1], 0, 4) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Semester</th>
                                                    <td>
                                                        @if(getSessionData('semester') !== null)
                                                            {{ getSessionData('semester') == 1 ? 'Ganjil' : 'Genap' }}
                                                        @elseif(isset($sheetData[2]) && isset($sheetData[2][1]))
                                                            {{ $sheetData[2][1] }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kelas</th>
                                                    <td>
                                                        @if(getSessionData('kelas'))
                                                            {{ getSessionData('kelas') }}
                                                        @elseif(isset($sheetData[3]) && isset($sheetData[3][1]))
                                                            {{ $sheetData[3][1] }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <h5 class="mb-3">Data Nilai</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>NIM</th>
                                                    <th>Nama</th>
                                                    <th>Semester</th>
                                                    <th>Status</th>
                                                    <th>Nilai Akhir Angka</th>
                                                    <th>Nilai Akhir Huruf</th>
                                                    <th>Bobot</th>
                                                    <th>Outcome</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $nilaiMahasiswaData = getSessionData('nilai_mahasiswa');
                                                @endphp

                                                @if($nilaiMahasiswaData)
                                                    @foreach($nilaiMahasiswaData as $nilai)
                                                        <tr>
                                                            <td>{{ $nilai['nim'] }}</td>
                                                            <td>{{ $nilai['nama'] }}</td>
                                                            <td>{{ $nilai['semester'] }}</td>
                                                            <td>{{ $nilai['status'] }}</td>
                                                            <td>{{ $nilai['nilai_akhir_angka'] }}</td>
                                                            <td>{{ $nilai['nilai_akhir_huruf'] }}</td>
                                                            <td>{{ $nilai['nilai_bobot'] }}</td>
                                                            <td>{{ $nilai['outcome'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    @php
                                                        $rowNilaiAkhirAngka = strpos(strtolower($sheetData[6][8]), 'nilai akhir angka') === 0 ? 8 : 10;
                                                        $rowNilaiAkhirHuruf = $rowNilaiAkhirAngka + 1;
                                                        $rowNilaiBobot = $rowNilaiAkhirHuruf + 1;
                                                        $rowOutcome = $rowNilaiBobot + 1;
                                                    @endphp
                                                    @foreach(array_slice($sheetData, 7) as $row)
                                                        @if(!empty($row[0]))
                                                            <tr>
                                                                <td>{{ $row[0] }}</td>
                                                                <td>{{ $row[1] }}</td>
                                                                <td>{{ $row[2] }}</td>
                                                                <td>{{ $row[3] }}</td>
                                                                <td>{{ $row[$rowNilaiAkhirAngka] }}</td>
                                                                <td>{{ $row[$rowNilaiAkhirHuruf] }}</td>
                                                                <td>{{ str_replace(',', '.', $row[$rowNilaiBobot]) }}</td>
                                                                <td>{{ $row[$rowOutcome] }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    @endforeach

                    <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data" class="mt-4" id="confirm-form">
                        @csrf
                        <input type="hidden" name="temp_file" value="{{ $tempFile }}">

                        @foreach($pengampu_ids as $id)
                            <input type="hidden" name="pengampu_ids[]" value="{{ $id }}">
                        @endforeach

                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('import.cancel', ['temp_file' => $tempFile]) }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary" id="confirm-button">Konfirmasi Import</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@include('components.loading-modal')

@push('scripts')
<script>
    // Handle form submission
    const form = document.getElementById('confirm-form');
    const submitButton = document.getElementById('confirm-button');
    const loadingModal = new bootstrap.Modal(document.getElementById('loading-modal'), {
        backdrop: 'static',
        keyboard: false
    });

    // Initialize loading modal
    document.addEventListener('DOMContentLoaded', function() {
        // Close any loading modal from the form page
        try {
            // Check if window.opener is available (we're in a new window) and has a loadingModal
            if (window.opener && window.opener.loadingModal) {
                window.opener.loadingModal.hide();
            }
        } catch (e) {
            console.error("Couldn't access opener window:", e);
        }

        // Hide loading modal if it was somehow left open
        loadingModal.hide();

        // Dispatch an event that the preview is loaded
        window.dispatchEvent(new CustomEvent('preview-loaded'));

        // If we were redirected from the form page with a pending_preview parameter
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('pending_preview')) {
            // Remove the parameter to avoid confusion on future loads
            const url = new URL(window.location);
            url.searchParams.delete('pending_preview');
            window.history.replaceState({}, '', url);
        }
    });

    form.addEventListener('submit', function(e) {
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
    });
</script>
@endpush
</x-default-layout>
