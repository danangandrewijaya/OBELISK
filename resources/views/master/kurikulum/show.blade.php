<x-default-layout>
<div class="container">
    <h1>Detail Kurikulum: {{ $kurikulum->nama }}</h1>
    <p><strong>Prodi ID:</strong> {{ $kurikulum->prodi_id }}</p>
    <h3>Daftar CPL</h3>
    <a href="{{ route('master.cpl.create', ['kurikulum_id' => $kurikulum->id]) }}" class="btn btn-primary mb-2">Tambah CPL</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kurikulum->cpls as $cpl)
            <tr>
                <td>{{ $cpl->nomor }}</td>
                <td>{{ $cpl->nama }}</td>
                <td>{{ $cpl->deskripsi }}</td>
                <td>
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
    <a href="{{ route('master.kurikulum.index') }}" class="btn btn-secondary">Kembali</a>
</div>
</x-default-layout>
