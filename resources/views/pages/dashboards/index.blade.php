<x-default-layout>

    @section('title')
        Dashboard Akademik
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('dashboard') }}
    @endsection

    <!--begin::Content container-->
    <div class="app-container container-fluid">
        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="col-12">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative">
                                <h2>Dashboard Akademik</h2>
                                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                                <span id="semester-aktif" class="text-muted fs-7 fw-semibold">Semester </span>
                            </div>
                        </div>
                    </div>
                    <!--end::Card header-->
                </div>
                <!--end::Card-->
            </div>
        </div>
        <!--end::Row-->

        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <!-- CPMK Card -->
            <div class="col-md-6 col-lg-6 col-xl-4 mb-5 mb-xl-0">
                <div class="card card-flush h-md-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2" id="cpmk-count">0</span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">Total CPMK Aktif</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4">
                        <div class="d-flex flex-wrap">
                            <div class="d-flex flex-center me-5 pt-2">
                                <span class="fw-bold fs-6 text-gray-800">Capaian Pembelajaran Mata Kuliah</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CPL Card -->
            <div class="col-md-6 col-lg-6 col-xl-4 mb-5 mb-xl-0">
                <div class="card card-flush h-md-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2" id="cpl-count">0</span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">Total CPL Terkait</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4">
                        <div class="d-flex flex-wrap">
                            <div class="d-flex flex-center me-5 pt-2">
                                <span class="fw-bold fs-6 text-gray-800">Capaian Pembelajaran Lulusan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MKS Card -->
            <div class="col-md-6 col-lg-6 col-xl-4 mb-5 mb-xl-0">
                <div class="card card-flush h-md-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2" id="mks-count">0</span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">Mata Kuliah Aktif</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4">
                        <div class="d-flex flex-wrap">
                            <div class="d-flex flex-center me-5 pt-2">
                                <span class="fw-bold fs-6 text-gray-800">Total Mata Kuliah Semester</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Row-->

        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <!-- RPS Status -->
            <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Status RPS</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        <div id="rps_status_chart" class="min-h-auto"></div>
                    </div>
                </div>
            </div>

            <!-- CPMK per CPL Distribution -->
            <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Distribusi CPMK per CPL</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        <div id="cpmk_cpl_chart" class="min-h-auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Row-->

        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="col-12">
                <!-- Daftar Mata Kuliah -->
                <div class="card">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Daftar Mata Kuliah</span>
                        </h3>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="mks-table">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th>Kode</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>CPMK</th>
                                    <th>CPL</th>
                                    <th>Status RPS</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Content container-->

    @push('scripts')
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('assets/plugins/custom/apexcharts/apexcharts.bundle.js') }}"></script>
    <!--end::Vendors Javascript-->
    
    <script>
    window.addEventListener('load', function () {
        // Retry mechanism for element detection
        const getElement = (id, retries = 5) => {
            return new Promise((resolve, reject) => {
                const check = (count) => {
                    const element = document.getElementById(id);
                    if (element) {
                        resolve(element);
                    } else if (count > 0) {
                        setTimeout(() => check(count - 1), 100);
                    } else {
                        reject(new Error(`Element with id '${id}' not found after ${retries} retries`));
                    }
                };
                check(retries);
            });
        };

        // Initialize dashboard
        Promise.all([
            getElement('semester-aktif'),
            getElement('cpmk-count'),
            getElement('cpl-count'),
            getElement('mks-count'),
            getElement('rps_status_chart'),
            getElement('cpmk_cpl_chart')
        ]).then(([semester, cpmk, cpl, mks, rpsChart, cpmkCplChart]) => {
            const elements = { semester, cpmk, cpl, mks, rpsChart, cpmkCplChart };
            
            // Load dashboard data
            fetch('/dashboard/stats')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update semester aktif
                    elements.semester.textContent = 'Semester ' + data.tahun_aktif + '/' + data.semester_aktif;
                    
                    // Update count cards
                    elements.cpmk.textContent = data.cpmk_count;
                    elements.cpl.textContent = data.cpl_count;
                    elements.mks.textContent = data.mks_count;

                    // Initialize RPS Status Chart
                    if (data.rps_status && elements.rpsChart) {
                        const rpsOptions = {
                            series: [data.rps_status.draft_count, data.rps_status.published_count],
                            chart: {
                                height: 300,
                                type: 'donut',
                            },
                            labels: ['Draft', 'Published'],
                            colors: ['#FFA800', '#50CD89'],
                            legend: {
                                position: 'bottom'
                            },
                            dataLabels: {
                                enabled: true,
                                formatter: function (val, opts) {
                                    return opts.w.config.series[opts.seriesIndex];
                                },
                            },
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: 300
                                    }
                                }
                            }]
                        };

                        const rpsChart = new ApexCharts(elements.rpsChart, rpsOptions);
                        rpsChart.render();
                    }

                    // Initialize CPMK-CPL Distribution Chart
                    if (data.cpmk_cpl_distribution && elements.cpmkCplChart) {
                        const cpmkCplOptions = {
                            series: [{
                                name: 'CPMK',
                                data: data.cpmk_cpl_distribution.map(item => item.count)
                            }],
                            chart: {
                                height: 300,
                                type: 'bar',
                                toolbar: {
                                    show: false
                                }
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '55%',
                                    endingShape: 'rounded'
                                },
                            },
                            dataLabels: {
                                enabled: true
                            },
                            xaxis: {
                                categories: data.cpmk_cpl_distribution.map(item => 'CPL ' + item.cpl),
                            },
                            colors: ['#009EF7'],
                            fill: {
                                opacity: 1
                            },
                            tooltip: {
                                y: {
                                    formatter: function (val) {
                                        return val + " CPMK"
                                    }
                                }
                            }
                        };

                        const cpmkCplChart = new ApexCharts(elements.cpmkCplChart, cpmkCplOptions);
                        cpmkCplChart.render();
                    }

                    // Initialize MKS Table
                    if (data.mks_list) {
                        initMksTable(data.mks_list);
                    }
                })
                .catch(error => {
                    console.error('Error loading dashboard data:', error);
                });
        }).catch(error => {
            console.error('Error initializing dashboard:', error);
        });
    });

    function initMksTable(data) {
        if (!data || !data.length) {
            console.warn('No MKS list data available');
            return;
        }

        const tbody = document.querySelector('#mks-table tbody');
        if (!tbody) {
            console.error('MKS table body not found');
            return;
        }

        tbody.innerHTML = '';

        data.forEach(mks => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${mks.kode}</td>
                <td>${mks.nama}</td>
                <td>${mks.sks}</td>
                <td>${mks.cpmks_count}</td>
                <td>${mks.cpls_count}</td>
                <td><span class="badge badge-light-${mks.rps_status === 'published' ? 'success' : 'warning'}">${mks.rps_status || 'draft'}</span></td>
            `;
            tbody.appendChild(tr);
        });
    }
    </script>
@endpush

</x-default-layout>