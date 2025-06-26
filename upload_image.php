<?php
// upload_image.php
header('Content-Type: application/json');

$target_dir = "uploads/"; // Directorio donde se guardarán las imágenes
// Crea la carpeta 'uploads' si no existe
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_file_size = 5 * 1024 * 1024; // 5 MB

    $file_type = mime_content_type($_FILES["file"]["tmp_name"]);
    $file_size = $_FILES["file"]["size"];

    if (!in_array($file_type, $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido. Solo JPG, PNG, GIF.']);
        exit();
    }

    if ($file_size > $max_file_size) {
        echo json_encode(['success' => false, 'message' => 'El archivo es demasiado grande. Máximo 5MB.']);
        exit();
    }

    $file_extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
    $new_file_name = uniqid() . "." . $file_extension; // Nombre único para el archivo
    $target_file = $target_dir . $new_file_name;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // Devuelve la URL relativa de la imagen
        echo json_encode(['success' => true, 'url' => $target_file, 'message' => 'Imagen subida exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al mover el archivo subido.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se recibió ningún archivo o hubo un error en la subida. Error code: ' . ($_FILES["file"]["error"] ?? 'N/A')]);
}
?>