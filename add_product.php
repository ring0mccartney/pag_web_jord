<?php
// add_product.php
require_once 'db_connection.php'; // Asegúrate de que la conexión funcione primero

// Establecer cabeceras para JSON
header('Content-Type: application/json');

// Obtener los datos JSON del cuerpo de la solicitud
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// --- Líneas de depuración para verificar los datos de entrada ---
error_log("DEBUG: Datos RAW recibidos en add_product.php: " . $rawData);
error_log("DEBUG: Datos JSON decodificados en add_product.php: " . print_r($data, true));

// Validación básica de si se recibieron datos
if (empty($data)) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos JSON o el formato es incorrecto.']);
    $conn->close();
    exit();
}
// --- Fin de líneas de depuración de datos de entrada ---


$nombre = $data['nombre'] ?? '';
$precio = $data['precio'] ?? 0.00;
$imagen_url = $data['imagen_url'] ?? null;
$mas_detalles_url = $data['mas_detalles_url'] ?? null;
$en_oferta = $data['en_oferta'] ?? false;
$atributo1 = $data['atributo1'] ?? null;
$atributo2 = $data['atributo2'] ?? null;
$atributo3 = $data['atributo3'] ?? null;
$atributo4 = $data['atributo4'] ?? null;
$atributo5 = $data['atributo5'] ?? null;
$atributo6 = $data['atributo6'] ?? null;
$atributo7 = $data['atributo7'] ?? null;

// Validaciones adicionales para los datos
if (empty($nombre)) {
    error_log("ERROR: Nombre del producto vacío.");
    echo json_encode(['success' => false, 'message' => 'El nombre del producto es obligatorio.']);
    $conn->close();
    exit();
}
if (!is_numeric($precio) || $precio <= 0) {
    error_log("ERROR: Precio del producto inválido: " . $precio);
    echo json_encode(['success' => false, 'message' => 'El precio debe ser un número positivo.']);
    $conn->close();
    exit();
}

// Prevenir inyección SQL usando prepared statements
$stmt = $conn->prepare("INSERT INTO productos (nombre, precio, imagen_url, mas_detalles_url, en_oferta, atributo1, atributo2, atributo3, atributo4, atributo5, atributo6, atributo7) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt === false) {
    // Línea de depuración: Error al preparar la consulta
    error_log("ERROR: Fallo al preparar la consulta INSERT: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor al preparar la consulta.']);
    $conn->close();
    exit();
}

// Bindeo de parámetros
// s: string, d: double/decimal, i: integer (para boolean en MySQL se usa INT/TINYINT)
$bindResult = $stmt->bind_param("sdssisssssss", $nombre, $precio, $imagen_url, $mas_detalles_url, $en_oferta, $atributo1, $atributo2, $atributo3, $atributo4, $atributo5, $atributo6, $atributo7);

if ($bindResult === false) {
    // Línea de depuración: Error al bindear los parámetros
    error_log("ERROR: Fallo al bindear los parámetros en INSERT: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor al procesar datos.']);
    $stmt->close();
    $conn->close();
    exit();
}

if ($stmt->execute()) {
    // Línea de depuración: Éxito en la ejecución
    error_log("INFO: Producto '{$nombre}' añadido exitosamente con ID: " . $stmt->insert_id);
    echo json_encode(['success' => true, 'message' => 'Producto añadido exitosamente.', 'id' => $stmt->insert_id]);
} else {
    // Línea de depuración: Error al ejecutar la consulta
    error_log("ERROR: Fallo al ejecutar la consulta INSERT: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Error al añadir producto: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>