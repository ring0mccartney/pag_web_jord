<?php
// update_product.php
require_once 'db_connection.php';

header('Content-Type: application/json');

// Obtener los datos JSON del cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null;
$nombre = $data['nombre'] ?? null;
$precio = $data['precio'] ?? null;
$imagen_url = $data['imagen_url'] ?? null;
$mas_detalles_url = $data['mas_detalles_url'] ?? null;
$en_oferta = $data['en_oferta'] ?? null;
$atributo1 = $data['atributo1'] ?? null;
$atributo2 = $data['atributo2'] ?? null;
$atributo3 = $data['atributo3'] ?? null;
$atributo4 = $data['atributo4'] ?? null;
$atributo5 = $data['atributo5'] ?? null;
$atributo6 = $data['atributo6'] ?? null;
$atributo7 = $data['atributo7'] ?? null;


// Validar ID y al menos un campo para actualizar
if (empty($id) || (!isset($nombre) && !isset($precio) && !isset($imagen_url) && !isset($mas_detalles_url) && !isset($en_oferta) && !isset($atributo1) && !isset($atributo2) && !isset($atributo3) && !isset($atributo4) && !isset($atributo5) && !isset($atributo6) && !isset($atributo7))) {
    echo json_encode(['success' => false, 'message' => 'ID del producto y al menos un campo a actualizar son requeridos.']);
    $conn->close();
    exit();
}

// Construir dinámicamente la consulta UPDATE
$setClauses = [];
$bindParams = [];
$types = '';

if (isset($nombre)) { $setClauses[] = "nombre = ?"; $bindParams[] = &$nombre; $types .= 's'; }
if (isset($precio)) { $setClauses[] = "precio = ?"; $bindParams[] = &$precio; $types .= 'd'; } // 'd' para decimal/double
if (isset($imagen_url)) { $setClauses[] = "imagen_url = ?"; $bindParams[] = &$imagen_url; $types .= 's'; }
if (isset($mas_detalles_url)) { $setClauses[] = "mas_detalles_url = ?"; $bindParams[] = &$mas_detalles_url; $types .= 's'; }
if (isset($en_oferta)) { $setClauses[] = "en_oferta = ?"; $bindParams[] = &$en_oferta; $types .= 'i'; } // 'i' para boolean/int
if (isset($atributo1)) { $setClauses[] = "atributo1 = ?"; $bindParams[] = &$atributo1; $types .= 's'; }
if (isset($atributo2)) { $setClauses[] = "atributo2 = ?"; $bindParams[] = &$atributo2; $types .= 's'; }
if (isset($atributo3)) { $setClauses[] = "atributo3 = ?"; $bindParams[] = &$atributo3; $types .= 's'; }
if (isset($atributo4)) { $setClauses[] = "atributo4 = ?"; $bindParams[] = &$atributo4; $types .= 's'; }
if (isset($atributo5)) { $setClauses[] = "atributo5 = ?"; $bindParams[] = &$atributo5; $types .= 's'; }
if (isset($atributo6)) { $setClauses[] = "atributo6 = ?"; $bindParams[] = &$atributo6; $types .= 's'; }
if (isset($atributo7)) { $setClauses[] = "atributo7 = ?"; $bindParams[] = &$atributo7; $types .= 's'; }


if (empty($setClauses)) {
    echo json_encode(['success' => false, 'message' => 'No hay datos para actualizar.']);
    $conn->close();
    exit();
}

$sql = "UPDATE productos SET " . implode(', ', $setClauses) . " WHERE id = ?";
$stmt = $conn->prepare($sql);

// Añadir el ID al final de los parámetros para el WHERE
$bindParams[] = &$id;
$types .= 'i'; // 'i' para ID (entero)

// Usar call_user_func_array para bind_param con un array de referencias
call_user_func_array([$stmt, 'bind_param'], array_merge([$types], $bindParams));

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Producto actualizado exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el producto o no hubo cambios.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar producto: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>