<?php
include './config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si es una solicitud para guardar o actualizar cliente
    if (isset($_POST['guardar_cliente'])) {
        $id_cliente     = !empty($_POST['id_cliente']) ? intval($_POST['id_cliente']) : null;
        $nombre         = trim($_POST['nombre']);
        $telefono       = trim($_POST['telefono']);
        $email          = trim($_POST['email']);
        $direccion      = trim($_POST['direccion']);
        $dni            = trim($_POST['dni']);
        $tipo_cliente   = trim($_POST['tipo_cliente']);
        $notas          = trim($_POST['notas']);

        if ($id_cliente) {
            // Actualización del cliente
            $sql = "UPDATE clientes SET nombre=?, telefono=?, email=?, direccion=?, dni=?, tipo_cliente=?, notas=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                header("Location: gestion_clientes.php?status=error_prepare");
                exit();
            }
            $stmt->bind_param("sssssssi", $nombre, $telefono, $email, $direccion, $dni, $tipo_cliente, $notas, $id_cliente);
        } else {
            // Inserción de un nuevo cliente
            $sql = "INSERT INTO clientes (nombre, telefono, email, direccion, dni, tipo_cliente, notas) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                header("Location: gestion_clientes.php?status=error_prepare");
                exit();
            }
            $stmt->bind_param("sssssss", $nombre, $telefono, $email, $direccion, $dni, $tipo_cliente, $notas);
        }

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: ../gestion_clientes.php?status=success");
            exit();
        } else {
            $stmt->close();
            $conn->close();
            header("Location: ../gestion_clientes.php?status=error_execute");
            exit();
        }
    }

    // Si es una solicitud para eliminar un cliente
    if (isset($_POST['eliminar_cliente'])) {
        $id_cliente = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($id_cliente) {
            $sql = "DELETE FROM clientes WHERE id=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                header("Location: gestion_clientes.php?status=error_prepare");
                exit();
            }
            $stmt->bind_param("i", $id_cliente);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                // Redirigir a la página de gestión de clientes con el parámetro 'status=deleted'
                header("Location: ../gestion_clientes.php?status=deleted");
                exit();
            } else {
                $stmt->close();
                $conn->close();
                header("Location: ../gestion_clientes.php?status=error_execute");
                exit();
            }
        } else {
            header("Location: ../gestion_clientes.php?status=error_invalid_id");
            exit();
        }
    }
}
ini_set('display_errors', 1);
error_reporting(E_ALL);

?>
