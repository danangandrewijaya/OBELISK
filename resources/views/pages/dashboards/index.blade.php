<x-default-layout>

    @section('title')
        Dashboard
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
                                <h2>Dashboard</h2>
                                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                                <span id="semester-aktif" class="text-muted fs-7 fw-semibold">Semester </span>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center">
                                <select id="semester-filter" class="form-select form-select-sm form-select-solid" data-control="select2" data-placeholder="Pilih Semester">
                                    <option value="all" selected>Semua Semester</option>
                                </select>
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
            <!-- Curriculum Distribution -->
            <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Distribusi Makul Semester</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        <div id="curriculum_distribution_chart" class="min-h-auto"></div>
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

        // Charts references
        let curriculumChartInstance = null;
        let cpmkCplChartInstance = null;

        // Initialize dashboard
        Promise.all([
            getElement('semester-aktif'),
            getElement('semester-filter'),
            getElement('cpmk-count'),
            getElement('cpl-count'),
            getElement('mks-count'),
            getElement('curriculum_distribution_chart'),
            getElement('cpmk_cpl_chart')
        ]).then(([semesterLabel, semesterFilter, cpmk, cpl, mks, curriculumChart, cpmkCplChart]) => {
            const elements = {
                semesterLabel,
                semesterFilter,
                cpmk,
                cpl,
                mks,
                curriculumChart,
                cpmkCplChart
            };

            // Load initial dashboard data
            loadDashboardData(elements);

            // Initialize the semester filter with select2
            $(elements.semesterFilter).select2({
                minimumResultsForSearch: 5
            });

            // Set up change event for the semester filter
            $(elements.semesterFilter).on('change', function() {
                loadDashboardData(elements, this.value);
            });
        }).catch(error => {
            console.error('Error initializing dashboard:', error);
        });

        // Function to load dashboard data based on selected semester
        function loadDashboardData(elements, semester = 'all') {
            // Build URL with semester parameter
            const url = '/dashboard/stats' + (semester ? `?semester=${semester}` : '');

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Debugging output
                    console.log('Dashboard data received:', data);

                    // Update semester dropdown options if provided
                    if (data.semester_options && data.semester_options.length > 0) {
                        // Keep the current All option
                        let currentOptions = $(elements.semesterFilter).find('option[value="all"]');
                        $(elements.semesterFilter).empty().append(currentOptions);

                        // Add new semester options
                        data.semester_options.forEach(option => {
                            $(elements.semesterFilter).append(new Option(option.text, option.id, false, false));
                        });

                        // Trigger select2 update
                        $(elements.semesterFilter).trigger('change.select2');
                    }

                    // Update semester label
                    if (semester === 'all') {
                        elements.semesterLabel.textContent = 'Semua Semester (Terbaru: ' + data.tahun_aktif + '/' + data.semester_aktif + ')';
                    } else {
                        const [tahun, semesterNum] = semester.split('-');
                        elements.semesterLabel.textContent = 'Semester ' + tahun + '/' + semesterNum;
                    }

                    // Update count cards
                    elements.cpmk.textContent = data.cpmk_count;
                    elements.cpl.textContent = data.cpl_count;
                    elements.mks.textContent = data.mks_count;

                    // Initialize or update Curriculum Distribution Chart
                    if (data.curriculum_distribution && elements.curriculumChart) {
                        console.log('Curriculum distribution data:', data.curriculum_distribution);
                        try {
                            updateCurriculumChart(elements.curriculumChart, data.curriculum_distribution);
                        } catch (error) {
                            console.error('Error updating curriculum chart:', error);
                        }
                    } else {
                        console.warn('Missing curriculum_distribution data or chart element');
                    }

                    // Initialize or update CPMK-CPL Distribution Chart
                    if (data.cpmk_cpl_distribution && elements.cpmkCplChart) {
                        console.log('CPMK-CPL distribution data:', data.cpmk_cpl_distribution);
                        try {
                            updateCpmkCplChart(elements.cpmkCplChart, data.cpmk_cpl_distribution);
                        } catch (error) {
                            console.error('Error updating CPMK-CPL chart:', error);
                        }
                    } else {
                        console.warn('Missing cpmk_cpl_distribution data or chart element');
                    }

                    // Initialize MKS Table
                    if (data.mks_list) {
                        initMksTable(data.mks_list);
                    }
                })
                .catch(error => {
                    console.error('Error loading dashboard data:', error);
                });
        }

        // Function to update or create Curriculum Distribution Chart
        function updateCurriculumChart(chartElement, curriculumData) {
            // Prepare data for the chart
            const categories = curriculumData.map(item => item.semester);
            const seriesData = curriculumData.map(item => parseInt(item.count));

            const curriculumOptions = {
                series: [{
                    name: 'Mata Kuliah',
                    data: seriesData
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded',
                        distributed: true
                    },
                },
                dataLabels: {
                    enabled: true
                },
                xaxis: {
                    categories: categories,
                    title: {
                        text: 'Semester'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Mata Kuliah'
                    }
                },
                colors: ['#3699FF', '#F64E60', '#8950FC', '#50CD89', '#FFA800', '#181C32', '#009EF7'],
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " Mata Kuliah"
                        }
                    }
                }
            };

            if (curriculumChartInstance) {
                // Update existing chart
                curriculumChartInstance.updateOptions({
                    series: [{
                        name: 'Mata Kuliah',
                        data: seriesData
                    }],
                    xaxis: {
                        categories: categories
                    }
                });
            } else {
                // Create new chart
                curriculumChartInstance = new ApexCharts(chartElement, curriculumOptions);
                curriculumChartInstance.render();
            }
        }

        // Function to update or create CPMK-CPL Chart
        function updateCpmkCplChart(chartElement, distribution) {
            const cpmkCplOptions = {
                series: [{
                    name: 'CPMK',
                    data: distribution.map(item => item.count)
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
                    categories: distribution.map(item => 'CPL ' + item.cpl),
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

            if (cpmkCplChartInstance) {
                // Update existing chart
                cpmkCplChartInstance.updateOptions({
                    series: [{
                        name: 'CPMK',
                        data: distribution.map(item => item.count)
                    }],
                    xaxis: {
                        categories: distribution.map(item => 'CPL ' + item.cpl)
                    }
                });
            } else {
                // Create new chart
                cpmkCplChartInstance = new ApexCharts(chartElement, cpmkCplOptions);
                cpmkCplChartInstance.render();
            }
        }
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
            `;
            tbody.appendChild(tr);
        });
    }
    </script>
@endpush

</x-default-layout>
