<x-default-layout>
<div class="container">
    <h1>Edit PI</h1>
    <form action="{{ route('master.pi.update', $pi) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="kurikulum_id" class="form-label">Kurikulum</label>
            <select name="kurikulum_id" id="kurikulum_id" class="form-select" disabled>
                @foreach($kurikulums as $kurikulum)
                    <option value="{{ $kurikulum->id }}" {{ ($pi->cpl && $pi->cpl->kurikulum_id == $kurikulum->id) ? 'selected' : '' }}>{{ $kurikulum->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="cpl_id" class="form-label">CPL</label>
            <select name="cpl_id" id="cpl_id" class="form-select" required>
                <option value="">Pilih CPL</option>
                @foreach($cpls as $cpl)
                    <option value="{{ $cpl->id }}" {{ $pi->cpl_id == $cpl->id ? 'selected' : '' }}>
                        [{{ $cpl->nomor }}] {{ $cpl->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="nomor" class="form-label">Nomor PI</label>
            <input type="number" name="nomor" class="form-control" id="nomor" value="{{ $pi->nomor }}" min="1" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" id="deskripsi">{{ $pi->deskripsi }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('master.pi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
    <!-- No JS needed: kurikulum is fixed, CPL always enabled -->
</div>
</x-default-layout>
