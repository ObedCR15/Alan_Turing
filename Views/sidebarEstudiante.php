<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Principal</div>
                <a class="nav-link" href="dashboardEstudiante.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                
                <div class="sb-sidenav-menu-heading">Académico</div>
                <a class="nav-link" href="EstudianteMatricula.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-signature"></i></div>
                    Matrículas
                </a>
                
                <a class="nav-link" href="EstudiantePago.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-wallet"></i></div>
                    Pagos
                </a>
                
                <a class="nav-link" href="EstudianteCalificacion.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                    Calificaciones
                </a>
                
                <a class="nav-link" href="EstudianteCurso.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                    Cursos
                </a>
            </div>
        </div>
        
        <div class="sb-sidenav-footer">
            <div class="small">Sesión iniciada como:</div>
            <?= htmlspecialchars($_SESSION['student_name']) ?>
        </div>
    </nav>
</div>
