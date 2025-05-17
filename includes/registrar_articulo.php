<?php
include './config.php';

$nombre = $_POST['nombre'];
$categoria = $_POST['categoria'];
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];
$proveedor = $_POST['proveedor'];
$ubicacion = $_POST['ubicacion'];
$fecha_ingreso = $_POST['fecha_ingreso'];

$sql = "INSERT INTO articulos (nombre, categoria, cantidad, precio, proveedor, ubicacion, fecha_ingreso)
        VALUES ('$nombre', '$categoria', '$cantidad', '$precio', '$proveedor', '$ubicacion', '$fecha_ingreso')";

if ($conn->query($sql) === TRUE) {
    echo "Artículo registrado con éxito";
} else {
    echo "Error al registrar el artículo: " . $conn->error;
}

$conn->close();
?>
