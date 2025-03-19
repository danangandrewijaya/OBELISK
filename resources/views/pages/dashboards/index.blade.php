@extends('layout.master')

@section('title', 'Dashboard Akademik')

@section('content')
    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Dashboard Akademik</h3>
            </div>
        </div>
    </div>

    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!-- CPMK Card -->
        <div class="col-md-6 col-lg-6 col-xl-4 mb-5 mb-xl-0">
            <div class="card card-flush h-md-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-dark me-2" id="cpmk-count">0</span>
                        <span class="text-gray-400 pt-1 fw-semibold fs-6">Total CPMK</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="d-flex flex-wrap">
                        <div class="d-flex flex-center me-5 pt-2">
                            <span class="fw-bold fs-6 text-gray-800">Capaian Pembelajaran Mata Kuliah</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center fw-semibold fs-6 mt-4">
                        <a href="{{ route('report.cpmk-cpl') }}" class="btn btn-sm btn-primary">Lihat Detail</a>
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
                        <span class="text-gray-400 pt-1 fw-semibold fs-6">Total CPL</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="d-flex flex-wrap">
                        <div class="d-flex flex-center me-5 pt-2">
                            <span class="fw-bold fs-6 text-gray-800">Capaian Pembelajaran Lulusan</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center fw-semibold fs-6 mt-4">
                        <a href="{{ route('report.cpmk-cpl') }}" class="btn btn-sm btn-primary">Lihat Detail</a>
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
                        <span class="text-gray-400 pt-1 fw-semibold fs-6">Total MKS</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="d-flex flex-wrap">
                        <div class="d-flex flex-center me-5 pt-2">
                            <span class="fw-bold fs-6 text-gray-800">Mata Kuliah</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center fw-semibold fs-6 mt-4">
                        <a href="{{ route('report.matakuliah-semester.index') }}" class="btn btn-sm btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!-- CPMK-CPL Distribution -->
        <div class="col-xl-6">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Distribusi CPMK per CPL</span>
                    </h3>
                </div>
                <div class="card-body pt-6">
                    <div id="cpmk_cpl_chart" style="height: 350px"></div>
                </div>
            </div>
        </div>

        <!-- MKS Distribution -->
        <div class="col-xl-6">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Distribusi Mata Kuliah per Semester</span>
                    </h3>
                </div>
                <div class="card-body pt-6">
                    <div id="mks_semester_chart" style="height: 350px"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch dashboard data
    fetch('/api/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            // Update counters
            document.getElementById('cpmk-count').textContent = data.cpmk_count;
            document.getElementById('cpl-count').textContent = data.cpl_count;
            document.getElementById('mks-count').textContent = data.mks_count;

            // Initialize charts
            initializeCPMKCPLChart(data.cpmk_cpl_distribution);
            initializeMKSChart(data.mks_distribution);
        });
});

function initializeCPMKCPLChart(data) {
    // Create root element
    var root = am5.Root.new("cpmk_cpl_chart");
    root.setThemes([am5themes_Animated.new(root)]);

    // Create chart
    var chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "none",
            wheelY: "none"
        })
    );

    // Add data
    chart.data = data;

    // Create axes
    var xAxis = chart.xAxes.push(
        am5xy.CategoryAxis.new(root, {
            categoryField: "cpl",
            renderer: am5xy.AxisRendererX.new(root, {}),
            tooltip: am5.Tooltip.new(root, {})
        })
    );

    var yAxis = chart.yAxes.push(
        am5xy.ValueAxis.new(root, {
            renderer: am5xy.AxisRendererY.new(root, {})
        })
    );

    // Add series
    var series = chart.series.push(
        am5xy.ColumnSeries.new(root, {
            name: "CPMK",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "count",
            categoryXField: "cpl",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        })
    );

    series.columns.template.setAll({
        cornerRadiusTL: 5,
        cornerRadiusTR: 5
    });

    // Add legend
    var legend = chart.children.push(am5.Legend.new(root, {}));
    legend.data.setAll(chart.series.values);

    // Add cursor
    chart.set("cursor", am5xy.XYCursor.new(root, {}));

    xAxis.data.setAll(data);
    series.data.setAll(data);
}

function initializeMKSChart(data) {
    // Create root element
    var root = am5.Root.new("mks_semester_chart");
    root.setThemes([am5themes_Animated.new(root)]);

    // Create chart
    var chart = root.container.children.push(
        am5percent.PieChart.new(root, {
            layout: root.verticalLayout
        })
    );

    // Create series
    var series = chart.series.push(
        am5percent.PieSeries.new(root, {
            valueField: "count",
            categoryField: "semester",
            endAngle: 270
        })
    );

    series.states.create("hidden", {
        endAngle: -90
    });

    // Add data
    series.data.setAll(data);

    // Add legend
    var legend = chart.children.push(am5.Legend.new(root, {
        centerX: am5.percent(50),
        x: am5.percent(50),
        marginTop: 15,
        marginBottom: 15
    }));

    legend.data.setAll(series.dataItems);
}
</script>
@endpush