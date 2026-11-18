@extends('layouts.app')

@section('title', 'Préstamos')

@section('content')

<style>
    .loans-page { color: #493729; }
    .loans-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 28px; }
    .loans-header h2 { color: #0D4032; font-weight: 800; margin: 0; }
    .loans-header p { color: #766656; margin: 5px 0 0; }
    
    .btn-new-loan { background: #145A45; color: #FFFFFF; border: 1px solid #D4A64A; padding: 11px 20px; border-radius: 10px; font-weight: 600; transition: .2s; }
    .btn-new-loan:hover { background: #0D4032; color: #FFFFFF; transform: translateY(-2px); }

    /* Estilo para el botón de reporte para que combine con el diseño */
    .btn-pdf-report { background: #FFFFFF; color: #a33d2d; border: 1px solid #a33d2d; padding: 11px 20px; border-radius: 10px; font-weight: 600; transition: .2s; text-decoration: none; display: inline-block;}
    .btn-pdf-report:hover { background: #a33d2d; color: #FFFFFF; transform: translateY(-2px); }

    .loans-card { background: #FFFFFF; border: 1px solid #e5dccd; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(73,55,41,.08); }
    .loans-card-header { background: #0D4032; color: #FFFFFF; padding: 19px 22px; border-bottom: 3px solid #D4A64A; }
    .loans-card-header h5 { margin: 0; font-weight: 700; }
    .loans-card-body { padding: 22px; background: #FFFFFF; }
    .loans-table { margin-bottom: 0; min-width: 950px; }
    .loans-table thead th { background: #F5F0E6; color: #493729; padding: 16px 12px; border-bottom: 2px solid #D4A64A; font-size: 12px; text-transform: uppercase; letter-spacing: .4px; white-space: nowrap; }
    .loans-table tbody td { padding: 16px 12px; vertical-align: middle; color: #493729; border-color: #e9e0d3; font-size: 14px; }
    .loans-table tbody tr:hover { background: #faf7f0; }

    .loan-id { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 9px; background: #e2eee8; color: #145A45; font-weight: 700; }
    .loan-book { font-weight: 700; color: #0D4032; }
    .loan-reader { color: #655344; font-weight: 500; }
    .loan-date { white-space: nowrap; color: #655344; }
    .loan-fine { color: #a33d2d; font-weight: 800; }
    .loan-no-fine { color: #766656; font-weight: 600; }

    .loan-status { display: inline-block; padding: 7px 11px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .status-requested { background: #eee9df; color: #655344; }
    .status-approved { background: #dceee6; color: #145A45; }
    .status-overdue { background: #f7e5df; color: #963f2d; }
    .status-warning { background: #f8edcf; color: #76551d; }
    .status-loaned { background: #e2eee8; color: #145A45; }
    .status-rejected { background: #f7e5df; color: #963f2d; }
    .status-returned { background: #e2eee8; color: #145A45; }

    .loan-pickup { display: block; margin-top: 5px; font-size: 11px; color: #766656; }
    .loan-actions { border-radius: 9px; padding: 7px 12px; font-weight: 600; color: #145A45; border-color: #145A45; }
    .loan-actions:hover { background: #145A45; color: #FFFFFF; }

    .loans-dropdown { border: 1px solid #e5dccd; border-radius: 12px; padding: 7px; min-width: 225px; box-shadow: 0 5px 15px rgba(73,55,41,.12); }
    .loans-dropdown .dropdown-item { padding: 10px 12px; border-radius: 7px; font-size: 13px; color: #493729; }
    .loans-dropdown .dropdown-item:hover { background: #F5F0E6; }
    .loans-dropdown .dropdown-divider { border-color: #e5dccd; }

    .empty-loans { text-align: center; padding: 45px 15px; color: #766656; }
    .empty-loans i { font-size: 42px; display: block; margin-bottom: 12px; color: #145A45; }
    .empty-loans h5 { color: #0D4032; font-weight: 700; }
    .loans-pagination { margin-top: 22px; }
    .loans-page .table-danger td { background-color: #f9eae5 !important; }

    @media(max-width: 768px) {
        .loans-card-body { padding: 12px; }
        .loans-header { align-items: flex-start; }
    }
</style>

<div class="loans-page">

    <div class="loans-header">
        <div>
            <h2>
                <i class="bi bi-journal-check me-2"></i>
                {{ auth()->user()->role == 'admin' ? 'Historial de Préstamos' : 'Mis Préstamos' }}
            </h2>
            <p>
                {{ auth()->user()->role == 'admin'
                    ? 'Administra las solicitudes, entregas y devoluciones.'
                    : 'Consulta el estado de tus préstamos y fechas de devolución.' }}
            </p>
        </div>

        <!-- ✅ CORRECCIÓN: Botones globales en el encabezado -->
        @if(auth()->user()->role == 'admin')
            <div class="d-flex gap-2">
                <a href="{{ route('prestamos.reporte') }}" class="btn-pdf-report">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Reporte PDF
                </a>
                <a href="{{ route('prestamos.create') }}" class="btn btn-new-loan">
                    <i class="bi bi-plus-circle me-2"></i> Nuevo Préstamo
                </a>
            </div>
        @endif
    </div>

    <div class="loans-card">
        <div class="loans-card-header">
            <h5><i class="bi bi-clock-history me-2"></i> Registro de préstamos</h5>
        </div>

        <div class="loans-card-body">
            <div class="table-responsive">
                <table class="table loans-table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Libro</th>
                            @if(auth()->user()->role == 'admin')
                                <th>Lector</th>
                            @endif
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
                                <td><span class="loan-id">{{ $prestamo->id }}</span></td>
                                <td>
                                    <span class="loan-book">
                                        <i class="bi bi-book me-1"></i> {{ $prestamo->libro->titulo }}
                                    </span>
                                </td>
                                @if(auth()->user()->role == 'admin')
                                    <td>
                                        <span class="loan-reader">
                                            <i class="bi bi-person-circle me-1"></i>
                                            {{ $prestamo->lector->nombre }} {{ $prestamo->lector->apellido }}
                                        </span>
                                    </td>
                                @endif
                                <td class="loan-date">{{ $prestamo->fecha_prestamo }}</td>
                                <td class="loan-date">{{ $prestamo->fecha_limite }}</td>
                                <td class="loan-date">{{ $prestamo->fecha_devolucion ?? 'Pendiente' }}</td>
                                <td>
                                    @if($prestamo->multa > 0)
                                        <span class="loan-fine">${{ number_format($prestamo->multa, 2) }}</span>
                                    @else
                                        <span class="loan-no-fine">$0.00</span>
                                    @endif
                                </td>
                                <td>
                                    @if($prestamo->estado == 'Solicitado')
                                        <span class="loan-status status-requested">
                                            <i class="bi bi-hourglass-split me-1"></i> Solicitado
                                        </span>
                                    @elseif($prestamo->estado == 'Aprobado')
                                        <span class="loan-status status-approved">
                                            <i class="bi bi-check-circle me-1"></i> Listo para recoger
                                        </span>
                                        @if($prestamo->fecha_recoger)
                                            <span class="loan-pickup">Hasta: {{ $prestamo->fecha_recoger }}</span>
                                        @endif
                                    @elseif($prestamo->estado == 'Prestado')
                                        @php
                                            $hoy = strtotime(date('Y-m-d'));
                                            $limite = strtotime($prestamo->fecha_limite);
                                            $diasRestantes = round(($limite - $hoy) / 86400);
                                        @endphp
                                        @if($diasRestantes < 0)
                                            <span class="loan-status status-overdue">
                                                <i class="bi bi-exclamation-circle me-1"></i> Vencido
                                            </span>
                                        @elseif($diasRestantes >= 0 && $diasRestantes <= 3)
                                            <span class="loan-status status-warning">
                                                <i class="bi bi-clock me-1"></i> Por vencer ({{ $diasRestantes }} d)
                                            </span>
                                        @else
                                            <span class="loan-status status-loaned">
                                                <i class="bi bi-bookmark-check me-1"></i> Prestado
                                            </span>
                                        @endif
                                    @elseif($prestamo->estado == 'Rechazado')
                                        <span class="loan-status status-rejected">
                                            <i class="bi bi-x-circle me-1"></i> Rechazado
                                        </span>
                                    @else
                                        <span class="loan-status status-returned">
                                            <i class="bi bi-check-circle me-1"></i> Devuelto
                                        </span>
                                    @endif
                                </td>

                                @if(auth()->user()->role == 'admin')
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-success loan-actions dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical me-1"></i> Opciones
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end loans-dropdown">
                                                
                                                @if($prestamo->estado == 'Solicitado')
                                                    <li>
                                                        <form action="{{ route('prestamos.aprobar', $prestamo) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="bi bi-check-circle me-2"></i> Aprobar Solicitud
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('prestamos.rechazar', $prestamo) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="bi bi-x-circle me-2"></i> Rechazar
                                                            </button>
                                                        </form>
                                                    </li>

                                                @elseif($prestamo->estado == 'Aprobado')
                                                    <li>
                                                        <form action="{{ route('prestamos.entregar', $prestamo) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success" onclick="return confirm('¿El lector ya recogió el libro?')">
                                                                <i class="bi bi-box-arrow-up me-2"></i> Confirmar Entrega Física
                                                            </button>
                                                        </form>
                                                    </li>
                                                    
                                                    <!-- ✅ CORRECCIÓN: El comprobante también se puede imprimir aquí -->
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('prestamos.comprobante', $prestamo) }}">
                                                            <i class="bi bi-printer text-secondary me-2"></i> Imprimir Comprobante
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <form action="{{ route('prestamos.rechazar', $prestamo) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Cancelar por no ser recogido?')">
                                                                <i class="bi bi-x-circle me-2"></i> Cancelar (No recogido)
                                                            </button>
                                                        </form>
                                                    </li>

                                                @elseif($prestamo->estado == 'Prestado')
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('prestamos.edit', $prestamo) }}">
                                                            <i class="bi bi-box-arrow-in-down me-2" style="color:#145A45"></i> Registrar Devolución
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('prestamos.renovar', $prestamo) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item" style="color:#145A45">
                                                                <i class="bi bi-arrow-clockwise me-2"></i> Renovar (7 días)
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <!-- ✅ CORRECCIÓN: El comprobante también se puede imprimir aquí -->
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('prestamos.comprobante', $prestamo) }}">
                                                            <i class="bi bi-printer text-secondary me-2"></i> Imprimir Comprobante
                                                        </a>
                                                    </li>
                                                @endif

                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('prestamos.destroy', $prestamo) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Eliminar este registro del historial?')">
                                                            <i class="bi bi-trash me-2"></i> Eliminar Registro
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
                                <td colspan="{{ auth()->user()->role == 'admin' ? '9' : '7' }}">
                                    <div class="empty-loans">
                                        <i class="bi bi-journal-x"></i>
                                        <h5>No hay préstamos registrados</h5>
                                        <p class="mb-0">Los préstamos aparecerán aquí cuando se registren.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center loans-pagination">
                {{ $prestamos->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection