<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contacto.html');
    exit;
}

// Honeypot + límites antispam
if (!empty($_POST['honeypot']) || strlen($_POST['mensaje'] ?? '') > 2000) {
    header('Location: contacto.html?error=spam');
    exit;
}

// Sanitiza/Valida
$nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$mensaje = htmlspecialchars(trim($_POST['mensaje'] ?? ''), ENT_QUOTES, 'UTF-8');
$ip = $_SERVER['REMOTE_ADDR'];

if (empty($nombre) || empty($email) || empty($mensaje) || strlen($nombre) > 100 || 
    !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($mensaje) < 10) {
    header('Location: contacto.html?error=datos');
    exit;
}

// ¡TU EMAIL AQUÍ!
$destino = 'contacto@colabcoworking.com';  
$asunto = 'COLAB Contacto: ' . $nombre;
$cuerpo = "Nombre: $nombre\n Email: $email\n IP: $ip\n📱 User-Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n\n Mensaje:\n$mensaje\n\n---\nCOLAB Coworking Cerámico | Patraix, Valencia";
$headers = "From: no-reply@colabcoworking.com\r\n" . 
           "Reply-To: $email\r\n" .
           "X-Mailer: PHP v" . phpversion() . "\r\n" .
           "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($destino, $asunto, $cuerpo, $headers)) {
    header('Location: contacto.html?success=1');
} else {
    // Fallback log (no BD)
    error_log("Mail falló contacto.php: $destino");
    header('Location: contacto.html?error=envio');
}
exit;
?>