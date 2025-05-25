<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="dashboardEstudiante.php">Alan Turing Institute</a>

    <!-- Navbar derecha -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i> <!-- Icono de usuario -->
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <!-- Configuración -->
                <a class="dropdown-item" href="DocenteCambiarClave.php">
                    <i class="fas fa-cogs me-2"></i> Cambiar Contraseña
                </a>
                <div class="dropdown-divider"></div>
                <!-- Cerrar sesión -->
                <a class="dropdown-item" href="../index.php?action=logout">
                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                </a>
            </div>
        </li>
    </ul>
</nav>