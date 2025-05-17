<?php
ini_set('display_errors', 1);  // Habilita la visualización de errores
ini_set('display_startup_errors', 1);  // Muestra los errores al iniciar PHP
error_reporting(E_ALL);  // Reporta todos los errores

session_start();
include_once('config.php'); // Incluye la conexión a la base de datos

// Comprobar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    // Verificar que los campos no estén vacíos
    if (empty($nombre) || empty($password)) {
        $_SESSION['login_error'] = 'Por favor ingrese nombre y contraseña.';
        header("Location: ../index.php");
        exit;
    }

    // Consulta para validar las credenciales del técnico
    $query = "SELECT * FROM tecnicos WHERE nombre = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        // Si la preparación falla, muestra el error
        echo "Error al preparar la consulta: " . $conn->error;
        exit;
    }

    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si el usuario existe
    if ($result->num_rows > 0) {
        $tecnico = $result->fetch_assoc(); // Obtener los datos del técnico

        // Verificar si la contraseña es correcta usando password_verify
        if (password_verify($password, $tecnico['contraseña'])) {
            // Almacenar el nombre del técnico en la sesión
            $_SESSION['usuario_nombre'] = $tecnico['nombre'];
            
            // Redirigir al dashboard con mensaje de éxito
            $_SESSION['login_success'] = '¡Bienvenido!';
            header("Location: ../dashboard.php");  // Redirige a la página de login
            exit;
        } else {
            // Si las credenciales son incorrectas, mostrar un mensaje de error
            $_SESSION['login_error'] = 'Nombre o contraseña incorrectos.';
            header("Location: ../index.php");
            exit;
        }
    } else {
        // Si el técnico no existe, mostrar el error
        $_SESSION['login_error'] = 'Nombre o contraseña incorrectos.';
        header("Location: ../index.php");
        exit;
    }
}
?>
