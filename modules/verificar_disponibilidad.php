<?php
include '../conexion.php'; // Conectar a la base de datos

if (isset($_POST['id_implemento'])) {
    $id_implemento = $_POST['id_implemento'];

    $query = "SELECT cantidad FROM implemento WHERE id_implemento = ? AND estado = 'disponible'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_implemento);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['disponible' => true, 'cantidad' => $row['cantidad']]);
    } else {
        echo json_encode(['disponible' => false, 'cantidad' => 0]);
    }
}
?>
