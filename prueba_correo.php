<?php
// Incluir las librerías de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'israelharder@gmail.com';      // TU CORREO
    $mail->Password   = 'zdvcapecepracllw';        // CONTRASEÑA DE APLICACIÓN (SIN ESPACIOS)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // o 'tls'
    $mail->Port       = 587;

    // Configuración del correo
    $mail->setFrom('israelharder@gmail.com', 'Prueba XAMPP');
    $mail->addAddress('israel_ijhb@hotmail.com'); // EL CORREO DE DESTINO

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Prueba de PHPMailer desde XAMPP';
    $mail->Body    = 'Este es un mensaje de prueba desde <b>XAMPP</b>';

    $mail->send();
    echo 'Mensaje enviado correctamente';
} catch (Exception $e) {
    // Esto mostrará el error real
    echo "Error al enviar: {$mail->ErrorInfo}";
}
?>