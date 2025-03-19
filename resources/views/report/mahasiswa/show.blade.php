<x-default-layout>
    @section('title')
        Rapor CPL Mahasiswa
    @endsection

    {{-- @section('breadcrumbs')
        {{ Breadcrumbs::render('report.mahasiswa.show', $mahasiswa) }}
    @endsection --}}

    <div class="card">
        <div class="card-header">
            <div class="card-title">Data Mahasiswa</div>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-4">
                    <div class="fw-bold">NIM</div>
                    <div>{{ $mahasiswa->nim }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Nama</div>
                    <div>{{ $mahasiswa->nama }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Program Studi</div>
                    <div>{{ $mahasiswa->prodi->nama }}</div>
                </div>
            </div>

            <div class="separator my-5"></div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Data Nilai</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Semester</th>
                                    <th>Kelas</th>
                                    <th>Nilai Angka</th>
                                    <th>Nilai Huruf</th>
                                    <th>Outcome</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mahasiswa->nilai as $nilai)
                                <tr>
                                    <td>{{ $nilai->mks->mkk->kode }} - {{ $nilai->mks->mkk->nama }}</td>
                                    <td>{{ $nilai->mks->mkk->sks }}</td>
                                    <td>{{ $nilai->semester }}</td>
                                    <td>{{ $nilai->kelas }}</td>
                                    <td>{{ $nilai->nilai_akhir_angka }}</td>
                                    <td>{{ $nilai->nilai_akhir_huruf }}</td>
                                    <td data-bs-toggle="tooltip" data-bs-placement="top" title="@foreach($nilai->nilaiCpmk as $nilaiCpmk) CPMK{{ $nilaiCpmk->cpmk->nomor }} ({{ $nilaiCpmk->nilai_angka }})@if(!$loop->last), @endif @endforeach">
                                        {{ $nilai->outcome }} <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="separator my-5"></div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Capaian CPL</div>
                </div>
                <div class="card-body">
                    <div id="cpl_chart"></div>
                </div>
            </div>

            <div class="separator my-5"></div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Capaian CPL per Semester</div>
                </div>
                <div class="card-body">
                    <div id="cpl_semester_chart"></div>

                    <div class="separator my-5"></div>

                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th style="min-width: 300px;">CPL</th>
                                    @foreach($mahasiswa->nilai->pluck('semester')->unique()->sort() as $semester)
                                        <th class="text-center">Semester {{ $semester }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cpls as $cpl)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">CPL {{ $cpl->nomor }}</span>
                                                <span class="text-muted">{{ $cpl->nama }}</span>
                                            </div>
                                        </td>
                                        @foreach($mahasiswa->nilai->pluck('semester')->unique()->sort() as $semester)
                                            @php
                                                $semesterNilai = $mahasiswa->nilai->where('semester', $semester);
                                                $cplTotal = 0;
                                                $cplCount = 0;

                                                foreach($semesterNilai as $nilai) {
                                                    foreach($nilai->nilaiCpmk as $nilaiCpmk) {
                                                        foreach($nilaiCpmk->cpmk->cpmkCpl as $cpmkCpl) {
                                                            if($cpmkCpl->cpl_id == $cpl->id) {
                                                                $cplTotal += $nilaiCpmk->nilai_bobot;
                                                                $cplCount++;
                                                            }
                                                        }
                                                    }
                                                }

                                                $average = $cplCount > 0 ? round($cplTotal / $cplCount, 2) : null;
                                            @endphp
                                            <td class="text-center">
                                                @if($average !== null)
                                                    <span class="badge {{ $average >= 2.5 ? 'badge-light-success' : 'badge-light-danger' }}"
                                                          data-bs-toggle="tooltip"
                                                          data-bs-placement="top"
                                                          title="CPL {{ $cpl->nomor }}: {{ $cpl->nama }}">
                                                        {{ number_format($average, 2) }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-light">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            // Calculate overall CPL values
            var cplValues = {};
            var cplCounts = {};

            @foreach($mahasiswa->nilai as $nilai)
                @foreach($nilai->nilaiCpmk as $nilaiCpmk)
                    @foreach($nilaiCpmk->cpmk->cpmkCpl as $cpmkCpl)
                        if (!cplValues[{{ $cpmkCpl->cpl_id }}]) {
                            cplValues[{{ $cpmkCpl->cpl_id }}] = 0;
                            cplCounts[{{ $cpmkCpl->cpl_id }}] = 0;
                        }
                        cplValues[{{ $cpmkCpl->cpl_id }}] += {{ $nilaiCpmk->nilai_bobot }};
                        cplCounts[{{ $cpmkCpl->cpl_id }}]++;
                    @endforeach
                @endforeach
            @endforeach

            // Calculate averages for overall chart
            var overallCplData = [];
            var overallCplLabels = [];
            var overallBarColors = [];
            const threshold = 2.5;
            var overallValue;

            @foreach($cpls as $cpl)
                if (cplCounts[{{ $cpl->id }}]) {
                    overallValue = parseFloat((cplValues[{{ $cpl->id }}] / cplCounts[{{ $cpl->id }}]).toFixed(2));
                    overallCplData.push(overallValue);
                    overallBarColors.push(overallValue < threshold ? '#F1416C' : '#50CD89');
                } else {
                    overallValue = 0;
                    overallCplData.push(overallValue);
                    overallBarColors.push('#F1416C');
                }
                overallCplLabels.push('CPL {{ $cpl->nomor }}');
            @endforeach

            // Initialize overall CPL chart
            var overallOptions = {
                series: [{
                    name: 'Nilai CPL',
                    data: overallCplData
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded',
                        distributed: true
                    },
                },
                annotations: {
                    yaxis: [{
                        y: threshold,
                        borderColor: '#F1416C',
                        label: {
                            borderColor: '#F1416C',
                            style: {
                                color: '#fff',
                                background: '#F1416C'
                            },
                            text: 'Batas Minimal (2.5)'
                        }
                    }]
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val.toFixed(2)
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: overallCplLabels,
                },
                yaxis: {
                    title: {
                        text: 'Nilai'
                    },
                    min: 0,
                    max: 4
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val.toFixed(2)
                        }
                    }
                },
                colors: overallBarColors,
                legend: {
                    show: false
                }
            };

            var overallChart = new ApexCharts(document.querySelector("#cpl_chart"), overallOptions);
            overallChart.render();

            // Calculate CPL values per semester
            var semesterData = {};
            var allSemesters = [];

            @foreach($mahasiswa->nilai as $nilai)
                if (!semesterData[{{ $nilai->semester }}]) {
                    semesterData[{{ $nilai->semester }}] = {};
                    allSemesters.push({{ $nilai->semester }});
                }

                @foreach($nilai->nilaiCpmk as $nilaiCpmk)
                    @foreach($nilaiCpmk->cpmk->cpmkCpl as $cpmkCpl)
                        if (!semesterData[{{ $nilai->semester }}][{{ $cpmkCpl->cpl_id }}]) {
                            semesterData[{{ $nilai->semester }}][{{ $cpmkCpl->cpl_id }}] = {
                                total: 0,
                                count: 0
                            };
                        }
                        semesterData[{{ $nilai->semester }}][{{ $cpmkCpl->cpl_id }}].total += {{ $nilaiCpmk->nilai_bobot }};
                        semesterData[{{ $nilai->semester }}][{{ $cpmkCpl->cpl_id }}].count++;
                    @endforeach
                @endforeach
            @endforeach

            // Sort semesters
            allSemesters.sort((a, b) => a - b);

            // Prepare data for chart
            var seriesData = [];
            var cplSemesterValues;
            @foreach($cpls as $cpl)
                cplSemesterValues = [];
                allSemesters.forEach(semester => {
                    if (semesterData[semester][{{ $cpl->id }}] && semesterData[semester][{{ $cpl->id }}].count > 0) {
                        cplSemesterValues.push(parseFloat((semesterData[semester][{{ $cpl->id }}].total / semesterData[semester][{{ $cpl->id }}].count).toFixed(2)));
                    } else {
                        cplSemesterValues.push(null);
                    }
                });
                seriesData.push({
                    name: 'CPL {{ $cpl->nomor }}',
                    data: cplSemesterValues
                });
            @endforeach

            // Initialize semester chart
            var semesterOptions = {
                series: seriesData,
                chart: {
                    height: 400,
                    type: 'line',
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val ? val.toFixed(2) : '';
                    }
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                markers: {
                    size: 5
                },
                xaxis: {
                    categories: allSemesters.map(sem => 'Semester ' + sem),
                    title: {
                        text: 'Semester'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Nilai'
                    },
                    min: 0,
                    max: 4,
                    tickAmount: 8
                },
                annotations: {
                    yaxis: [{
                        y: 2.5,
                        borderColor: '#F1416C',
                        label: {
                            borderColor: '#F1416C',
                            style: {
                                color: '#fff',
                                background: '#F1416C'
                            },
                            text: 'Batas Minimal (2.5)'
                        }
                    }]
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val ? val.toFixed(2) : 'Tidak ada data';
                        }
                    }
                },
                legend: {
                    position: 'right'
                }
            };

            var semesterChart = new ApexCharts(document.querySelector("#cpl_semester_chart"), semesterOptions);
            semesterChart.render();
        </script>
    @endpush
</x-default-layout>
