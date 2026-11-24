<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #493729; }
        .header { text-align: center; border-bottom: 2px solid #D4A64A; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #145A45; margin: 0; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #145A45; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #E8E0D2; }
        .text-danger { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Biblioteca DB</h1>
        <p>Reporte de Préstamos Vencidos - Corte al {{ $hoy }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Lector</th>
                <th>Libro</th>
                <th>F. Límite</th>
                <th>Días de Retraso</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vencidos as $v)
                @php
                    $fecha1 = new DateTime($v->fecha_limite);
                    $fecha2 = new DateTime($hoy);
                    $dias = $fecha1->diff($fecha2)->days;
                @endphp
                <tr>
                    <td>{{ $v->lector->nombre }} {{ $v->lector->apellido }}</td>
                    <td>{{ $v->libro->titulo }}</td>
                    <td class="text-danger">{{ $v->fecha_limite }}</td>
                    <td class="text-danger">{{ $dias }} días</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">¡No hay préstamos vencidos! Todo en orden.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>