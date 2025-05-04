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
                            </div>
                            <div class="card-body pt-0">
                                <div id="cpl-chart" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

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
                const cplValues = [];
                const cplColors = [];

                @foreach($cplData as $cplId => $data)
                    cplLabels.push('CPL {{ $data['cpl']->nomor }}');
                    cplValues.push({{ $data['rata_rata'] }});
                    cplColors.push('{{ $data['rata_rata'] >= 75 ? '#50cd89' : ($data['rata_rata'] >= 65 ? '#ffc700' : '#f1416c') }}');
                @endforeach

                // Initialize chart
                const chart = new ApexCharts(document.querySelector('#cpl-chart'), {
                    series: [{
                        name: 'Nilai Rata-rata',
                        data: cplValues
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            distributed: true,
                            borderRadius: 4,
                            horizontal: false,
                            columnWidth: '40%',
                        }
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: cplLabels,
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
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
                            style: {
                                colors: '#823fdb',
                                fontSize: '12px'
                            }
                        }
                    },
                    fill: {
                        opacity: 1,
                        colors: cplColors
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0,
                            }
                        },
                        hover: {
                            filter: {
                                type: 'darken',
                                value: 0.15,
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'darken',
                                value: 0.35,
                            }
                        }
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function (val) {
                                return val.toFixed(2);
                            }
                        }
                    },
                    colors: cplColors,
                    grid: {
                        borderColor: '#f1f1f1',
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    }
                });

                chart.render();
            @endif
        });
    </script>
    @endpush
</x-default-layout>
