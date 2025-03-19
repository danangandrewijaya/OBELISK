<x-default-layout>
    @section('title')
        Detail Mahasiswa
    @endsection

    {{-- @section('breadcrumbs')
        {{ Breadcrumbs::render('report.mahasiswa.show', $mahasiswa) }}
    @endsection --}}

    <div class="card">
        <div class="card-header">
            <div class="card-title">Data Mahasiswa</div>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-4">
                    <div class="fw-bold">NIM</div>
                    <div>{{ $mahasiswa->nim }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Nama</div>
                    <div>{{ $mahasiswa->nama }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Program Studi</div>
                    <div>{{ $mahasiswa->prodi->nama }}</div>
                </div>
            </div>

            <div class="separator my-5"></div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Data Nilai</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Semester</th>
                                    <th>Kelas</th>
                                    <th>Nilai Angka</th>
                                    <th>Nilai Huruf</th>
                                    <th>Outcome</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mahasiswa->nilai as $nilai)
                                <tr>
                                    <td>{{ $nilai->mks->mkk->kode }} - {{ $nilai->mks->mkk->nama }}</td>
                                    <td>{{ $nilai->mks->mkk->sks }}</td>
                                    <td>{{ $nilai->semester }}</td>
                                    <td>{{ $nilai->kelas }}</td>
                                    <td>{{ $nilai->nilai_akhir_angka }}</td>
                                    <td>{{ $nilai->nilai_akhir_huruf }}</td>
                                    <td data-bs-toggle="tooltip" data-bs-placement="top" title="@foreach($nilai->nilaiCpmk as $nilaiCpmk) CPMK{{ $nilaiCpmk->cpmk->nomor }} ({{ $nilaiCpmk->nilai_angka }})@if(!$loop->last), @endif @endforeach">
                                        {{ $nilai->outcome }} <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-default-layout>
