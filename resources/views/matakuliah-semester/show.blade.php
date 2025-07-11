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
                @if(session('active_role') !== 'dosen')
                <a href="{{ route('master.matakuliah-semester.edit', $matakuliahSemester) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endif
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
                                <th>CPL (Capaian Pembelajaran Lulusan)</th>
                                <th>PI (Performance Indicators)</th>
                                <th>Rata-Rata Nilai</th>
                                <th>Rata-Rata Bobot</th>
                                <th>Analisis Pelaksanaan</th>
                                <th>Rencana Perbaikan</th>
                                <th>Aksi</th>
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
                                                        <li>CPL-{{ $cpmkCpl->cpl->nomor }}: {{ $cpmkCpl->cpl->nama }}
                                                            <span class="badge fs-6 badge-light-{{ $cpmkCpl->bobot == 0 ? 'danger':'primary' }}">{{ $cpmkCpl->bobot*100 }}%</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($cpmk->cpmkPi->count() > 0)
                                            <ul class="list-unstyled mb-0">
                                                @foreach($cpmk->cpmkPi as $cpmkPi)
                                                    @if($cpmkPi)
                                                        <li>PI-{{ $cpmkPi->pi->cpl->nomor }}-{{ $cpmkPi->pi->nomor }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $totalNilaiCpmk = 0;
                                            $totalBobotCpmk = 0;
                                            $countCpmk = 0;

                                            foreach ($matakuliahSemester->nilaiMahasiswa as $nilai) {
                                                foreach ($nilai->nilaiCpmk as $nilaiCpmk) {
                                                    if ($nilaiCpmk->cpmk_id == $cpmk->id) {
                                                        // Pastikan menggunakan field nilai_angka (bukan nilai)
                                                        $totalNilaiCpmk += $nilaiCpmk->nilai_angka ?? 0;
                                                        $totalBobotCpmk += $nilaiCpmk->nilai_bobot ?? 0;
                                                        $countCpmk++;
                                                    }
                                                }
                                            }

                                            if ($countCpmk > 0) {
                                                $rataNilai = number_format($totalNilaiCpmk / $countCpmk, 2);
                                                $rataBobot = number_format($totalBobotCpmk / $countCpmk, 2);
                                            } else {
                                                $rataNilai = 0;
                                                $rataBobot = 0;
                                            }
                                        @endphp

                                        <span class="badge fs-6 {{ $rataNilai >= 70 ? 'badge-success' : ($rataNilai >= 50 ? 'badge-warning' : 'badge-danger') }}">
                                            {{ $rataNilai }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge fs-6 {{ $rataNilai >= 70 ? 'badge-success' : ($rataNilai >= 50 ? 'badge-warning' : 'badge-danger') }}">
                                            {{ $rataBobot }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $cpmk->pelaksanaan }}
                                    </td>
                                    <td>
                                        {{ $cpmk->evaluasi }}
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary tindak-lanjut-btn"
                                        data-cpmk-id="{{ $cpmk->id }}"
                                        data-cpmk-nomor="CPMK-{{ $cpmk->nomor }}"
                                        data-cpmk-deskripsi="{{ $cpmk->deskripsi }}"
                                        data-cpmk-level="{{ $cpmk->level_taksonomi }}"
                                        data-cpmk-cpl="@foreach($cpmk->cpmkCpl as $cpmkCpl){{ $cpmkCpl->cpl ? 'CPL-'.$cpmkCpl->cpl->nomor.': '.$cpmkCpl->cpl->nama.'; ' : '' }}@endforeach"
                                        data-cpmk-pelaksanaan="{{ $cpmk->pelaksanaan }}"
                                        data-cpmk-evaluasi="{{ $cpmk->evaluasi }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#tindakLanjutModal">
                                            Tindak Lanjut
                                        </a>
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
                                <th>Revisi CPMK</th>
                                <th>Keterangan</th>
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
                                    <td>
                                        @if($nilai->outcome == 'REMIDI CPMK')
                                            <a href="#" class="badge badge-{{ $nilai->keterangan ? 'primary' : 'warning' }} nilai-cpmk-modal"
                                               data-mahasiswa-id="{{ $nilai->mahasiswa->id ?? '' }}"
                                               data-mahasiswa-nama="{{ $nilai->mahasiswa->nama ?? 'Tidak diketahui' }}"
                                               data-nilai-id="{{ $nilai->id ?? '' }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#nilaiCpmkModal">{{ $nilai->keterangan ? 'Sudah Perbaikan' : 'Belum Perbaikan' }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nilai->outcome == 'REMIDI CPMK')
                                            {{ $nilai->keterangan ?? '-' }}
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
                        <div class="row">
                            <div class="col-md-12">
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
                            {{-- Hide --}}
                            {{-- <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Ringkasan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            <div class="mb-3">
                                                <h6>Rata-rata Nilai:</h6>
                                                <div id="rata-nilai" class="fs-2 fw-bold text-primary">-</div>
                                            </div>
                                            <div>
                                                <h6>Rata-rata Bobot:</h6>
                                                <div id="rata-bobot" class="fs-2 fw-bold text-success">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div id="nilai-cpmk-error" class="alert alert-danger" style="display: none;">
                        Gagal memuat data nilai CPMK.
                    </div>
                    <div id="keterangan-form" class="mt-4 border-top pt-4" style="display: none;">
                        <h5>Remidi CPMK</h5>
                        <form id="keterangan-form-element">
                            <input type="hidden" name="nilai_id" id="keterangan-nilai-id">
                            <div class="form-group mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan untuk remidi CPMK..."></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary" id="save-keterangan">Simpan Keterangan</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tindak Lanjut CPMK -->
    <div class="modal fade" id="tindakLanjutModal" tabindex="-1" aria-labelledby="tindakLanjutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="tindak-lanjut-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tindakLanjutModalLabel">Tindak Lanjut CPMK</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Notifikasi -->
                        <div id="tindak-lanjut-alert" style="display:none"></div>
                        <input type="hidden" name="cpmk_id" id="modal-cpmk-id">
                        <dl class="row mb-3">
                            <dt class="col-sm-4">CPMK</dt>
                            <dd class="col-sm-8" id="modal-cpmk-nomor"></dd>
                            <dt class="col-sm-4">Deskripsi</dt>
                            <dd class="col-sm-8" id="modal-cpmk-deskripsi"></dd>
                            <dt class="col-sm-4">Level Taksonomi</dt>
                            <dd class="col-sm-8" id="modal-cpmk-level"></dd>
                            <dt class="col-sm-4">CPL</dt>
                            <dd class="col-sm-8" id="modal-cpmk-cpl"></dd>
                        </dl>
                        <div class="mb-3">
                            <label for="modal-cpmk-pelaksanaan" class="form-label">Analisis Pelaksanaan</label>
                            <textarea class="form-control" id="modal-cpmk-pelaksanaan" name="pelaksanaan" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="modal-cpmk-evaluasi" class="form-label">Rencana Perbaikan</label>
                            <textarea class="form-control" id="modal-cpmk-evaluasi" name="evaluasi" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-link p-0" id="toggle-mhs-bobot" style="font-size: 0.95rem;">
                                <i class="fas fa-users"></i> Lihat Mahasiswa Nilai Bobot &lt; 1.0
                            </button>
                            <div id="mhs-bobot-list" class="mt-2" style="display: none;">
                                <div class="alert alert-info mb-2" style="font-size:0.95rem;">
                                    Mahasiswa dengan nilai bobot CPMK ini &lt; 1.0:
                                </div>
                                <ul id="mhs-bobot-ul" class="mb-0" style="font-size:0.97rem;">
                                    <!-- Diisi via JS -->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
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
                    const isRemidiCpmk = this.textContent.trim() === 'Remidi CPMK' || this.textContent.trim() === 'Belum Perbaikan' || this.textContent.trim() === 'Sudah Perbaikan';

                    // Tampilkan nama mahasiswa di modal
                    document.getElementById('mhs-nama').textContent = mahasiswaNama;

                    // Reset konten dan tampilkan spinner
                    document.getElementById('nilai-cpmk-content').style.display = 'none';
                    document.getElementById('loading-spinner').style.display = 'block';
                    document.getElementById('nilai-cpmk-error').style.display = 'none';
                    document.getElementById('nilai-cpmk-body').innerHTML = '';
                    document.getElementById('keterangan-form').style.display = 'none';
                    // document.getElementById('rata-nilai').textContent = '-';
                    // document.getElementById('rata-bobot').textContent = '-';

                    // Setup keterangan form if needed

                    const isDosen = @json(session('active_role') === 'dosen');
                    const isAdmin = @json(session('active_role') === 'admin');
                    if (isRemidiCpmk && (isAdmin || isDosen)) {
                        document.getElementById('keterangan-nilai-id').value = nilaiId;

                        // Fetch current keterangan value
                        fetch(`/api/nilai/${nilaiId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.keterangan) {
                                    document.getElementById('keterangan').value = data.keterangan;
                                } else {
                                    document.getElementById('keterangan').value = '';
                                }
                                document.getElementById('keterangan-form').style.display = 'block';
                            })
                            .catch(error => {
                                console.error('Error fetching keterangan:', error);
                                document.getElementById('keterangan').value = '';
                                document.getElementById('keterangan-form').style.display = 'block';
                            });
                    }

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
                                document.getElementById('rata-nilai').textContent = '-';
                                document.getElementById('rata-bobot').textContent = '-';
                                return;
                            }

                            // Hitung rata-rata nilai dan bobot
                            let totalNilai = 0;
                            let totalBobot = 0;

                            data.forEach(item => {
                                const row = document.createElement('tr');
                                // Pastikan nilai dikonversi ke angka dengan parseFloat
                                // dan nilai default 0 jika null/undefined/NaN
                                const nilai = parseFloat(item.nilai) || 0;
                                const bobot = parseFloat(item.bobot) || 0;

                                totalNilai += nilai;
                                totalBobot += bobot;

                                row.innerHTML = `
                                    <td>CPMK-${item.cpmk.nomor}</td>
                                    <td>${item.cpmk.deskripsi}</td>
                                    <td>${nilai.toFixed(2)}</td>
                                    <td>${bobot.toFixed(2)}</td>
                                `;

                                tableBody.appendChild(row);
                            });

                            // Tampilkan rata-rata
                            // if (data.length > 0) {
                            //     const avgNilai = (totalNilai / data.length).toFixed(2);
                            //     const avgBobot = (totalBobot / data.length).toFixed(2);

                            //     document.getElementById('rata-nilai').textContent = avgNilai;
                            //     document.getElementById('rata-bobot').textContent = avgBobot;
                            // }
                        })
                        .catch(error => {
                            console.error('Error fetching CPMK data:', error);
                            document.getElementById('loading-spinner').style.display = 'none';
                            document.getElementById('nilai-cpmk-error').style.display = 'block';
                        });
                });
            });

            // Handle keterangan form submission
            document.getElementById('keterangan-form-element').addEventListener('submit', function(e) {
                e.preventDefault();

                const nilaiId = document.getElementById('keterangan-nilai-id').value;
                const keterangan = document.getElementById('keterangan').value;
                const saveBtn = document.getElementById('save-keterangan');

                // Disable button during save
                saveBtn.disabled = true;
                saveBtn.textContent = 'Menyimpan...';

                // Send AJAX request to update keterangan
                fetch(`/api/nilai/${nilaiId}/keterangan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        keterangan: keterangan
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Re-enable button
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Simpan Keterangan';

                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'alert alert-success mt-2';
                    successMsg.textContent = 'Keterangan berhasil disimpan';
                    document.getElementById('keterangan-form').appendChild(successMsg);

                    // Remove success message after 3 seconds
                    setTimeout(() => {
                        successMsg.remove();
                    }, 3000);

                    // Update the keterangan displayed in the table
                    location.reload();
                })
                .catch(error => {
                    console.error('Error saving keterangan:', error);

                    // Re-enable button
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Simpan Keterangan';

                    // Show error message
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'alert alert-danger mt-2';
                    errorMsg.textContent = 'Gagal menyimpan keterangan';
                    document.getElementById('keterangan-form').appendChild(errorMsg);

                    // Remove error message after 3 seconds
                    setTimeout(() => {
                        errorMsg.remove();
                    }, 3000);
                });
            });

            // Modal Tindak Lanjut CPMK
            document.querySelectorAll('.tindak-lanjut-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.getElementById('modal-cpmk-id').value = this.dataset.cpmkId;
                    document.getElementById('modal-cpmk-nomor').textContent = this.dataset.cpmkNomor;
                    document.getElementById('modal-cpmk-deskripsi').textContent = this.dataset.cpmkDeskripsi;
                    document.getElementById('modal-cpmk-level').textContent = this.dataset.cpmkLevel;
                    document.getElementById('modal-cpmk-cpl').textContent = this.dataset.cpmkCpl;
                    document.getElementById('modal-cpmk-pelaksanaan').value = this.dataset.cpmkPelaksanaan;
                    document.getElementById('modal-cpmk-evaluasi').value = this.dataset.cpmkEvaluasi;
                });
            });

            // Submit form tindak lanjut
            document.getElementById('tindak-lanjut-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const cpmkId = document.getElementById('modal-cpmk-id').value;
                const pelaksanaan = document.getElementById('modal-cpmk-pelaksanaan').value;
                const evaluasi = document.getElementById('modal-cpmk-evaluasi').value;
                const alertBox = document.getElementById('tindak-lanjut-alert');
                alertBox.style.display = 'none';
                alertBox.innerHTML = '';

                fetch(`/api/cpmk/${cpmkId}/tindak-lanjut`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ pelaksanaan, evaluasi })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alertBox.className = 'alert alert-success';
                        alertBox.innerHTML = 'Tindak lanjut berhasil disimpan.';
                        alertBox.style.display = 'block';
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    } else {
                        alertBox.className = 'alert alert-danger';
                        alertBox.innerHTML = data.message || 'Gagal menyimpan tindak lanjut.';
                        alertBox.style.display = 'block';
                    }
                })
                .catch(err => {
                    alertBox.className = 'alert alert-danger';
                    alertBox.innerHTML = 'Gagal menyimpan tindak lanjut.';
                    alertBox.style.display = 'block';
                });
            });

            // Toggle tampil/sembunyi list mahasiswa
            document.getElementById('toggle-mhs-bobot').addEventListener('click', function() {
                const listDiv = document.getElementById('mhs-bobot-list');
                if (listDiv.style.display === 'none') {
                    listDiv.style.display = 'block';
                    this.textContent = 'Sembunyikan Daftar Mahasiswa';
                } else {
                    listDiv.style.display = 'none';
                    this.innerHTML = '<i class="fas fa-users"></i> Lihat Mahasiswa Nilai Bobot < 1.0';
                }
            });

            // Isi list mahasiswa saat modal dibuka
            document.querySelectorAll('.tindak-lanjut-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    // ...existing code...
                    // Ambil data CPMK id
                    const cpmkId = this.dataset.cpmkId;
                    // Reset list
                    document.getElementById('mhs-bobot-ul').innerHTML = '<li>Memuat data...</li>';
                    document.getElementById('mhs-bobot-list').style.display = 'none';
                    document.getElementById('toggle-mhs-bobot').innerHTML = '<i class="fas fa-users"></i> Lihat Mahasiswa Nilai Bobot < 1.0';

                    // Fetch mahasiswa dengan nilai bobot < 1.0 untuk CPMK ini
                    fetch(`/api/cpmk/${cpmkId}/mahasiswa-bobot-kurang`)
                        .then(res => res.json())
                        .then(data => {
                            const ul = document.getElementById('mhs-bobot-ul');
                            ul.innerHTML = '';
                            if (data.length === 0) {
                                ul.innerHTML = '<li>Tidak ada mahasiswa dengan nilai bobot &lt; 1.0</li>';
                            } else {
                                data.forEach(mhs => {
                                    ul.innerHTML += `<li>${mhs.nim} - ${mhs.nama} <span class="badge bg-danger ms-2">${mhs.bobot}</span></li>`;
                                });
                            }
                        })
                        .catch(() => {
                            document.getElementById('mhs-bobot-ul').innerHTML = '<li>Gagal memuat data.</li>';
                        });
                });
            });
        });
    </script>
    @endpush
</x-default-layout>
