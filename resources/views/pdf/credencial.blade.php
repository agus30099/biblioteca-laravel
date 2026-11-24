<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background: #F5F0E6; }
        .card { width: 100%; height: 100%; border: 2px solid #145A45; border-radius: 10px; padding: 15px; box-sizing: border-box; }
        .header { background: #145A45; color: white; padding: 8px; text-align: center; border-radius: 8px; border-bottom: 3px solid #D4A64A; }
        .header h2 { margin: 0; font-size: 16px; }
        .content { margin-top: 15px; display: flex; }
        .photo { width: 70px; height: 90px; background: #ddd; border: 2px solid #D4A64A; border-radius: 5px; text-align: center; line-height: 90px; color: #888; font-size: 10px; }
        .info { margin-left: 15px; font-size: 11px; color: #493729; }
        .info p { margin: 3px 0; }
        .info strong { color: #0D4032; }
        .barcode { margin-top: 10px; text-align: center; font-family: 'Libre Barcode 39', cursive; font-size: 30px; }
        /* Simulamos un código de barras con CSS */
        .barcode-mockup { display: inline-block; height: 30px; background: repeating-linear-gradient(90deg, #000, #000 2px, #fff 2px, #fff 4px); width: 150px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2>Biblioteca DB</h2>
        </div>
        <div class="content">
            <div class="photo">FOTO</div>
            <div class="info">
                <p><strong>Nombre:</strong> {{ $lector->nombre }} {{ $lector->apellido }}</p>
                <p><strong>ID Lector:</strong> {{ $lector->id }}</p>
                <p><strong>Correo:</strong> {{ $lector->email }}</p>
                <p><strong>Teléfono:</strong> {{ $lector->telefono }}</p>
            </div>
        </div>
        <div class="barcode">
            <div class="barcode-mockup"></div>
            <div style="font-size: 10px; font-family: sans-serif; margin-top: 2px;">{{ $lector->id }}-{{ date('Y') }}</div>
        </div>
    </div>
</body>
</html>