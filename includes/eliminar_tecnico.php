<?php
// Incluir la configuración de conexión
include('./config.php');

// Asegurarse de que no haya salida previa antes de header()
ob_start();

// Verificar si se ha enviado el ID del técnico a eliminar
if (isset($_POST['id'])) {
    $tecnico_id = $_POST['id'];

    // Preparar la consulta SQL para eliminar el técnico
    $query = "DELETE FROM tecnicos WHERE id = ?";
    
    // Usar una declaración preparada para evitar inyecciones SQL
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $tecnico_id); // Enlazar el ID del técnico
        if ($stmt->execute()) {
            // El técnico ha sido eliminado correctamente
            // Redirigir a la página de técnicos
            header('Location: ../gestion_tecnicos.php?eliminado=exito');
            exit();  // Asegurarse de que no se ejecute más código después de la redirección
        } else {
            // Si hubo un error al eliminar el técnico
            header('Location: ../gestion_tecnicos.php?eliminado=error');
            exit();  // Asegurarse de que no se ejecute más código después de la redirección
        }
        // $stmt->close();  // Comentado temporalmente
    } else {
        // Error en la consulta SQL
        header('Location: ../gestion_tecnicos.php?eliminado=consulta_error');
        exit();  // Asegurarse de que no se ejecute más código después de la redirección
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Finalizar el almacenamiento en búfer de salida
ob_end_flush();
?>
