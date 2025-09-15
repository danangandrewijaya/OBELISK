<x-default-layout>

@section('content')
<div class="container">
    <h3>Import Mata Kuliah Semester (Standalone)</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('matkul-smt.import.preview') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="excel_file">File Excel</label>
            <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx,.xls" required>
        </div>
        <button class="btn btn-primary mt-2">Upload & Preview</button>
    </form>
</div>
@endsection

</x-default-layout>
