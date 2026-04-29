<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs COLAB Contacto - Admin</title>
    <!-- Bootstrap 5 CDN para tablas responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: system-ui; }
        .log-pre { background: #ffffff; border: 1px solid #dee2e6; max-height: 400px; overflow-y: auto; }
        .ip-high { background-color: #fff3cd !important; }
        .ip-danger { background-color: #f8d7da !important; }
        h2 { color: #212529; }
    </style>
</head>
<body class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php
                // SEGURIDAD: Tu clave (CAMBIAR)
                if (!isset($_GET['key']) || $_GET['key'] !== 'claveParaVerLogs123.') {
                    die('<div class="alert alert-danger text-center"><h3>❌ Acceso Denegado</h3><p>Clave incorrecta.</p></div>');
                }

                // ========== LOGS CONTACTO ==========
                if (file_exists('logs-contacto.txt')) {
                    $logs_lines = file('logs-contacto.txt');
                    $total_envios = count($logs_lines);
                    echo '<div class="card mb-4 shadow-sm">';
                    echo '<div class="card-header bg-primary text-white">';
                    echo '<h3 class="mb-0"><i class="bi bi-file-earmark-text"></i> Logs Contacto</h3>';
                    echo '<small class="opacity-75">' . $total_envios . ' envíos totales</small>';
                    echo '</div>';
                    echo '<div class="card-body p-0">';
                    echo '<pre class="log-pre p-4 m-0">' . htmlspecialchars(file_get_contents('logs-contacto.txt')) . '</pre>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-info"><i class="bi bi-info-circle"></i> No hay logs aún (primer envío crea archivo).</div>';
                }

                // ========== CONTADORES IPS ==========
                if (file_exists('ip-contadores.json')) {
                    $contadores = json_decode(file_get_contents('ip-contadores.json'), true) ?: [];
                    $total_ips = count($contadores);
                    echo '<div class="card shadow-sm">';
                    echo '<div class="card-header bg-success text-white">';
                    echo '<h4 class="mb-0"><i class="bi bi-shield-check"></i> Contadores IPs Anti-Spam</h4>';
                    echo '<small>' . $total_ips . ' IPs únicas trackeadas</small>';
                    echo '</div>';
                    echo '<div class="card-body">';
                    
                    if (empty($contadores)) {
                        echo '<p class="text-muted">No hay contadores aún.</p>';
                    } else {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-hover table-sm">';
                        echo '<thead class="table-dark"><tr>
                            <th>IP</th><th>Envíos</th><th>Último</th><th>Estado</th><th>Acción</th>
                        </tr></thead><tbody>';
                        
                        foreach ($contadores as $ip => $data) {
                            $count = $data['count'] ?? 0;
                            $last_seen = date('d/m H:i', $data['last_seen'] ?? 0);
                            $estado = ($count > 5) ? 'danger' : (($count > 2) ? 'warning' : 'success');
                            $clase = ($count > 5) ? 'ip-danger' : (($count > 2) ? 'ip-high' : '');
                            
                            echo "<tr class='$clase'>
                                <td><code>$ip</code></td>
                                <td><strong>$count</strong></td>
                                <td>$last_seen</td>
                                <td>
                                    <span class='badge bg-$estado'>$count envíos</span>
                                </td>
                                <td>
                                    <a href='?key=claveParaVerLogs123.&block=$ip' class='btn btn-sm btn-outline-danger'>
                                        <i class='bi bi-ban'></i> Bloquear
                                    </a>
                                </td>
                            </tr>";
                        }
                        echo '</tbody></table></div>';
                    }
                    echo '</div></div>';
                }

                // ========== ACCIONES ==========
                echo '<div class="card-footer bg-light border-0">';
                echo '<div class="btn-group w-100" role="group">';
                echo '<a href="?key=claveParaVerLogs123.&action=reset" class="btn btn-warning">
                    <i class="bi bi-arrow-clockwise"></i> Reset Contadores
                </a>';
                echo '<a href="borrar_logs.php?key=claveParaVerLogs123." class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Borrar Logs
                </a>';
                if (isset($_GET['block'])) {
                    // AUTO-BLOQUEO IP
                    $ip_block = $_GET['block'];
                    $blacklist = file_exists('.blacklist_ips.txt') ? file('.blacklist_ips.txt', FILE_IGNORE_NEW_LINES) : [];
                    if (!in_array($ip_block, $blacklist)) {
                        $blacklist[] = $ip_block;
                        file_put_contents('.blacklist_ips.txt', implode("\n", $blacklist) . "\n");
                        echo '<a href="?key=claveParaVerLogs123." class="btn btn-success ms-2">
                            <i class="bi bi-check-circle"></i> IP Bloqueada: ' . htmlspecialchars($ip_block) . '
                        </a>';
                    }
                }
                echo '</div>';
                echo '</div>';

                // ========== RESET ==========
                if (isset($_GET['action']) && $_GET['action'] === 'reset') {
                    if (file_exists('ip-contadores.json')) {
                        file_put_contents('ip-contadores.json', '{}');
                    }
                    echo '<div class="alert alert-success mt-3">
                        <i class="bi bi-check-circle-fill"></i> ✅ Contadores reseteados
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS (opcional tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>