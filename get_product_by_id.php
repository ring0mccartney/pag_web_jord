<?php
// get_product_by_id.php
require_once 'db_connection.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? null; // Obtener el ID de la URL (GET request)

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID del producto es obligatorio.']);
    $conn->close();
    exit();
}

$stmt = $conn->prepare("SELECT id, nombre, precio, imagen_url, mas_detalles_url, en_oferta, atributo1, atributo2, atributo3, atributo4, atributo5, atributo6, atributo7 FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $product['en_oferta'] = (bool)$product['en_oferta']; // Convertir a booleano para JS
    echo json_encode($product);
} else {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
}

$stmt->close();
$conn->close();
?>