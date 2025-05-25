<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador</title>
    <link href="../assets/css/styles.css" rel="stylesheet" />
</head>
<body class="bg-primary">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header">
                        <h3 class="text-center font-weight-light my-4">Iniciar Sesión (Administrador)</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        <form action="../Controllers/LoginAdministradorController.php?action=login" method="POST">
                            <div class="form-group">
                                <label>DNI</label>
                                <input type="text" name="dni" class="form-control" placeholder="Ingrese su DNI" required>
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="Ingrese su contraseña" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-3">Iniciar Sesión</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <small>Alan Turing Institute - Administrador</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
