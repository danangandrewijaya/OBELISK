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
                <div class="col-md-4">
                    <div class="fw-bold">Kode - Nama</div>
                    <div>{{ $matakuliahSemester->mkk->kode }} - {{ $matakuliahSemester->mkk->nama }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Tahun / Semester</div>
                    <div>{{ $matakuliahSemester->tahun }} / {{ $matakuliahSemester->semester }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Kelas</div>
                    <div>{{ $matakuliahSemester->kelas }}</div>
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
                                                <span class="text-muted">{{ $cpmk->nama }}</span>
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
        </div>
    </div>
</x-default-layout>
