@extends('layouts.app')

@section('title', 'Préstamos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ auth()->user()->role == 'admin' ? 'Historial de Préstamos' : 'Mis Préstamos' }}</h2>
        
        @if(auth()->user()->role == 'admin')
            <a href="{{ route('prestamos.create') }}" class="btn btn-success">
                <i class="bi bi-journal-arrow-up"></i> Nuevo Préstamo
            </a>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Libro</th>
                        @if(auth()->user()->role == 'admin') <th>Lector</th> @endif
                        <th>F. Préstamo</th>
                        <th>F. Límite</th>
                        <th>F. Devolución</th>
                        <th>Multa</th>
                        <th>Estado</th>
                        @if(auth()->user()->role == 'admin')
                            <th class="text-center">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestamos as $prestamo)
                        <tr class="{{ ($prestamo->estado == 'Prestado' && date('Y-m-d') > $prestamo->fecha_limite) ? 'table-danger' : '' }}">
                            
                            <td>{{ $prestamo->id }}</td>
                            <td>{{ $prestamo->libro->titulo }}</td>
                            
                            @if(auth()->user()->role == 'admin') 
                                <td>{{ $prestamo->lector->nombre }} {{ $prestamo->lector->apellido }}</td> 
                            @endif
                            
                            <td>{{ $prestamo->fecha_prestamo }}</td>
                            <td>{{ $prestamo->fecha_limite }}</td>
                            <td>{{ $prestamo->fecha_devolucion ?? 'Pendiente' }}</td>
                            
                            <!-- COLUMNA MULTA -->
                            <td>
                                @if($prestamo->multa > 0)
                                    <span class="text-danger fw-bold">${{ number_format($prestamo->multa, 2) }}</span>
                                @else
                                    $0.00
                                @endif
                            </td>

                            <!-- COLUMNA ESTADO -->
                            <td>
                                @if($prestamo->estado == 'Solicitado')
                                    <span class="badge bg-secondary">Solicitado</span>
                                @elseif($prestamo->estado == 'Aprobado')
                                    <!-- ✅ NUEVO ESTADO: Aprobado -->
                                    <span class="badge bg-info text-dark">Listo para recoger</span>
                                    @if($prestamo->fecha_recoger)
                                        <br><small class="text-muted">Hasta: {{ $prestamo->fecha_recoger }}</small>
                                    @endif
                                @elseif($prestamo->estado == 'Prestado')
                                    @php
                                        $hoy = strtotime(date('Y-m-d')); 
                                        $limite = strtotime($prestamo->fecha_limite);
                                        $diasRestantes = round(($limite - $hoy) / 86400);
                                    @endphp
                                    @if($diasRestantes < 0)
                                        <span class="badge bg-danger">Vencido</span>
                                    @elseif($diasRestantes >= 0 && $diasRestantes <= 3)
                                        <span class="badge bg-warning text-dark">Por vencer ({{ $diasRestantes }} d)</span>
                                    @else
                                        <span class="badge bg-primary">Prestado</span>
                                    @endif
                                @elseif($prestamo->estado == 'Rechazado')
                                    <span class="badge bg-danger">Rechazado</span>
                                @else
                                    <span class="badge bg-success">Devuelto</span>
                                @endif
                            </td>

                            <!-- COLUMNA ACCIONES -->
                            @if(auth()->user()->role == 'admin')
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Opciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow">
                                            
                                            @if($prestamo->estado == 'Solicitado')
                                                <li>
                                                    <form action="{{ route('prestamos.aprobar', $prestamo) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="bi bi-check-circle"></i> Aprobar Solicitud
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('prestamos.rechazar', $prestamo) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-x-circle"></i> Rechazar
                                                        </button>
                                                    </form>
                                                </li>

                                            @elseif($prestamo->estado == 'Aprobado')
                                                <!-- ✅ NUEVO BOTÓN: Confirmar entrega física -->
                                                <li>
                                                    <form action="{{ route('prestamos.entregar', $prestamo) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-primary" onclick="return confirm('¿El lector ya recogió el libro? El préstamo comenzará a contar hoy.')">
                                                            <i class="bi bi-box-arrow-up"></i> Confirmar Entrega Física
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('prestamos.rechazar', $prestamo) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Cancelar por no ser recogido?')">
                                                            <i class="bi bi-x-circle"></i> Cancelar (No recogido)
                                                        </button>
                                                    </form>
                                                </li>

                                            @elseif($prestamo->estado == 'Prestado')
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('prestamos.edit', $prestamo) }}">
                                                        <i class="bi bi-box-arrow-in-down text-primary"></i> Registrar Devolución
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('prestamos.renovar', $prestamo) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="dropdown-item text-info">
                                                            <i class="bi bi-arrow-clockwise"></i> Renovar (7 días)
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif

                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('prestamos.destroy', $prestamo) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Eliminar este registro del historial?')">
                                                        <i class="bi bi-trash"></i> Eliminar Registro
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role == 'admin' ? '9' : '7' }}" class="text-center">
                                No hay préstamos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $prestamos->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection