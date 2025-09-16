<x-default-layout>
<div class="container">
    <h1>Tambah PI</h1>
    <form action="{{ route('master.pi.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kurikulum_id" class="form-label">Kurikulum</label>
            <select name="kurikulum_id" id="kurikulum_id" class="form-select" onchange="updateCplOptions()">
                <option value="">Pilih Kurikulum</option>
                @foreach($kurikulums as $kurikulum)
                    <option value="{{ $kurikulum->id }}" {{ (old('kurikulum_id', $selectedKurikulum)) == $kurikulum->id ? 'selected' : '' }}>{{ $kurikulum->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="cpl_id" class="form-label">CPL <span class="text-muted">(Pilih kurikulum dulu)</span></label>
            <select name="cpl_id" id="cpl_id" class="form-select" required disabled>
                <option value="">Pilih CPL</option>
                @foreach($cpls as $cpl)
                    <option value="{{ $cpl->id }}" {{ (old('cpl_id', $selectedCpl)) == $cpl->id ? 'selected' : '' }}>
                        [{{ $cpl->nomor }}] {{ $cpl->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="nomor" class="form-label">Nomor PI</label>
            <input type="number" name="nomor" class="form-control" id="nomor" min="1" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" id="deskripsi"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('master.pi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
    <script>
        function updateCplOptions() {
            var kurikulumId = document.getElementById('kurikulum_id').value;
            window.location.href = '{{ route('master.pi.create') }}?kurikulum_id=' + kurikulumId;
        }
        // Enable CPL selector only if kurikulum is selected
        document.addEventListener('DOMContentLoaded', function() {
            var kurikulumSelect = document.getElementById('kurikulum_id');
            var cplSelect = document.getElementById('cpl_id');
            if (kurikulumSelect.value) {
                cplSelect.removeAttribute('disabled');
            } else {
                cplSelect.setAttribute('disabled', 'disabled');
            }
            kurikulumSelect.addEventListener('change', function() {
                if (kurikulumSelect.value) {
                    cplSelect.removeAttribute('disabled');
                } else {
                    cplSelect.setAttribute('disabled', 'disabled');
                }
            });
        });
    </script>
</div>
</x-default-layout>
