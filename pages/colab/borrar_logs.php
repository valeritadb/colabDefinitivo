<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar Logs - COLAB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <?php
                // SEGURIDAD: Tu clave
                if (!isset($_GET['key']) || $_GET['key'] !== 'claveParaVerLogs123.') {
                    die('<div class="alert alert-danger text-center h4"><i class="bi bi-shield-exclamation"></i> Acceso Denegado</div>');
                }

                // BORRAR ARCHIVOS
                $archivos_borrados = 0;
                
                // Logs contacto
                if (file_exists('logs-contacto.txt')) {
                    unlink('logs-contacto.txt');
                    $archivos_borrados++;
                }
                
                // Opcional: contadores (descomenta si quieres)
                // if (file_exists('ip-contadores.json')) {
                //     unlink('ip-contadores.json');
                //     $archivos_borrados++;
                // }
                
                // Blacklist (NO borrar, mantener bloqueos)
                // NO tocar .blacklist_ips.txt

                if ($archivos_borrados > 0) {
                    echo '<div class="alert alert-success text-center">';
                    echo '<h3><i class="bi bi-check-circle-fill fs-1 text-success mb-3"></i></h3>';
                    echo '<h4>✅ Logs Borrados Exitosamente</h4>';
                    echo '<p class="lead">Se eliminaron ' . $archivos_borrados . ' archivo(s)</p>';
                    echo '<hr>';
                } else {
                    echo '<div class="alert alert-warning text-center">';
                    echo '<h4><i class="bi bi-info-circle"></i> No hay logs para borrar</h4>';
                    echo '<p>Los archivos se recrean automáticamente con nuevos envíos.</p>';
                }
                ?>
                
                <div class="text-center mt-4">
                    <a href="ver_logs.php?key=claveParaVerLogs123." class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-left"></i> Volver Dashboard
                    </a>
                    <a href="contacto.html" class="btn btn-outline-secondary btn-lg ms-2">
                        <i class="bi bi-house"></i> Form Contacto
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>