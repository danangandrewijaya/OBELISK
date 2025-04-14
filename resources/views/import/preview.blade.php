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
                                                    <td>{{ $sheetData[0][2] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tahun</th>
                                                    <td>{{ $sheetData[1][2] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Semester</th>
                                                    <td>{{ $sheetData[2][2] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Kelas</th>
                                                    <td>{{ $sheetData[3][2] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>SKS</th>
                                                    <td>{{ $sheetData[8][2] }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="200">Pengampu</th>
                                                    <td>{{ $sheetData[5][2] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Koordinator Pengampu</th>
                                                    <td>{{ $sheetData[6][2] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Kaprodi</th>
                                                    <td>{{ $sheetData[9][2] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>GPM</th>
                                                    <td>{{ $sheetData[11][2] }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <h5 class="mb-3">Data CPMK-CPL</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Kode CPMK</th>
                                                    <th>Deskripsi CPMK</th>
                                                    <th>Kode CPL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(array_slice($sheetData, 18, 14) as $row)
                                                    @if(!empty($row[1]))
                                                        <tr>
                                                            <td>{{ $row[1] }}</td>
                                                            <td>{{ $row[2] }}</td>
                                                            <td>{{ collect(array_slice($row, 3, 13))->search(fn($value) => $value == 1) !== false ? 'CPL' . (collect(array_slice($row, 3, 13))->search(fn($value) => $value == 1) + 1) : '-' }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                @elseif($sheetName === 'FORM NILAI SIAP')
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Mata Kuliah</th>
                                                    <td>{{ explode(' ', $sheetData[0][1])[0] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tahun</th>
                                                    <td>{{ substr($sheetData[1][1], 0, 4) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Semester</th>
                                                    <td>{{ $sheetData[2][1] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Kelas</th>
                                                    <td>{{ $sheetData[3][1] }}</td>
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
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    @endforeach

                    <form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data" class="mt-4" id="confirm-form">
                        @csrf
                        <input type="hidden" name="confirm" value="1">
                        {{-- <input type="hidden" name="kurikulum" value="{{ $kurikulum->id }}"> --}}
                        <input type="hidden" name="temp_file" value="{{ $tempFile }}">

                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('import.form') }}" class="btn btn-secondary">Batal</a>
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
        // Hide loading modal if it was somehow left open
        loadingModal.hide();
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
