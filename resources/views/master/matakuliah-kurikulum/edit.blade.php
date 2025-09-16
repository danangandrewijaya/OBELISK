<x-default-layout>
<div class="container">
    <h1>Edit Mata Kuliah Kurikulum</h1>
<div class="card shadow-sm">
    <div class="card-header">
        <div class="card-title">Edit Mata Kuliah Kurikulum</div>
    </div>
    <div class="card-body">
    <form action="{{ route('master.matakuliah-kurikulum.update', $makulKurikulum) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="kode" class="form-label">Kode</label>
                <input type="text" name="kode" class="form-control" id="kode" value="{{ old('kode', $makulKurikulum->kode) }}" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama', $makulKurikulum->nama) }}" required>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <input type="text" name="kategori" class="form-control" id="kategori" value="{{ old('kategori', $makulKurikulum->kategori) }}" placeholder="Wajib/Pilihan" required>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <input type="text" name="semester" class="form-control" id="semester" value="{{ old('semester', $makulKurikulum->semester) }}" placeholder="Semester" required>
            </div>
            <div class="mb-3">
                <label for="kurikulum_id" class="form-label">Kurikulum</label>
                <select name="kurikulum_id" id="kurikulum_id" class="form-select" required>
                    <option value="">Pilih Kurikulum</option>
                    @foreach($kurikulums as $kurikulum)
                        <option value="{{ $kurikulum->id }}" {{ (old('kurikulum_id', $makulKurikulum->kurikulum_id)) == $kurikulum->id ? 'selected' : '' }}>{{ $kurikulum->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="sks" class="form-label">SKS</label>
                <input type="number" name="sks" class="form-control" id="sks" value="{{ old('sks', $makulKurikulum->sks) }}" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('master.matakuliah-kurikulum.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
</div>
</x-default-layout>
