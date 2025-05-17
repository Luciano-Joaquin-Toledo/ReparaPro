<?php
include './config.php';

if (isset($_GET['id'])) {
    $id_cliente = intval($_GET['id']);

    // Consulta para obtener los datos del cliente
    $sql = "SELECT * FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificamos si se encontraron datos
    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
        echo json_encode($cliente);  // Devolver los datos en formato JSON
    } else {
        echo json_encode(['error' => 'Cliente no encontrado']);
    }

    $stmt->close();
    $conn->close();
}
?>
