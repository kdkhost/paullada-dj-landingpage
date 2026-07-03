@extends('admin.layouts.admin')

@section('page_title', 'Analytics')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalPageViews }}</h3>
                <p>Total de Visualizações</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-bar"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Visitas por Dia (Últimos 30 Dias)</h3>
            </div>
            <div class="card-body">
                <canvas id="visitsLineChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Visualizações por Página</h3>
            </div>
            <div class="card-body">
                <canvas id="pagePieChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tipos de Evento</h3>
            </div>
            <div class="card-body">
                <canvas id="eventBarChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resumo por Página</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Página</th>
                            <th>Visualizações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pageViews as $pv)
                        <tr>
                            <td>{{ $pv->pagina ?: '/' }}</td>
                            <td><span class="badge badge-primary">{{ $pv->total }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center">Nenhum dado disponível.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
    var dates = {!! json_encode($dates) !!};
    var counts = {!! json_encode($counts) !!};

    new Chart(document.getElementById('visitsLineChart'), {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Visitas',
                data: counts,
                backgroundColor: 'rgba(60,141,188,0.2)',
                borderColor: 'rgba(60,141,188,1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    new Chart(document.getElementById('pagePieChart'), {
        type: 'pie',
        data: {
            labels: {!! $pageLabels !!},
            datasets: [{
                data: {!! $pageCounts !!},
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de']
            }]
        },
        options: {
            responsive: true,
            legend: { position: 'bottom' }
        }
    });

    new Chart(document.getElementById('eventBarChart'), {
        type: 'bar',
        data: {
            labels: {!! $typeLabels !!},
            datasets: [{
                label: 'Eventos',
                data: {!! $typeCounts !!},
                backgroundColor: ['#00a65a', '#f56954', '#f39c12', '#00c0ef', '#3c8dbc']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endpush
