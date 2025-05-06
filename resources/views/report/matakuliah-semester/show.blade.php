<x-default-layout>
    @section('title')
        Detail Matakuliah Semester
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">Data Matakuliah</div>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="fw-bold">Kode - Nama</div>
                    <div>{{ $matakuliahSemester->mkk->kode }} - {{ $matakuliahSemester->mkk->nama }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fw-bold">Tahun / Semester</div>
                    <div>{{ $matakuliahSemester->tahun }} / {{ $matakuliahSemester->semester }}</div>
                </div>
            </div>

            <div class="separator my-5"></div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Hubungan CPMK - CPL</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>CPMK</th>
                                    <th>CPL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($matakuliahSemester->cpmk as $cpmk)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">CPMK {{ $cpmk->nomor }}</span>
                                                <span class="text-muted">{{ $cpmk->deskripsi }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($cpmk->cpmkCpl as $cpmkCpl)
                                                <div class="badge badge-light-primary mb-1">
                                                    CPL {{ $cpmkCpl->cpl->nomor }}: {{ Str::limit($cpmkCpl->cpl->nama, 50) }}
                                                </div>
                                                @if(!$loop->last)<br>@endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="separator my-5"></div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar Nilai Mahasiswa</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Program Studi</th>
                                    <th>Kelas</th>
                                    <th class="text-end">Nilai Angka</th>
                                    <th class="text-end">Nilai Huruf</th>
                                    <th class="text-end">Outcome</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($matakuliahSemester->nilaiMahasiswa as $nilai)
                                    <tr class="{{ $nilai->outcome == 'TIDAK LULUS' ? 'table-danger' : ($nilai->outcome == 'REMIDI CPMK' ? 'table-warning' : '') }}">
                                        <td>{{ $nilai->mahasiswa->nim }}</td>
                                        <td>{{ $nilai->mahasiswa->nama }}</td>
                                        <td>{{ $nilai->mahasiswa->prodi->nama }}</td>
                                        <td>{{ $nilai->kelas }}</td>
                                        <td class="text-end">
                                            <span class="badge badge-{{ $nilai->outcome == 'TIDAK LULUS' ? 'danger' : ($nilai->outcome == 'REMIDI CPMK' ? 'warning' : 'light-primary') }} fw-bold">
                                                {{ $nilai->nilai_akhir_angka }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge badge-{{ $nilai->outcome == 'TIDAK LULUS' ? 'danger' : ($nilai->outcome == 'REMIDI CPMK' ? 'warning' : 'light-primary') }} fw-bold">
                                                {{ $nilai->nilai_akhir_huruf }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm p-1" data-bs-toggle="modal" data-bs-target="#cpmkModal{{ $nilai->id }}">
                                                <span class="badge badge-{{ $nilai->outcome == 'TIDAK LULUS' ? 'danger' : ($nilai->outcome == 'REMIDI CPMK' ? 'warning' : 'light-primary') }} fw-bold">
                                                    {{ $nilai->outcome }}
                                                </span>
                                            </button>

                                            <div class="modal fade" tabindex="-1" id="cpmkModal{{ $nilai->id }}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h3 class="modal-title">Detail Nilai CPMK - {{ $nilai->mahasiswa->nama }}</h3>
                                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                                <i class="bi bi-x-lg"></i>
                                                            </div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-row-bordered gy-5">
                                                                <thead>
                                                                    <tr class="fw-bold fs-6 text-gray-800">
                                                                        <th class="text-start">CPMK</th>
                                                                        <th class="text-end">Nilai</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($nilai->nilaiCpmk as $cpmk)
                                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                                            <td>
                                                                                <div class="d-flex flex-column text-start">
                                                                                    <span class="fw-bold">CPMK {{ $cpmk->cpmk->nomor }}</span>
                                                                                    <span class="text-muted">{{ $cpmk->cpmk->deskripsi }}</span>
                                                                                </div>
                                                                            </td>
                                                                            <td class="text-end">
                                                                                <span class="badge badge-light-success">
                                                                                    {{ $cpmk->nilai_bobot }}
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
