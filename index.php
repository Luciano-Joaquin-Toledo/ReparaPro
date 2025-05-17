<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Sistema de Reparaciones</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 1rem;">
    <h4 class="text-center mb-4">
      <i class="fa-solid fa-screwdriver-wrench me-2 text-primary"></i>
      Sistema de Reparaciones
    </h4>

    <!-- Formulario de inicio de sesión -->
    <form id="loginForm" method="POST" action="./includes/login.php" novalidate>
      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
          <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Luciano">
        </div>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
          <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
        </div>
      </div>

      <!-- Mostrar error si existe -->
      <?php if (isset($_SESSION['login_error'])): ?>
        <div class="alert alert-danger" role="alert">
          <?= $_SESSION['login_error']; ?>
        </div>
        <?php unset($_SESSION['login_error']); ?>
      <?php endif; ?>

      <button type="submit" class="btn btn-primary w-100">
        <i class="fa-solid fa-right-to-bracket me-2"></i>Ingresar
      </button>
    </form>
  </div>

  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
