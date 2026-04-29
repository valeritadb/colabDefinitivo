<?php
// 1. CHECK MÉTODO
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contacto.html');
    exit;
}

$blacklist_file = '.blacklist_ips.txt';
if (file_exists($blacklist_file)) {
    $blacklist = file($blacklist_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (in_array($_SERVER['REMOTE_ADDR'], $blacklist)) {
        header('Location: contacto.html?error=spam');
        exit;
    }
}

// 2. BLACKLIST MANUAL (IPs fijas de logs-contacto.txt - método rápido)
//Añadirlas manualmente entre '' y separadas por .
$blacklist_ips = [
    
];
if (in_array($_SERVER['REMOTE_ADDR'], $blacklist_ips)) {
    header('Location: contacto.html?error=spam');
    exit;
}

// 3. ANTISPAM BÁSICO (sin cambios)
if (!empty($_POST['honeypot']) || strlen($_POST['mensaje'] ?? '') > 2000) {
    header('Location: contacto.html?error=spam');
    exit;
}

// 4. MÉTODO DINÁMICO: Auto-contador IPs
$ip_actual = $_SERVER['REMOTE_ADDR'];
$colab_dir = 'colab/';  // Carpeta destino
$contador_file = $colab_dir . 'ip-contadores.json';

// AUTO-CREA CARPETA COLAB si no existe
/*if (!is_dir($colab_dir)) {
    mkdir($colab_dir, 0755, true);  // Crea recursivo (pages/colab/)
}*/

// Lee contadores
$contadores = [];
if (file_exists($contador_file)) {
    $contadores = json_decode(file_get_contents($contador_file), true) ?: [];
}

if (!isset($contadores[$ip_actual])) {
    $contadores[$ip_actual] = ['count' => 0, 'last_seen' => time()];
}
$contadores[$ip_actual]['count']++;
$contadores[$ip_actual]['last_seen'] = time();

// Bloquea >5 (ajusta)
if ($contadores[$ip_actual]['count'] > 5) {
    header('Location: contacto.html?error=spam');
    exit;
}

// GUARDA con ruta completa
file_put_contents($contador_file, json_encode($contadores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));


// 5. LIMPIA DATOS (sin cambios)
$nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$mensaje = htmlspecialchars(trim($_POST['mensaje'] ?? ''), ENT_QUOTES, 'UTF-8');

// 6. LOG COMPLETO (añade contador para debug)
$ip = $ip_actual;
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$fecha = date('Y-m-d H:i:s');
$contador_ip = $contadores[$ip_actual]['count'] ?? 0;

$log_entry = "[$fecha] IP:$ip | UA:$user_agent | Count:$contador_ip | Nombre:$nombre | Email:$email\n";
file_put_contents('colab/logs-contacto.txt', $log_entry, FILE_APPEND | LOCK_EX);

// 7. VALIDA (sin cambios)
if (empty($nombre) || empty($email) || empty($mensaje) || strlen($nombre) > 100 || 
    !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($mensaje) < 10) {
    header('Location: contacto.html?error=datos');
    exit;
}

// 8. EMAIL LIMPIO (sin cambios)
$destino = 'contacto@colabcoworking.com';
$asunto = 'COLAB Contacto: ' . $nombre;
$cuerpo = "Nombre: $nombre\nEmail: $email\n\nMensaje:\n$mensaje\n\n---\nCOLAB Coworking Cerámico\nCarrer de Franco Tormo 21ac, Patraix\nValència";
$headers = "From: no-reply@colabcoworking.com\r\n" .
           "Reply-To: $email\r\n" .
           "X-Mailer: PHP v" . phpversion() . "\r\n" .
           "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($destino, $asunto, $cuerpo, $headers)) {
    header('Location: contacto.html?success=1');
} else {
    error_log("Mail falló contacto.php: $destino | IP:$ip");
    header('Location: contacto.html?error=envio');
}
exit;
?>