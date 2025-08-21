<x-default-layout>
    @section('title', 'Perbandingan CPL Antar Siklus')

    @section('breadcrumbs')
        {{ Breadcrumbs::render('siklus.compare-cpl') }}
    @endsection

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="card-title">Perbandingan CPL Antar Siklus</div>
        </div>
        <div class="card-body">
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

            <div class="alert alert-info mb-5">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fs-2"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">Informasi</h4>
                        <div>
                            Pilih maksimal 4 siklus untuk membandingkan nilai CPL antar siklus.
                            Grafik akan menampilkan perbandingan nilai CPL untuk setiap siklus yang dipilih.
                        </div>
                    </div>
                </div>
            </div>

            <form id="compareForm" class="form">
                <div class="row mb-5">
                    <div class="col-md-12">
                        <label class="required form-label">Pilih Siklus (Maksimal 4)</label>
                        <select class="form-select" id="siklus_ids" name="siklus_ids[]" multiple="multiple"
                            data-control="select2"
                            data-placeholder="Pilih siklus yang akan dibandingkan"
                            data-allow-clear="true">
                            @foreach($siklusList as $siklus)
                                <option value="{{ $siklus->id }}">{{ $siklus->tahun_mulai }} - {{ $siklus->nama }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Pilih minimal 1 dan maksimal 4 siklus untuk dibandingkan</div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-chart-bar"></i>
                            Tampilkan Grafik
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <canvas id="cplChart" style="height: 400px; width: 100%;"></canvas>
                                <div id="cplChartEmpty" class="p-10 text-center" style="display:none;">
                                    <h5 class="text-muted">Tidak ada data untuk ditampilkan</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
let cplChart;

$(document).ready(function() {
    // Initialize select2 with maximum selection
    $("#siklus_ids").select2({
        maximumSelectionLength: 4,
        placeholder: "Pilih maksimal 4 siklus",
        allowClear: true
    });

    // auto-select first 1-2 siklus if present to show initial chart
    const opts = $('#siklus_ids option');
    if (opts.length > 0) {
        const toSelect = [];
        toSelect.push(opts.eq(0).val());
        if (opts.length > 1) toSelect.push(opts.eq(1).val());
        $('#siklus_ids').val(toSelect).trigger('change');
        fetchAndRenderChart(toSelect);
    }

    // Handle form submission
    $('#compareForm').on('submit', function(e) {
        e.preventDefault();
        const siklusIds = $('#siklus_ids').val();

        if (siklusIds.length === 0) {
            Swal.fire({
                text: "Silakan pilih minimal 1 siklus",
                icon: "warning",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            return;
        }

        if (siklusIds.length > 4) {
            Swal.fire({
                text: "Maksimal 4 siklus yang dapat dibandingkan",
                icon: "warning",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            return;
        }

        fetchAndRenderChart(siklusIds);
    });
});

function fetchAndRenderChart(siklusIds) {
    if (!siklusIds || !siklusIds.length) return;

    $.ajax({
        url: '{{ route("siklus.compare-cpl.data") }}',
        type: 'GET',
        data: { siklus_ids: siklusIds },
        dataType: 'json',
        success: function(response) {
            if (!response || !response.labels || !response.datasets) {
                console.error('Invalid response for chart data', response);
                Swal.fire({ text: 'Data grafik tidak valid', icon: 'error' });
                return;
            }
            updateChart(response);
        },
        error: function(xhr, status, err) {
            console.error('AJAX error', status, err, xhr.responseText);
            Swal.fire({ text: 'Terjadi kesalahan saat mengambil data', icon: 'error' });
        }
    });
}

function updateChart(data) {
    if (cplChart) {
        cplChart.destroy();
    }

    if (!data || !data.labels || data.labels.length === 0) {
        $('#cplChart').hide();
        $('#cplChartEmpty').show();
        return;
    }

    $('#cplChartEmpty').hide();
    $('#cplChart').show();

    const ctx = document.getElementById('cplChart').getContext('2d');
    cplChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: data.datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Nilai CPL (%)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Kode CPL'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Perbandingan Nilai CPL Antar Siklus'
                }
            }
        }
    });
}
</script>
@endpush
</x-default-layout>
