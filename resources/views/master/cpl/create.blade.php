<x-default-layout>
<div class="container">
    <h1>Tambah CPL</h1>
    <form action="{{ route('master.cpl.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nomor" class="form-label">Nomor CPL</label>
            <input type="number" name="nomor" class="form-control" id="nomor" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama CPL</label>
            <input type="text" name="nama" class="form-control" id="nama" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" id="deskripsi"></textarea>
        </div>
        <div class="mb-3">
            <label for="kurikulum_id" class="form-label">Kurikulum</label>
            <select name="kurikulum_id" id="kurikulum_id" class="form-select" required>
                <option value="">Pilih Kurikulum</option>
                @foreach($kurikulums as $kurikulum)
                    <option value="{{ $kurikulum->id }}" {{ (old('kurikulum_id', $selectedKurikulum)) == $kurikulum->id ? 'selected' : '' }}>{{ $kurikulum->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('master.cpl.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
</x-default-layout>
