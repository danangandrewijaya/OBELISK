<x-default-layout>
<div class="container">
    <h1>Edit CPL</h1>
    <form action="{{ route('master.cpl.update', $cpl) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nomor" class="form-label">Nomor CPL</label>
            <input type="number" name="nomor" class="form-control" id="nomor" value="{{ $cpl->nomor }}" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama CPL</label>
            <input type="text" name="nama" class="form-control" id="nama" value="{{ $cpl->nama }}" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" id="deskripsi">{{ $cpl->deskripsi }}</textarea>
        </div>
        <div class="mb-3">
            <label for="kurikulum_id" class="form-label">Kurikulum</label>
            <select name="kurikulum_id" id="kurikulum_id" class="form-select" required>
                <option value="">Pilih Kurikulum</option>
                @foreach($kurikulums as $kurikulum)
                    <option value="{{ $kurikulum->id }}" {{ $cpl->kurikulum_id == $kurikulum->id ? 'selected' : '' }}>{{ $kurikulum->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('master.cpl.index', ['kurikulum_id' => $cpl->kurikulum_id]) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
</x-default-layout>
