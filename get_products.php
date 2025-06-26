<?php
// get_products.php
require_once 'db_connection.php';

// Establecer cabeceras para JSON
header('Content-Type: application/json');

$sql = "SELECT id, nombre, precio, imagen_url, mas_detalles_url, en_oferta, atributo1, atributo2, atributo3, atributo4, atributo5, atributo6, atributo7 FROM productos";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Asegúrate de que 'en_oferta' sea un booleano para JS
        $row['en_oferta'] = (bool)$row['en_oferta'];
        $products[] = $row;
    }
}

echo json_encode($products); // Devuelve los productos en formato JSON

$conn->close();
?>