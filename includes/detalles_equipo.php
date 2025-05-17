<?php
require '../includes/config.php';

$id = intval($_GET['id']);
$tipo = $_GET['tipo'];

$tabla = 'equipos_' . $tipo;
$sql = "SELECT * FROM $tabla WHERE equipo_id = $id";
$result = $conn->query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    foreach ($row as $key => $value) {
        if ($key !== 'equipo_id') {
            // Capitaliza y reemplaza guiones bajos
            $label = ucwords(str_replace('_', ' ', $key));
            $data[$label] = $value;
        }
    }
}

echo json_encode($data);
?>
