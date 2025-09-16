<x-default-layout>
<div class="container">
    <h1>Edit Kurikulum</h1>
    <form action="{{ route('master.kurikulum.update', $kurikulum) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Kurikulum</label>
            <input type="text" name="nama" class="form-control" id="nama" value="{{ $kurikulum->nama }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('master.kurikulum.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
</x-default-layout>
