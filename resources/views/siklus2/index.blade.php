<x-default-layout>
    @section('title', 'Siklus PI')

    @section('breadcrumbs')
        {{ Breadcrumbs::render('siklus2.index') }}
    @endsection

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="card-title">Daftar Siklus PI</div>
            <div class="card-toolbar">
                <a href="{{ route('siklus2.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Siklus
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-row-bordered table-striped gy-5">
                    <thead>
                        <tr class="fw-bold fs-6 text-muted">
                            <th>Nama</th>
                            <th>Kurikulum</th>
                            <th>Tahun Mulai</th>
                            <th>Tahun Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siklus as $item)
                            <tr>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->kurikulum->nama }}</td>
                                <td>{{ $item->tahun_mulai }}</td>
                                <td>{{ $item->tahun_selesai }}</td>
                                <td>
                                    <a href="{{ route('siklus2.show', $item) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <a href="{{ route('siklus2.configure', $item) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-cog"></i> Konfigurasi
                                    </a>
                                    <a href="{{ route('siklus2.edit', $item) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('siklus2.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data siklus</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-default-layout>
