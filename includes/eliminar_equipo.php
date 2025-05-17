<?php
require './config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Eliminar de subtables según tipo (si aplica)
    // Esto es opcional si tenés ON DELETE CASCADE en tus FKs

    $conn->query("DELETE FROM equipos WHERE id = $id");

    header("Location: ../gestion_equipos.php?msg=eliminado");
    exit();
} else {
    header("Location: ../gestion_equipos.php?msg=error");
    exit();
}
?>
