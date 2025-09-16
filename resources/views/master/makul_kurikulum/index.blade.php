<x-default-layout>
<div class="container">
    <h1>Mata Kuliah Kurikulum</h1>
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-title">Data Mata Kuliah Kurikulum</div>
    <a href="{{ route('master.matakuliah-kurikulum.create', ['kurikulum_id' => $kurikulumId]) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus-circle"></i> Tambah
        </a>
    </div>
    <div class="card-body">
        <form method="GET" class="row mb-3 gx-2 align-items-end">
            <div class="col-auto me-3">
                <label for="kurikulum_id" class="form-label mb-0">Kurikulum</label>
                <select name="kurikulum_id" id="kurikulum_id" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="">Semua Kurikulum</option>
                    @foreach($kurikulums as $kurikulum)
                        <option value="{{ $kurikulum->id }}" {{ $kurikulumId == $kurikulum->id ? 'selected' : '' }}>{{ $kurikulum->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label for="search" class="form-label mb-0">Cari</label>
                <input type="text" name="search" id="search" class="form-control form-control-sm" value="{{ $search ?? '' }}" placeholder="Kode/Nama/Kategori">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
            </div>
            <div class="col-auto d-flex align-items-center gap-1" style="min-width:120px;">
                <label for="per_page" class="form-label mb-0 me-1">Tampil</label>
                <select name="per_page" id="per_page" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    @foreach([10, 15, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ request('per_page', 25) == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
                <span class="form-label mb-0 ms-1">/ halaman</span>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('master.matakuliah-kurikulum.index') }}" class="btn btn-light btn-sm border">Reset Filter</a>
            </div>
        </form>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-row-bordered table-striped gy-5">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Semester</th>
                    <th>Kurikulum</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($makulKurikulums as $mk)
                <tr>
                    <td>{{ $mk->kode }}</td>
                    <td>{{ $mk->nama }}</td>
                    <td>{{ $mk->kategori }}</td>
                    <td>{{ $mk->semester }}</td>
                    <td>{{ $mk->kurikulum->nama ?? '-' }}</td>
                    <td>{{ $mk->sks }}</td>
                    <td>
                        <a href="{{ route('master.matakuliah-kurikulum.edit', $mk) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('master.matakuliah-semester.index', ['kurikulum_id' => $mk->kurikulum_id, 'kode' => $mk->kode]) }}" class="btn btn-info btn-sm">Lihat MKS</a>
                        <form action="{{ route('master.matakuliah-kurikulum.destroy', $mk) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $makulKurikulums->links('vendor.pagination.laravel-datatables') }}
        </div>
    </div>
</div>
</div>
</x-default-layout>
