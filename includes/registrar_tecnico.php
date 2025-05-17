<?php
// registrar_tecnico.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos
    include('config.php'); // Asegúrate de incluir tu archivo de conexión

    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $especialidad = $_POST['especialidad'];
    $contacto = $_POST['contacto'];
    $estado = $_POST['estado'];
    $contraseña = $_POST['contraseña'];

    // Encriptar la contraseña
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Consultar para insertar el nuevo técnico
    $sql = "INSERT INTO tecnicos (nombre, especialidad, contacto, estado, contraseña) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $especialidad, $contacto, $estado, $contraseña_hash);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página principal o donde sea necesario
        header("Location: ../gestion_tecnicos.php?mensaje=registro_exitoso");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
