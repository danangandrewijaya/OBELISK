<x-default-layout>
<div class="container">
    <h1>Tambah Kurikulum</h1>
    <form action="{{ route('master.kurikulum.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Kurikulum</label>
            <input type="text" name="nama" class="form-control" id="nama" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('master.kurikulum.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
</x-default-layout>
