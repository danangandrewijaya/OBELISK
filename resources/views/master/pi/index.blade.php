<x-default-layout>
@section('title', 'Master PI')
<div class="card shadow-sm">
    <div class="card-header">
        <div class="card-title">Data PI</div>
        <div class="card-toolbar">
            <a href="{{ route('master.pi.create', ['cpl_id' => $cplId, 'kurikulum_id' => $kurikulumId]) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah PI
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter form: inline, only one row, Reset right after CPL -->
        <!-- Filter: each section (kurikulum, cpl, reset) is one row, but label+input inline -->
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
            <div class="col-auto me-3">
                @if(empty($kurikulumId))
                    <div class="form-text text-danger">Pilih kurikulum dulu untuk menampilkan CPL</div>
                @endif
                <label for="cpl_id" class="form-label mb-0">CPL</label>
                <select name="cpl_id" id="cpl_id" class="form-select form-select-sm w-auto" onchange="this.form.submit()" {{ empty($kurikulumId) ? 'disabled' : '' }}>
                    <option value="">Semua CPL</option>
                    @if(empty($kurikulumId))
                        <!-- Guidance if kurikulum not selected -->
                    @else
                        @foreach($cpls as $cpl)
                            <option value="{{ $cpl->id }}" {{ $cplId == $cpl->id ? 'selected' : '' }}>[{{ $cpl->nomor }}] {{ $cpl->nama }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-auto align-self-end">
                <a href="{{ route('master.pi.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-filter"></i> Reset Filter</a>
            </div>
        </form>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-row-bordered table-striped gy-5">
            <thead>
                <tr>
                    <th>CPL</th>
                    <th>Nomor PI</th>
                    <th>Deskripsi</th>
                    <th>Kurikulum</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pis as $pi)
                <tr>
                    <td>
                        @if($pi->cpl)
                            <span class="badge bg-secondary">{{ $pi->cpl->nomor }}</span> {{ $pi->cpl->nama }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $pi->nomor }}</td>
                    <td>{{ $pi->deskripsi }}</td>
                    <td>{{ $pi->cpl && $pi->cpl->kurikulum ? $pi->cpl->kurikulum->nama : '-' }}</td>
                    <td>
                        <a href="{{ route('master.pi.edit', $pi) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('master.pi.destroy', $pi) }}" method="POST" style="display:inline-block;">
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
