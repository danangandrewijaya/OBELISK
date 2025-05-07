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
                                <th>CPL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matakuliahSemester->cpmk as $cpmk)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>CPMK-{{ $cpmk->nomor }}</td>
                                    <td>{{ $cpmk->deskripsi }}</td>
                                    <td>{{ $cpmk->level_taksonomi }}</td>
                                    <td>
                                        @if($cpmk->cpmkCpl->count() > 0)
                                            <ul class="list-unstyled mb-0">
                                                @foreach($cpmk->cpmkCpl as $cpmkCpl)
                                                    @if($cpmkCpl->cpl)
                                                        <li>CPL-{{ $cpmkCpl->cpl->nomor }}: {{ $cpmkCpl->cpl->nama }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
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
                                <th>Semester</th>
                                <th>Status</th>
                                <th>Nilai Akhir Angka</th>
                                <th>Nilai Akhir Huruf</th>
                                <th>Outcome</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matakuliahSemester->nilaiMahasiswa as $nilai)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $nilai->mahasiswa->nim ?? '-' }}</td>
                                    <td>{{ $nilai->mahasiswa->nama ?? 'Tidak diketahui' }}</td>
                                    <td>{{ $nilai->kelas ?? '-' }}</td>
                                    <td>{{ $nilai->semester ?? '-' }}</td>
                                    <td>{{ $nilai->status ?? '-' }}</td>
                                    <td>{{ $nilai->nilai_akhir_angka ?? '-' }}</td>
                                    <td>{{ $nilai->nilai_akhir_huruf ?? '-' }}</td>
                                    <td>
                                        @if($nilai->outcome == 'LULUS')
                                            <a href="#" class="badge badge-success nilai-cpmk-modal"
                                               data-mahasiswa-id="{{ $nilai->mahasiswa->id ?? '' }}"
                                               data-mahasiswa-nama="{{ $nilai->mahasiswa->nama ?? 'Tidak diketahui' }}"
                                               data-nilai-id="{{ $nilai->id ?? '' }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#nilaiCpmkModal">Lulus</a>
                                        @elseif($nilai->outcome == 'REMIDI CPMK')
                                            <a href="#" class="badge badge-warning nilai-cpmk-modal"
                                               data-mahasiswa-id="{{ $nilai->mahasiswa->id ?? '' }}"
                                               data-mahasiswa-nama="{{ $nilai->mahasiswa->nama ?? 'Tidak diketahui' }}"
                                               data-nilai-id="{{ $nilai->id ?? '' }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#nilaiCpmkModal">Remidi CPMK</a>
                                        @elseif($nilai->outcome == 'TIDAK LULUS')
                                            <a href="#" class="badge badge-danger nilai-cpmk-modal"
                                               data-mahasiswa-id="{{ $nilai->mahasiswa->id ?? '' }}"
                                               data-mahasiswa-nama="{{ $nilai->mahasiswa->nama ?? 'Tidak diketahui' }}"
                                               data-nilai-id="{{ $nilai->id ?? '' }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#nilaiCpmkModal">Tidak Lulus</a>
                                        @else
                                            <a href="#" class="badge badge-secondary nilai-cpmk-modal"
                                               data-mahasiswa-id="{{ $nilai->mahasiswa->id ?? '' }}"
                                               data-mahasiswa-nama="{{ $nilai->mahasiswa->nama ?? 'Tidak diketahui' }}"
                                               data-nilai-id="{{ $nilai->id ?? '' }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#nilaiCpmkModal">{{ $nilai->outcome }}</a>
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

    <!-- Modal Nilai CPMK -->
    <div class="modal fade" id="nilaiCpmkModal" tabindex="-1" aria-labelledby="nilaiCpmkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nilaiCpmkModalLabel">Nilai CPMK Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3 id="mhs-nama" class="mb-4">Nama Mahasiswa</h3>
                    <div id="loading-spinner" class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="nilai-cpmk-content" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-striped gy-5">
                                <thead>
                                    <tr class="fw-bold fs-6 text-muted">
                                        <th>CPMK</th>
                                        <th>Deskripsi</th>
                                        <th>Nilai</th>
                                        <th>Bobot</th>
                                    </tr>
                                </thead>
                                <tbody id="nilai-cpmk-body">
                                    <!-- Data akan diisi melalui AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="nilai-cpmk-error" class="alert alert-danger" style="display: none;">
                        Gagal memuat data nilai CPMK.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nilaiCpmkButtons = document.querySelectorAll('.nilai-cpmk-modal');

            nilaiCpmkButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const mahasiswaId = this.getAttribute('data-mahasiswa-id');
                    const mahasiswaNama = this.getAttribute('data-mahasiswa-nama');
                    const nilaiId = this.getAttribute('data-nilai-id');
                    const mksId = {{ $matakuliahSemester->id }};

                    // Tampilkan nama mahasiswa di modal
                    document.getElementById('mhs-nama').textContent = mahasiswaNama;

                    // Reset konten dan tampilkan spinner
                    document.getElementById('nilai-cpmk-content').style.display = 'none';
                    document.getElementById('loading-spinner').style.display = 'block';
                    document.getElementById('nilai-cpmk-error').style.display = 'none';
                    document.getElementById('nilai-cpmk-body').innerHTML = '';

                    // Ambil data nilai CPMK melalui AJAX
                    fetch(`/api/nilai/${nilaiId}/cpmk?mks_id=${mksId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Sembunyikan spinner dan tampilkan tabel
                            document.getElementById('loading-spinner').style.display = 'none';
                            document.getElementById('nilai-cpmk-content').style.display = 'block';

                            // Isi tabel dengan data
                            const tableBody = document.getElementById('nilai-cpmk-body');

                            if (data.length === 0) {
                                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada data nilai CPMK.</td></tr>';
                                return;
                            }

                            data.forEach(item => {
                                const row = document.createElement('tr');

                                row.innerHTML = `
                                    <td>CPMK-${item.cpmk.nomor}</td>
                                    <td>${item.cpmk.deskripsi}</td>
                                    <td>${item.nilai}</td>
                                    <td>${item.bobot}</td>
                                `;

                                tableBody.appendChild(row);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching CPMK data:', error);
                            document.getElementById('loading-spinner').style.display = 'none';
                            document.getElementById('nilai-cpmk-error').style.display = 'block';
                        });
                });
            });
        });
    </script>
    @endpush
</x-default-layout>
