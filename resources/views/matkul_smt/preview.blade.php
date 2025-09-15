<x-default-layout>

@section('content')
<div class="container">
    <h3>Preview Import Mata Kuliah Semester</h3>



    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode MK</th>
                <th>Nama MK</th>
                <th>Kelas</th>
                <th>SKS</th>
                <th>Kuota</th>
                <th>Pengampu (NIP - Nama)</th>
            </tr>
        </thead>
        <tbody>
        @if(!empty($previewRows))
            @foreach($previewRows as $i => $row)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row['kode_mk'] ?? '-' }}</td>
                    <td>{{ $row['nama_mk'] ?? '-' }}</td>
                    <td>{{ $row['kelas'] ?? '-' }}</td>
                    <td>{{ $row['sks'] ?? '-' }}</td>
                    <td>{{ $row['kuota'] ?? '-' }}</td>
                    <td>
                        @php
                            $pengampus = [];
                            for ($j = 1; $j <= 3; $j++) {
                                $nip = $row['nip'.$j] ?? null;
                                $nama = $row['pengampu'.$j] ?? null;
                                if ($nip || $nama) {
                                    $pengampus[] = trim(($nip ?: '-') . ' - ' . ($nama ?: '-'));
                                }
                            }
                        @endphp
                        {!! $pengampus ? implode('<br>', $pengampus) : '-' !!}
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="7">Tidak ada data</td></tr>
        @endif
        </tbody>
    </table>

</table>

    <form action="{{ route('matkul-smt.import.process') }}" method="post">
        @csrf
        <input type="hidden" name="temp_file" value="{{ $tempFile }}">
        <button class="btn btn-success">Confirm Import</button>
        <a href="{{ route('matkul-smt.import.form') }}" class="btn btn-secondary">Cancel</a>
    </form>

    {{-- <hr>
    <h5>Debug: Raw Preview Data</h5>
    <pre>@php var_dump($previewRows); @endphp</pre> --}}
</div>
@endsection

</x-default-layout>
