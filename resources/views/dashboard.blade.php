
@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')

<style>
    .dashboard {
        color: #493729;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .dashboard-header h2 {
        font-weight: 800;
        color: #145A45;
        margin: 0;
    }

    .dashboard-header p {
        color: #796653;
        margin: 5px 0 0;
    }

    .dashboard-label {
        background: #D4A64A;
        color: #493729;
        border-radius: 20px;
        padding: 9px 16px;
        font-weight: 700;
    }

    .stat-card {
        border: none;
        border-radius: 16px;
        padding: 12px;
        color: white;
        min-height: 165px;
        transition: transform .25s, box-shadow .25s;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(73, 55, 41, .15);
    }

    .stat-green {
        background: linear-gradient(135deg, #0D4032, #1B7658);
    }

    .stat-gold {
        background: linear-gradient(135deg, #A87927, #D4A64A);
        color: #493729;
    }

    .stat-brown {
        background: linear-gradient(135deg, #493729, #75553B);
    }

    .stat-icon {
        font-size: 28px;
        background: rgba(255,255,255,.18);
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
    }

    .stat-label {
        font-size: 15px;
        font-weight: 600;
        margin-top: 18px;
        opacity: .95;
    }

    .stat-number {
        font-size: 38px;
        font-weight: 800;
        margin: 0;
    }

    .panel-card {
        border: 1px solid #E4D9C8;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(73, 55, 41, .06);
        background: white;
    }

    .panel-heading {
        padding: 18px 22px;
        background: #145A45;
        color: white;
        border-bottom: 3px solid #D4A64A;
    }

    .panel-heading h5 {
        margin: 0;
        font-weight: 700;
    }

    .panel-body {
        padding: 22px;
    }

    .dashboard .table thead th {
        color: #145A45;
        background: #F5F0E6;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .5px;
        border-bottom: 2px solid #D4A64A;
        padding: 15px;
    }

    .dashboard .table tbody td {
        padding: 16px 15px;
        vertical-align: middle;
        color: #493729;
    }

    .dashboard .table tbody tr:hover {
        background: #FAF7F0;
    }

    .empty-state {
        padding: 35px 15px;
        text-align: center;
        color: #796653;
    }

    .empty-state i {
        font-size: 38px;
        color: #D4A64A;
        display: block;
        margin-bottom: 12px;
    }

    .status-badge {
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    @media(max-width: 768px) {
        .dashboard-header {
            align-items: flex-start;
        }

        .stat-number {
            font-size: 32px;
        }

        .panel-body {
            padding: 12px;
        }
    }
</style>

<div class="dashboard">

    <!-- Encabezado -->
    <div class="dashboard-header">
        <div>
            <h2>
                <i class="bi bi-speedometer2 me-2"></i>
                Panel de Administración
            </h2>
            <p>Resumen general del sistema de biblioteca.</p>
        </div>

        <span class="dashboard-label">
            <i class="bi bi-book-half me-1"></i> Biblioteca DB
        </span>
    </div>

    <!-- Estadísticas -->
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card stat-card stat-green">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <div class="stat-label">Libros en Stock</div>
                    <h3 class="stat-number">{{ $totalLibros }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card stat-brown">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-label">Lectores Registrados</div>
                    <h3 class="stat-number">{{ $totalLectores }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card stat-gold">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div class="stat-label">Préstamos Activos</div>
                    <h3 class="stat-number">{{ $prestamosActivos }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Gráfico -->
    <div class="panel-card mb-4">
        <div class="panel-heading">
            <h5>
                <i class="bi bi-bar-chart-line me-2"></i>
                Libros más prestados
            </h5>
        </div>

        <div class="panel-body">

            @if(count($titulosGrafico) > 0)
                <div class="chart-container">
                    <canvas id="graficoPrestamos"></canvas>
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-graph-up"></i>
                    <h6>No hay estadísticas disponibles</h6>
                    <p class="mb-0">
                        Los datos aparecerán cuando se registren préstamos.
                    </p>
                </div>
            @endif

        </div>
    </div>

    <!-- Últimos préstamos -->
    <div class="panel-card mb-4">

        <div class="panel-heading">
            <h5>
                <i class="bi bi-clock-history me-2"></i>
                Últimos préstamos realizados
            </h5>
        </div>

        <div class="panel-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Libro</th>
                            <th>Lector</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($ultimosPrestamos as $prestamo)
                            <tr>
                                <td>
                                    <i class="bi bi-book text-success me-2"></i>
                                    {{ $prestamo->libro->titulo }}
                                </td>

                                <td>
                                    <i class="bi bi-person text-secondary me-2"></i>
                                    {{ $prestamo->lector->nombre }}
                                </td>

                                <td>
                                    {{ $prestamo->fecha_prestamo }}
                                </td>

                                <td>
                                    <span class="badge status-badge bg-{{ $prestamo->estado == 'Prestado' ? 'warning text-dark' : 'success' }}">
                                        {{ $prestamo->estado }}
                                    </span>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="bi bi-journal-x"></i>
                                        <h6>No hay préstamos registrados</h6>
                                        <p class="mb-0">
                                            Los préstamos recientes aparecerán aquí.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

<!-- Chart.js -->
@if(count($titulosGrafico) > 0)

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('graficoPrestamos');

    new Chart(ctx, {
        type: 'bar',

        data: {
            labels: {!! json_encode($titulosGrafico) !!},

            datasets: [{
                label: 'Cantidad de préstamos',
                data: {!! json_encode($cantidadesGrafico) !!},

                backgroundColor: [
                    '#145A45',
                    '#D4A64A',
                    '#493729',
                    '#26785D',
                    '#B98B3D'
                ],

                borderRadius: 8,
                borderSkipped: false
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: '#E9E1D4'
                    }
                },

                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>

@endif

@endsection
