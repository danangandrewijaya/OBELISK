<x-default-layout>
    @section('title', 'Tambah Siklus')

    @section('breadcrumbs')
        {{ Breadcrumbs::render('siklus2.create') }}
    @endsection

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="card-title">Tambah Siklus</div>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('siklus2.store') }}" method="POST">
                @csrf

                <div class="mb-10">
                    <label for="nama" class="required form-label">Nama Siklus</label>
                    <input type="text" class="form-control form-control-solid @error('nama') is-invalid @enderror"
                        name="nama" id="nama" value="{{ old('nama') }}" required>
                    @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-10">
                    <label for="kurikulum_id" class="required form-label">Kurikulum</label>
                    <select name="kurikulum_id" id="kurikulum_id" class="form-select form-select-solid @error('kurikulum_id') is-invalid @enderror"
                        required data-control="select2" data-placeholder="Pilih kurikulum">
                        <option></option>
                        @foreach($kurikulums as $kurikulum)
                            <option value="{{ $kurikulum->id }}" {{ old('kurikulum_id') == $kurikulum->id ? 'selected' : '' }}>
                                {{ $kurikulum->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kurikulum_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="row mb-10">
                    <div class="col-md-6">
                        <label for="tahun_mulai" class="required form-label">Tahun Mulai</label>
                        <input type="number" class="form-control form-control-solid @error('tahun_mulai') is-invalid @enderror"
                            name="tahun_mulai" id="tahun_mulai" value="{{ old('tahun_mulai', date('Y')) }}" required min="2000" max="{{ date('Y') + 5 }}">
                        <div class="form-text">Tahun awal dari periode siklus (semester ganjil)</div>
                        @error('tahun_mulai')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tahun_selesai" class="required form-label">Tahun Selesai</label>
                        <input type="number" class="form-control form-control-solid @error('tahun_selesai') is-invalid @enderror"
                            name="tahun_selesai" id="tahun_selesai" value="{{ old('tahun_selesai', date('Y') + 3) }}" required min="2000" max="{{ date('Y') + 10 }}">
                        <div class="form-text">Tahun akhir dari periode siklus (semester genap)</div>
                        @error('tahun_selesai')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="separator separator-dashed mt-8 mb-8"></div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('siklus2.index') }}" class="btn btn-light me-3">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-default-layout>
