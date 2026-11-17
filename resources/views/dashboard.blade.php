@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2 class="mb-4">Panel de Control</h2>

    <!-- Tarjetas de indicadores -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-book"></i> Libros en Stock</h5>
                    <h2 class="display-4">{{ $totalLibros }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3 shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people"></i> Lectores Registrados</h5>
                    <h2 class="display-4">{{ $totalLectores }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3 shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-journal-check"></i> Préstamos Activos</h5>
                    <h2 class="display-4">{{ $prestamosActivos }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Libros más prestados -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Top 5 Libros más prestados</h5>
        </div>
        <div class="card-body">
            @if(count($titulosGrafico) > 0)
                <canvas id="graficoPrestamos" style="max-height: 300px;"></canvas>
            @else
                <div class="alert alert-info text-center mb-0">
                    Aún no hay préstamos registrados para mostrar estadísticas.
                </div>
            @endif
        </div>
    </div>

    <!-- Tabla de últimos préstamos -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Últimos Préstamos Realizados</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th>Lector</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimosPrestamos as $prestamo)
                    <tr>
                        <td>{{ $prestamo->libro->titulo }}</td>
                        <td>{{ $prestamo->lector->nombre }}</td>
                        <td>{{ $prestamo->fecha_prestamo }}</td>
                        <td>
                            <span class="badge bg-{{ $prestamo->estado == 'Prestado' ? 'warning' : 'success' }}">
                                {{ $prestamo->estado }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script de Chart.js (Solo se ejecuta si hay datos) -->
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
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(153, 102, 255, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
        </script>
    @endif
@endsection