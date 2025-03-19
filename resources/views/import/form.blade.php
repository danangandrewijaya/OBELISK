<form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="file">Pilih file Excel:</label>
        <input type="file" name="file" id="file" accept=".xlsx,.xls">
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <button type="submit">Import Excel</button>
</form>
