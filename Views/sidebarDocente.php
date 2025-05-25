<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Principal</div>
                <a class="nav-link" href="dashboardDocente.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <div class="sb-sidenav-menu-heading">Académico</div>
                <a class="nav-link" href="DocenteCalificacion.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-pencil-alt"></i></div>
                    Gestionar Calificaciones
                </a>

                <a class="nav-link" href="DocenteAsistencia.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                    Gestionar Asistencias
                </a>

                <a class="nav-link" href="DocenteVerEstudiantes.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Ver Estudiantes
                </a>
            </div>
        </div>

        <div class="sb-sidenav-footer">
            <div class="small">Sesión iniciada como:</div>
            <?= htmlspecialchars($_SESSION['docente_name']) ?>
        </div>
    </nav>
</div>
