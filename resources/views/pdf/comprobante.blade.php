<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Préstamo</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #145A45; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #145A45; margin: 0; }
        .header p { margin: 5px 0 0 0; color: #666; }
        .details { width: 100%; margin-bottom: 30px; }
        .details td { padding: 10px; border-bottom: 1px solid #eee; }
        .details td strong { color: #145A45; }
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #888; border-top: 1px solid #ddd; padding-top: 10px; }
        .badge { background: #D4A64A; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Biblioteca DB</h1>
        <p>Comprobante de Préstamo #{{ $prestamo->id }}</p>
    </div>

    <table class="details">
        <tr>
            <td><strong>Lector:</strong> {{ $prestamo->lector->nombre }} {{ $prestamo->lector->apellido }}</td>
            <td><strong>Correo:</strong> {{ $prestamo->lector->email }}</td>
        </tr>
        <tr>
            <td><strong>Libro:</strong> {{ $prestamo->libro->titulo }}</td>
            <td><strong>ISBN:</strong> {{ $prestamo->libro->isbn }}</td>
        </tr>
        <tr>
            <td><strong>Fecha de Préstamo:</strong> {{ $prestamo->fecha_prestamo }}</td>
            <td><strong>Fecha Límite de Entrega:</strong> <span class="badge">{{ $prestamo->fecha_limite }}</span></td>
        </tr>
    </table>

    <p style="text-align: justify; font-size: 14px;">
        <strong>Nota:</strong> El lector se compromete a devolver el material en la fecha estipulada. En caso de retraso, se aplicará una multa de $10 por cada día de demora. Si el libro no es recogido en la fecha acordada, la solicitud será cancelada automáticamente.
    </p>

    <div class="footer">
        Documento generado el {{ date('Y-m-d H:i:s') }} - Sistema de Gestión Biblioteca DB
    </div>
</body>
</html>