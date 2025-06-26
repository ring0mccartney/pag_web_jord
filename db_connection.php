<?php
// db_connection.php

// Configuración de la base de datos
define('DB_HOST', 'localhost'); // Usualmente 'localhost' para desarrollo
define('DB_USER', 'root');     // Usuario por defecto de MySQL en XAMPP/WAMP
define('DB_PASS', '');         // Contraseña por defecto (vacía para XAMPP/WAMP)
define('DB_NAME', 'newdb'); // ¡CAMBIA ESTO por el nombre de tu base de datos!

// Intentar conectar a la base de datos
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar la conexión
if ($conn->connect_error) {
    // Línea de depuración: Muestra el error de conexión directamente en el navegador
    die("Error de conexión a la base de datos: " . $conn->connect_error);
} else {
    // Línea de depuración: Mensaje de éxito de conexión
    error_log("INFO: Conexión a la base de datos exitosa.");
    // Puedes descomentar la siguiente línea TEMPORALMENTE para ver el mensaje en el navegador:
    // echo "¡Conexión a la base de datos exitosa!<br>";
}

// Opcional: Establecer el juego de caracteres a UTF-8
$conn->set_charset("utf8mb4");

// Configurar cabeceras CORS para permitir que tu frontend acceda
header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cabeceras permitidas

// Manejar solicitudes OPTIONS (pre-vuelo CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>