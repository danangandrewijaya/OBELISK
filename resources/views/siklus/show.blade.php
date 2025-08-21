<x-default-layout>
    @section('title', $siklus->nama)

    @section('breadcrumbs')
        {{ Breadcrumbs::render('siklus.show', $siklus) }}
    @endsection

    <div class="card shadow-sm mb-5">
        <div class="card-header">
            <div class="card-title">Detail Siklus</div>
            <div class="card-toolbar">
                <a href="{{ route('siklus.configure', $siklus) }}" class="btn btn-sm btn-primary me-2">
                    <i class="fas fa-cog"></i> Konfigurasi
                </a>
                <a href="{{ route('siklus.edit', $siklus) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row mb-8">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th class="w-25">Nama Siklus</th>
                            <td>{{ $siklus->nama }}</td>
                        </tr>
                        <tr>
                            <th>Kurikulum</th>
                            <td>{{ $siklus->kurikulum->nama }}</td>
                        </tr>
                        <tr>
                            <th>Periode</th>
                            <td>{{ $siklus->tahun_mulai }} - {{ $siklus->tahun_selesai }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <h3 class="mb-5">Rata-rata Ketercapaian CPL</h3>

            @if(count($cplData) > 0)
                <div class="row">
                    <div class="col-xl-4 mb-5">
                        <div class="card card-flush h-100">
                            <div class="card-header pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-dark">Ringkasan CPL</span>
                                </h3>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column">
                                    @foreach($cplData as $cplId => $data)
                                        <div class="d-flex align-items-center mb-5">
                                            <span class="bullet bullet-vertical h-40px bg-{{ $data['rata_rata'] >= 75 ? 'success' : ($data['rata_rata'] >= 65 ? 'warning' : 'danger') }} me-3"></span>
                                            <div class="flex-grow-1">
                                                <a href="#cpl-{{ $data['cpl']->nomor }}" class="text-gray-800 text-hover-primary fw-bold fs-6">CPL {{ $data['cpl']->nomor }}: {{ $data['cpl']->nama }}</a>
                                                <span class="text-muted fw-semibold d-block">Dari {{ $data['total_mk'] }} mata kuliah</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="fw-bold fs-4 text-{{ $data['rata_rata'] >= 75 ? 'success' : ($data['rata_rata'] >= 65 ? 'warning' : 'danger') }}">{{ $data['rata_rata'] }}</div>
                                                <div class="ms-2">
                                                    <span class="badge badge-light-{{ $data['rata_rata'] >= 75 ? 'success' : ($data['rata_rata'] >= 65 ? 'warning' : 'danger') }}">
                                                        {{ $data['rata_rata'] >= 75 ? 'Baik' : ($data['rata_rata'] >= 65 ? 'Cukup' : 'Kurang') }}
                                                    </span>
                                                </div>
                                                <div class="ms-2">
                                                    <span class="badge badge-light-{{ $data['rata_rata_4'] >= 3.0 ? 'success' : ($data['rata_rata_4'] >= 2.5 ? 'warning' : 'danger') }}">
                                                        Skala 4: {{ number_format($data['rata_rata_4'], 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8 mb-5">
                        <div class="card card-flush h-100">
                            <div class="card-header pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-dark">Grafik Ketercapaian CPL</span>
                                </h3>
                                <div class="card-toolbar">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="scale-toggle">
                                        <label class="form-check-label" for="scale-toggle">
                                            Skala 4
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div id="cpl-chart-100" style="height: 350px;"></div>
                                <div id="cpl-chart-4" style="height: 350px; display: none;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CPL per-angkatan comparison controls + chart -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card card-flush">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <label class="form-label mb-1">Pilih Angkatan</label>
                                    <select id="angkatan-select" class="form-select" multiple style="min-width:200px">
                                        @if(!empty($cplPerAngkatanData) && count($cplPerAngkatanData['years']) > 0)
                                            @foreach($cplPerAngkatanData['years'] as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0">Perbandingan Rata-rata CPL per Angkatan</h5>
                                    <div class="text-muted small">Pilih satu atau beberapa angkatan untuk membandingkan rata-rata tiap CPL.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- per-angkatan summary container removed; using comparison chart below -->

                @foreach($cplData as $cplId => $data)
                    <div class="card mb-5" id="cpl-{{ $data['cpl']->nomor }}">
                        <div class="card-header">
                            <h3 class="card-title">CPL {{ $data['cpl']->nomor }}: {{ $data['cpl']->nama }}</h3>
                            <div class="card-toolbar">
                                <span class="badge badge-light-{{ $data['rata_rata'] >= 75 ? 'success' : ($data['rata_rata'] >= 65 ? 'warning' : 'danger') }} fs-6">
                                    Rata-rata: {{ $data['rata_rata'] }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-5">
                                <p class="text-gray-600">{!! nl2br(e($data['cpl']->deskripsi)) !!}</p>
                            </div>

                            @if(count($data['detail']) > 0)
                                <div class="mb-5">
                                    <h4 class="mb-3">Detail Nilai Per Mata Kuliah</h4>
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered table-striped gy-3">
                                            <thead>
                                                <tr class="fw-bold fs-6 text-gray-800">
                                                    <th>Kode</th>
                                                    <th>Mata Kuliah</th>
                                                    <th>Tahun</th>
                                                    <th>Semester</th>
                                                    <th>Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data['detail'] as $detail)
                                                    <tr>
                                                        <td>{{ $detail['kode'] }}</td>
                                                        <td>{{ $detail['nama'] }}</td>
                                                        <td>{{ $detail['tahun'] }}</td>
                                                        <td>{{ $detail['semester'] }}</td>
                                                        <td>
                                                            <span class="badge badge-light-{{ $detail['nilai'] >= 75 ? 'success' : ($detail['nilai'] >= 65 ? 'warning' : 'danger') }}">
                                                                {{ $detail['nilai'] }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="fas fa-exclamation-triangle fs-2"></i>
                                        </div>
                                        <div>
                                            <h4 class="mb-1">Tidak ada data</h4>
                                            <p>Belum ada data nilai untuk mata kuliah yang dipilih dalam periode siklus ini.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

            @else
                <div class="alert alert-info">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle fs-2"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">Belum ada data</h4>
                            <p>Siklus ini belum memiliki konfigurasi CPL dan mata kuliah. Silakan klik tombol 'Konfigurasi' untuk menambahkan mata kuliah ke setiap CPL.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(count($cplData) > 0)
                // Prepare data for ApexCharts
                const cplLabels = [];
                const cplValues100 = [];
                const cplColors100 = [];
                const cplValues4 = [];
                const cplColors4 = [];

                @foreach($cplData as $cplId => $data)
                    cplLabels.push('CPL {{ $data['cpl']->nomor }}');

                    // Data for scale 100
                    cplValues100.push({{ $data['rata_rata'] }});
                    cplColors100.push('{{ $data['rata_rata'] >= 75 ? '#50cd89' : ($data['rata_rata'] >= 65 ? '#ffc700' : '#f1416c') }}');

                    // Data for scale 4
                    cplValues4.push({{ $data['rata_rata_4'] }});
                    cplColors4.push('{{ $data['rata_rata_4'] >= 3.0 ? '#50cd89' : ($data['rata_rata_4'] >= 2.5 ? '#ffc700' : '#f1416c') }}');
                @endforeach

                const chartOptions = {
                    series: [{
                        name: 'Nilai Rata-rata',
                        data: cplValues100
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            distributed: true,
                            borderRadius: 4,
                            horizontal: false,
                            columnWidth: '40%',
                        }
                    },
                    legend: { show: false },
                    dataLabels: { enabled: false },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: cplLabels,
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: {
                            style: {
                                colors: '#823fdb',
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        min: 0,
                        max: 100,
                        labels: {
                            style: { colors: '#823fdb', fontSize: '12px' },
                            formatter: function (val) {
                                return val.toFixed(2);
                            }
                        }
                    },
                    fill: { opacity: 1, colors: cplColors100 },
                    colors: cplColors100,
                    states: {
                        normal: { filter: { type: 'none', value: 0 } },
                        hover: { filter: { type: 'darken', value: 0.15 } },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: { type: 'darken', value: 0.35 }
                        }
                    },
                    tooltip: {
                        style: { fontSize: '12px' },
                        y: {
                            formatter: function (val) {
                                return val.toFixed(2);
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f1f1f1',
                        strokeDashArray: 4,
                        yaxis: { lines: { show: true } }
                    }
                };

                const chart = new ApexCharts(document.querySelector('#cpl-chart-100'), chartOptions);
                chart.render();

                // Toggle logic for main CPL chart
                const toggle = document.getElementById('scale-toggle');
                toggle.addEventListener('change', function() {
                    if (this.checked) {
                        chart.updateOptions({
                            series: [{ data: cplValues4 }],
                            yaxis: { min: 0, max: 4, labels: { formatter: function (val) { return val.toFixed(2); } } },
                            fill: { colors: cplColors4 }, colors: cplColors4
                        });
                    } else {
                        chart.updateOptions({
                            series: [{ data: cplValues100 }],
                            yaxis: { min: 0, max: 100, labels: { formatter: function (val) { return val.toFixed(2); } } },
                            fill: { colors: cplColors100 }, colors: cplColors100
                        });
                    }
                });

                // --- Per-angkatan chart ---
                @if(!empty($perAngkatanData) && count($perAngkatanData['labels']) > 0)
                    const angkatanLabels = {!! json_encode($perAngkatanData['labels']) !!};
                    const angkatanValues100 = {!! json_encode($perAngkatanData['values_100']) !!};
                    const angkatanValues4 = {!! json_encode($perAngkatanData['values_4']) !!};

                    const angkatanColors100 = angkatanValues100.map(function(v) { return v >= 75 ? '#50cd89' : (v >= 65 ? '#ffc700' : '#f1416c'); });
                    const angkatanColors4 = angkatanValues4.map(function(v) { return v >= 3.0 ? '#50cd89' : (v >= 2.5 ? '#ffc700' : '#f1416c'); });

                    const angkatanOptions = {
                        series: [{ name: 'Rata-rata per Angkatan', data: angkatanValues100 }],
                        chart: { type: 'bar', height: 320, toolbar: { show: false } },
                        plotOptions: { bar: { distributed: true, borderRadius: 4, columnWidth: '50%' } },
                        dataLabels: { enabled: false },
                        xaxis: { categories: angkatanLabels, labels: { style: { colors: '#823fdb', fontSize: '12px' } } },
                        yaxis: { min: 0, max: 100, labels: { formatter: function(v){ return v.toFixed(2); } } },
                        fill: { colors: angkatanColors100 },
                        colors: angkatanColors100,
                        tooltip: { y: { formatter: function(v){ return v.toFixed(2); } } },
                        grid: { borderColor: '#f1f1f1', strokeDashArray: 4 }
                    };

                @endif

                // --- CPL per-angkatan comparison ---
                @if(!empty($cplPerAngkatanData) && count($cplPerAngkatanData['years']) > 0)
                    // Prepare labels and datasets
                    const comparisonCplLabels = {!! json_encode($cplPerAngkatanData['cpl_labels']) !!};
                    const comparisonYears = {!! json_encode($cplPerAngkatanData['years']) !!};
                    const comparisonDatasets = {!! json_encode($cplPerAngkatanData['datasets']) !!};

                    // Chart container
                    const comparisonContainer = document.createElement('div');
                    comparisonContainer.id = 'cpl-per-angkatan-chart';
                    comparisonContainer.style.height = '360px';

                    // Prefer stable insertion point: before the per-angkatan card if present, otherwise after the select card
                    const perAngkatanCard = document.querySelector('#per-angkatan-chart-100') ? document.querySelector('#per-angkatan-chart-100').closest('.card') : null;
                    if (perAngkatanCard && perAngkatanCard.parentNode) {
                        perAngkatanCard.parentNode.insertBefore(comparisonContainer, perAngkatanCard);
                    } else {
                        const selectCard = document.getElementById('angkatan-select').closest('.card');
                        selectCard.parentNode.insertBefore(comparisonContainer, selectCard.nextSibling);
                    }

                    // Debug: print loaded dataset shapes
                    console.log('CPL Labels:', cplLabels);
                    console.log('Comparison CPL Labels:', comparisonCplLabels);
                    console.log('Available Years:', comparisonYears);
                    console.log('Datasets (per year):', comparisonDatasets);

                    let comparisonChart = null;

                    function renderComparison(selectedYears) {
                        console.log('Render comparison for years:', selectedYears);

                        // If no selection, clear chart
                        if (!selectedYears || selectedYears.length === 0) {
                            if (comparisonChart) {
                                try { comparisonChart.destroy(); } catch(e){ console.warn(e); }
                                comparisonChart = null;
                            }
                            comparisonContainer.innerHTML = '<div class="text-center text-muted">Pilih angkatan untuk mulai membandingkan.</div>';
                            return;
                        }

                        // Ensure container is attached and empty before rendering
                        comparisonContainer.innerHTML = '';

                        // stable color palette
                        const palette = ['#4BC0C0','#36A2EB','#FF6384','#FFCE56','#8E44AD','#2ECC71','#E67E22','#3498DB'];

                        const series = selectedYears.map(function(year, idx) {
                            const d = comparisonDatasets[year];
                            console.log('Year', year, 'data raw:', d);

                            // Normalize data array length to match labels
                            const needed = comparisonCplLabels.length;
                            let dataArr = [];
                            if (d && Array.isArray(d.data)) {
                                dataArr = d.data.slice(0, needed);
                                // pad with zeros if shorter
                                while (dataArr.length < needed) dataArr.push(0);
                            } else {
                                dataArr = Array(needed).fill(0);
                            }

                            // Ensure numeric values
                            dataArr = dataArr.map(v => (v === null || v === undefined || isNaN(v)) ? 0 : Number(v));

                            return {
                                name: year,
                                data: dataArr,
                                color: palette[idx % palette.length]
                            };
                        });

                        // Final safety: ensure every series has data array equal to labels length
                        series.forEach(s => {
                            if (!s.data || s.data.length !== comparisonCplLabels.length) {
                                s.data = Array(comparisonCplLabels.length).fill(0);
                            }
                        });

                        const options = {
                            series: series,
                            chart: { type: 'bar', height: 360, stacked: false, toolbar: { show: false } },
                            plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
                            xaxis: { categories: comparisonCplLabels },
                            yaxis: { min: 0, max: 100 },
                            legend: { position: 'top' },
                            tooltip: { y: { formatter: function(v){ return v.toFixed(2); } } },
                            colors: series.map(s => s.color)
                        };

                        try {
                            if (comparisonChart) { try { comparisonChart.destroy(); } catch(e){ console.warn(e); } }
                            comparisonChart = new ApexCharts(document.querySelector('#cpl-per-angkatan-chart'), options);
                            comparisonChart.render();
                        } catch (err) {
                            console.error('Error rendering comparison chart', err, {series, comparisonCplLabels});
                            comparisonContainer.innerHTML = '<div class="text-danger">Terjadi kesalahan saat menampilkan grafik. Cek console untuk detail.</div>';
                        }
                    }

                    // Init message
                    comparisonContainer.innerHTML = '<div class="text-center text-muted">Pilih angkatan untuk mulai membandingkan.</div>';

                    // Wire selection
                    const angkatanSelect = document.getElementById('angkatan-select');
                    angkatanSelect.addEventListener('change', function() {
                        const selected = Array.from(this.selectedOptions).map(o => o.value);
                        renderComparison(selected);
                    });
                @endif
            @endif
        });
    </script>
    @endpush
</x-default-layout>
