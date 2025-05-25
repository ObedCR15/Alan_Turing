<?php
class DashboardEstudianteModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Obtener el total de pensiones del estudiante en todos los programas
    public function obtenerTotalPensiones(int $idEstudiante): int {
        $sql = "
            SELECT COUNT(*) AS total_pensiones
            FROM pension
            WHERE id_estudiante = :id_estudiante
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_estudiante' => $idEstudiante]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total_pensiones'] : 0;
    }

    // Obtener el total de programas en los que está matriculado el estudiante
    public function obtenerTotalProgramas(int $idEstudiante): int {
        $sql = "
            SELECT COUNT(DISTINCT id_programa) AS total_programas
            FROM matriculas
            WHERE id_estudiante = :id_estudiante
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_estudiante' => $idEstudiante]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total_programas'] : 0;
    }
}
?>
