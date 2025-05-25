<?php
class DashboardDocenteModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerResumenDocente($idDocente) {
        try {
            // Total cursos impartidos
            $stmt = $this->pdo->prepare("SELECT COUNT(*) AS totalCursos FROM cursos WHERE id_docente = :id_docente");
            $stmt->bindParam(':id_docente', $idDocente);
            $stmt->execute();
            $totalCursos = $stmt->fetch(PDO::FETCH_ASSOC)['totalCursos'];

            // Total estudiantes a cargo (únicos matriculados en sus cursos)
            $stmt = $this->pdo->prepare("
                SELECT COUNT(DISTINCT m.id_estudiante) AS totalEstudiantes
                FROM matriculas m
                JOIN programa_curso pc ON m.id_programa = pc.id_programa
                JOIN cursos c ON pc.id_curso = c.id_curso
                WHERE c.id_docente = :id_docente
            ");
            $stmt->bindParam(':id_docente', $idDocente);
            $stmt->execute();
            $totalEstudiantes = $stmt->fetch(PDO::FETCH_ASSOC)['totalEstudiantes'];

            // Promedio de notas en todos sus cursos
            $stmt = $this->pdo->prepare("
                SELECT AVG(calificacion) AS promedioNotas
                FROM calificaciones
                JOIN cursos ON calificaciones.id_curso = cursos.id_curso
                WHERE cursos.id_docente = :id_docente
            ");
            $stmt->bindParam(':id_docente', $idDocente);
            $stmt->execute();
            $promedioNotas = $stmt->fetch(PDO::FETCH_ASSOC)['promedioNotas'];
            if ($promedioNotas === null) $promedioNotas = 0;

            return [
                'totalCursos' => $totalCursos,
                'totalEstudiantes' => $totalEstudiantes,
                'promedioNotas' => $promedioNotas
            ];
        } catch (PDOException $e) {
            error_log("Error en obtenerResumenDocente: " . $e->getMessage());
            return [
                'totalCursos' => 0,
                'totalEstudiantes' => 0,
                'promedioNotas' => 0
            ];
        }
    }
}
?>
