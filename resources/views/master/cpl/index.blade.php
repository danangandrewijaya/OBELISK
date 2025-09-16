<x-default-layout>
@section('title', 'Master CPL')
<div class="card shadow-sm">
    <div class="card-header">
        <div class="card-title">Data CPL</div>
        <div class="card-toolbar">
            <a href="{{ route('master.cpl.create', ['kurikulum_id' => $kurikulumId]) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah CPL
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-4">
                <label for="kurikulum_id" class="form-label">Filter Kurikulum</label>
                <select name="kurikulum_id" id="kurikulum_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Kurikulum</option>
                    @foreach($kurikulums as $kurikulum)
                        <option value="{{ $kurikulum->id }}" {{ $kurikulumId == $kurikulum->id ? 'selected' : '' }}>{{ $kurikulum->nama }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-row-bordered table-striped gy-5">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Kurikulum</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cpls as $cpl)
                <tr>
                    <td>{{ $cpl->nomor }}</td>
                    <td>{{ $cpl->nama }}</td>
                    <td>{{ $cpl->deskripsi }}</td>
                    <td>{{ $cpl->kurikulum->nama ?? '-' }}</td>
                    <td>
                        <a href="{{ route('master.pi.index', ['cpl_id' => $cpl->id, 'kurikulum_id' => $cpl->kurikulum_id]) }}" class="btn btn-info btn-sm mb-1">Lihat PI</a>
                        <a href="{{ route('master.cpl.edit', $cpl) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('master.cpl.destroy', $cpl) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-default-layout>
