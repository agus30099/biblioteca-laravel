<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Préstamos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { color: #145A45; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #145A45; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h1>Reporte de Préstamos Activos</h1>
    <p>Fecha de generación: {{ date('Y-m-d') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Lector</th>
                <th>Libro</th>
                <th>F. Préstamo</th>
                <th>F. Límite</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestamos as $prestamo)
                <tr>
                    <td>{{ $prestamo->id }}</td>
                    <td>{{ $prestamo->lector->nombre }} {{ $prestamo->lector->apellido }}</td>
                    <td>{{ $prestamo->libro->titulo }}</td>
                    <td>{{ $prestamo->fecha_prestamo }}</td>
                    <td style="color: {{ date('Y-m-d') > $prestamo->fecha_limite ? 'red' : 'black' }}; font-weight: bold;">
                        {{ $prestamo->fecha_limite }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>