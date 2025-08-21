<div class="btn-group">
    <a href="{{ route('master.matakuliah-semester.show', $row) }}" class="btn btn-sm btn-info" title="Detail">
        <i class="fas fa-eye"></i>
    </a>
    @if(session('active_role') !== App\Core\Constants::ROLE_DOSEN)
    <a href="{{ route('master.matakuliah-semester.edit', $row) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger" title="Hapus"
        onclick="deleteConfirmation('{{ route('master.matakuliah-semester.destroy', $row) }}')">
        <i class="fas fa-trash"></i>
    </button>
    @endif
</div>

@once
<script>
    function deleteConfirmation(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create form element with CSRF token and DELETE method
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.style.display = 'none';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endonce
