<?php
// delete_product.php
require_once 'db_connection.php';

header('Content-Type: application/json');

// Obtener los datos JSON del cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null;

// Validación
if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID del producto es obligatorio para eliminar.']);
    $conn->close();
    exit();
}

$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id); // 'i' indica que el parámetro es un entero

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Producto eliminado exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el producto para eliminar.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar producto: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>