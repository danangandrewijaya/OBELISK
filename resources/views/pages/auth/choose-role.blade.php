<x-default-layout>

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pilih Role</h3></div>
                <div class="card-body">
                    @if(isset($assignments))
                        @php
                            $currentRole = session('active_role');
                            $currentProdi = session('prodi_id');
                        @endphp
                        <div class="list-group">
                            @foreach($assignments as $a)
                                @php
                                    $isSelected = false;
                                    if($currentRole && $currentRole === $a->role_name) {
                                        // consider prodi match when both are empty or equal
                                        if(isset($currentProdi) && $currentProdi != '') {
                                            if($a->prodi_id == $currentProdi) $isSelected = true;
                                        } else {
                                            if(empty($a->prodi_id)) $isSelected = true;
                                        }
                                    }
                                @endphp

                                <form method="POST" action="{{ route('auth.choose-role.set') }}" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="role" value="{{ $a->role_name }}">
                                    <input type="hidden" name="prodi_id" value="{{ $a->prodi_id }}">
                                    <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                                        <div>
                                            <div class="fw-bold text-uppercase">{{ $a->role_name }}</div>
                                            <div class="fw-bold text-muted">{{ $a->prodi_name ?? 'Semua Prodi' }}</div>
                                        </div>
                                        @if($isSelected)
                                            <button type="button" class="btn btn-success" disabled>
                                                Terpilih
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-primary">Pilih</button>
                                        @endif
                                    </div>
                                </form>
                            @endforeach
                        </div>
                    @else
                        <form method="POST" action="{{ route('auth.choose-role.set') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role" class="form-select" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ session('active_role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Pilih</button>
                        </form>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger mt-3">
                            {{ $errors->first() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

</x-default-layout>
