<?php
// Activar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Verificar que es POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener datos del formulario
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$empresa = $_POST['empresa'] ?? '';
$mensaje = $_POST['mensaje'] ?? '';

// Validar campos
if (empty($nombre) || empty($email) || empty($empresa) || empty($mensaje)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
    exit;
}

// Configurar PHPMailer con los DATOS CORRECTOS DE TU HOSTING
$mail = new PHPMailer(true);

try {
    // ==============================================
    // CONFIGURACIÓN MODIFICADA - DATOS DE TU HOSTING
    // ==============================================
    $mail->isSMTP();
    $mail->Host       = 'mail.mdconsultoria.mx';      // Servidor de tu hosting
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contacto@mdconsultoria.mx';  // Cuenta REAL de tu hosting
    $mail->Password   = '5XBCE{&Z]1~N'; // ¡CAMBIA ESTO!
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   // SSL (para puerto 465)
    $mail->Port       = 465;                           // Puerto SSL

    // Configuración del correo
    $mail->setFrom('contacto@mdconsultoria.mx', 'Formulario Web'); // Mismo correo del username
    $mail->addAddress('contacto@mdconsultoria.mx');      // A QUIEN LE LLEGA
    $mail->addReplyTo($email, $nombre);                // Para responder al cliente

    // Contenido del correo (se queda IGUAL)
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = "Nueva consulta de $nombre - $empresa";
    
    // Cuerpo del mensaje
    $mail->Body = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .header { background: #003366; color: white; padding: 15px; text-align: center; }
            .content { padding: 20px; }
            .campo { margin-bottom: 15px; }
            .label { font-weight: bold; color: #003366; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>Nueva solicitud de servicio</h2>
        </div>
        <div class='content'>
            <div class='campo'>
                <div class='label'>Nombre completo:</div>
                <div>" . htmlspecialchars($nombre) . "</div>
            </div>
            <div class='campo'>
                <div class='label'>Correo electrónico:</div>
                <div>" . htmlspecialchars($email) . "</div>
            </div>
            <div class='campo'>
                <div class='label'>Empresa:</div>
                <div>" . htmlspecialchars($empresa) . "</div>
            </div>
            <div class='campo'>
                <div class='label'>Mensaje:</div>
                <div>" . nl2br(htmlspecialchars($mensaje)) . "</div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Versión texto plano
    $mail->AltBody = "Nombre: $nombre\nEmail: $email\nEmpresa: $empresa\nMensaje: $mensaje";

    $mail->send();
    
    // Respuesta exitosa
    echo json_encode(['success' => true, 'message' => '¡Mensaje enviado correctamente! Te contactaremos pronto.']);
    
} catch (Exception $e) {
    // Respuesta con error
    echo json_encode(['success' => false, 'message' => 'Error al enviar: ' . $mail->ErrorInfo]);
}
?>