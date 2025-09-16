<x-default-layout>
<div class="container">
    <h1>Detail Mata Kuliah Kurikulum</h1>
<div class="card shadow-sm">
    <div class="card-header">
        <div class="card-title">Detail Mata Kuliah Kurikulum</div>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Kode</dt>
            <dd class="col-sm-9">{{ $makulKurikulum->kode }}</dd>
            <dt class="col-sm-3">Nama</dt>
            <dd class="col-sm-9">{{ $makulKurikulum->nama }}</dd>
            <dt class="col-sm-3">Kurikulum</dt>
            <dd class="col-sm-9">{{ $makulKurikulum->kurikulum->nama ?? '-' }}</dd>
            <dt class="col-sm-3">SKS</dt>
            <dd class="col-sm-9">{{ $makulKurikulum->sks }}</dd>
        </dl>
    <a href="{{ route('master.matakuliah-kurikulum.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
</div>
</x-default-layout>
