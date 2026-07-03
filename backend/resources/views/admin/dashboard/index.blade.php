@extends('admin.layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalShows }}</h3>
                <p>Total de Shows</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <a href="{{ route('admin.shows.index') }}" class="small-box-footer">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $futureShows }}</h3>
                <p>Shows Futuros</p>
            </div>
            <div class="icon">
                <i class="fas fa-music"></i>
            </div>
            <a href="{{ route('admin.shows.index') }}" class="small-box-footer">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalGallery }}</h3>
                <p>Itens na Galeria</p>
            </div>
            <div class="icon">
                <i class="fas fa-images"></i>
            </div>
            <a href="{{ route('admin.gallery.index') }}" class="small-box-footer">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalEvents }}</h3>
                <p>Eventos Rastreados</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <a href="{{ route('admin.analytics') }}" class="small-box-footer">Ver detalhes <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Visitas nos Últimos 7 Dias</h3>
            </div>
            <div class="card-body">
                <canvas id="visitsChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Shows Recentes</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Data</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentShows as $show)
                        <tr>
                            <td>{{ $show->titulo }}</td>
                            <td>{{ $show->data_evento->format('d/m/Y') }}</td>
                            <td>
                                @if($show->status == 'active')
                                    <span class="badge badge-success">Ativo</span>
                                @elseif($show->status == 'inactive')
                                    <span class="badge badge-secondary">Inativo</span>
                                @else
                                    <span class="badge badge-dark">Realizado</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Nenhum show encontrado.</td>
                        </tr>
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
    var ctx = document.getElementById('visitsChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Visitas',
                data: {!! json_encode($counts) !!},
                backgroundColor: 'rgba(60,141,188,0.2)',
                borderColor: 'rgba(60,141,188,1)',
                pointRadius: 5,
                pointBackgroundColor: 'rgba(60,141,188,1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>
@endpush
