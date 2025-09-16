<x-default-layout>
    @section('title', 'Master Kurikulum')
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="card-title">Data Kurikulum</div>
            <div class="card-toolbar">
                <a href="{{ route('master.kurikulum.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle"></i> Tambah Kurikulum
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-row-bordered table-striped gy-5">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kurikulums as $kurikulum)
                    <tr>
                        <td>{{ $kurikulum->nama }}</td>
                        <td>
                            <a href="{{ route('master.kurikulum.edit', $kurikulum) }}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{ route('master.cpl.index', ['kurikulum_id' => $kurikulum->id]) }}" class="btn btn-info btn-sm">Lihat CPL</a>
                            <form action="{{ route('master.kurikulum.destroy', $kurikulum) }}" method="POST" style="display:inline-block;">
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
