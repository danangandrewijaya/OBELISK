<x-default-layout>
    @section('title', 'Detail Mata Kuliah Semester')

    @section('breadcrumbs')
        {{-- Breadcrumbs can be added here if needed --}}
    @endsection

    <div class="card shadow-sm mb-5">
        <div class="card-header">
            <div class="card-title">Informasi Mata Kuliah Semester</div>
            <div class="card-toolbar">
                <a href="{{ route('master.matakuliah-semester.index') }}" class="btn btn-sm btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('master.matakuliah-semester.edit', $matakuliahSemester) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-row-bordered gy-5">
                    <tr>
                        <th style="width: 200px">Kode Mata Kuliah</th>
                        <td>{{ $matakuliahSemester->mkk->kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama Mata Kuliah</th>
                        <td>{{ $matakuliahSemester->mkk->nama }}</td>
                    </tr>
                    <tr>
                        <th>SKS</th>
                        <td>{{ $matakuliahSemester->mkk->sks }}</td>
                    </tr>
                    <tr>
                        <th>Kurikulum</th>
                        <td>{{ $matakuliahSemester->mkk->kurikulum->nama ?? 'Tidak ada' }}</td>
                    </tr>
                    <tr>
                        <th>Tahun</th>
                        <td>{{ $matakuliahSemester->tahun }}</td>
                    </tr>
                    <tr>
                        <th>Semester</th>
                        <td>{{ $matakuliahSemester->semester }}</td>
                    </tr>
                    <tr>
                        <th>Dosen Pengampu</th>
                        <td>
                            @if($matakuliahSemester->pengampuDosens->count() > 0)
                                <ul class="list-unstyled mb-0">
                                    @foreach($matakuliahSemester->pengampuDosens as $dosen)
                                        <li>{{ $dosen->nama }}</li>
                                    @endforeach
                                </ul>
                            @else
                                Tidak ditentukan
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Koordinator Pengampu</th>
                        <td>{{ $matakuliahSemester->koordPengampu->nama ?? 'Tidak ditentukan' }}</td>
                    </tr>
                    <tr>
                        <th>GPM</th>
                        <td>{{ $matakuliahSemester->gpm->nama ?? 'Tidak ditentukan' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ $matakuliahSemester->created_at->format('d F Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <td>{{ $matakuliahSemester->updated_at->format('d F Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- CPMK Section -->
    <div class="card shadow-sm mb-5">
        <div class="card-header">
            <div class="card-title">CPMK (Capaian Pembelajaran Mata Kuliah)</div>
        </div>
        <div class="card-body">
            @if($matakuliahSemester->cpmk->count() > 0)
                <div class="table-responsive">
                    <table class="table table-row-bordered table-striped gy-5">
                        <thead>
                            <tr class="fw-bold fs-6 text-muted">
                                <th>No</th>
                                <th>CPMK</th>
                                <th>Deskripsi</th>
                                <th>Level Taksonomi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matakuliahSemester->cpmk as $cpmk)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>CPMK-{{ $cpmk->nomor }}</td>
                                    <td>{{ $cpmk->deskripsi }}</td>
                                    <td>{{ $cpmk->level_taksonomi }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    Belum ada data CPMK untuk mata kuliah semester ini.
                </div>
            @endif
        </div>
    </div>

    <!-- Mahasiswa Section -->
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="card-title">Daftar Mahasiswa</div>
        </div>
        <div class="card-body">
            @if($matakuliahSemester->nilaiMahasiswa->count() > 0)
                <div class="table-responsive">
                    <table class="table table-row-bordered table-striped gy-5">
                        <thead>
                            <tr class="fw-bold fs-6 text-muted">
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Nilai Akhir</th>
                                <th>Grade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matakuliahSemester->nilaiMahasiswa as $nilai)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $nilai->mahasiswa->nim ?? '-' }}</td>
                                    <td>{{ $nilai->mahasiswa->nama ?? 'Tidak diketahui' }}</td>
                                    <td>{{ $nilai->kelas ?? '-' }}</td>
                                    <td>{{ $nilai->nilai_akhir_angka ?? '-' }}</td>
                                    <td>{{ $nilai->nilai_akhir_huruf ?? '-' }}</td>
                                    <td>
                                        @if($nilai->outcome == 'lulus')
                                            <span class="badge badge-success">Lulus</span>
                                        @elseif($nilai->outcome == 'remidi_cpmk')
                                            <span class="badge badge-warning">Remidi CPMK</span>
                                        @elseif($nilai->outcome == 'tidak_lulus')
                                            <span class="badge badge-danger">Tidak Lulus</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $nilai->outcome }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    Belum ada data mahasiswa untuk mata kuliah semester ini.
                </div>
            @endif
        </div>
    </div>
</x-default-layout>
