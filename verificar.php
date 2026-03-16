<?php
echo "<h2>Diagnóstico de rutas</h2>";
echo "<p><strong>Directorio actual:</strong> " . __DIR__ . "</p>";
echo "<p><strong>Contenido de la carpeta:</strong></p>";
echo "<ul>";

$archivos = scandir(__DIR__);
foreach ($archivos as $archivo) {
    if ($archivo != "." && $archivo != "..") {
        $tipo = is_dir($archivo) ? "📁" : "📄";
        echo "<li>$tipo $archivo</li>";
    }
}
echo "</ul>";

// Verificar específicamente PHPMailer
if (file_exists(__DIR__ . '/PHPMailer')) {
    echo "<p style='color: green;'>✓ La carpeta PHPMailer EXISTE</p>";
    
    if (file_exists(__DIR__ . '/PHPMailer/src/Exception.php')) {
        echo "<p style='color: green;'>✓ Los archivos de PHPMailer están completos</p>";
    } else {
        echo "<p style='color: red;'>✗ La carpeta src no contiene los archivos necesarios</p>";
    }
} else {
    echo "<p style='color: red;'>✗ La carpeta PHPMailer NO existe</p>";
    echo "<p>Debes descargar PHPMailer y colocarlo en: " . __DIR__ . "/PHPMailer/</p>";
}
?>