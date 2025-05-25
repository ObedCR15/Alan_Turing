<?php
class DocenteModificarAsistenciaModel {
    private $pdo;
    private $docenteId;

    public function __construct(PDO $pdo, int $docenteId) {
        $this->pdo = $pdo;
        $this->docenteId = $docenteId;
    }

    // Obtener estudiantes registrados en un curso y programa para una fecha específica
    public function obtenerEstudiantesPorCursoYPrograma(int $idCurso, int $idPrograma, string $fecha) {
        $sql = "
            SELECT e.id_estudiante, e.nombre, e.apellido, a.estado
            FROM estudiantes e
            INNER JOIN matriculas m ON e.id_estudiante = m.id_estudiante
            INNER JOIN programas p ON m.id_programa = p.id_programa
            INNER JOIN asistencias a ON e.id_estudiante = a.id_estudiante AND a.fecha = :fecha
            WHERE m.id_programa = :id_programa
            AND EXISTS (
                SELECT 1 FROM programa_curso pc
                WHERE pc.id_programa = p.id_programa
                AND pc.id_curso = :id_curso
            )
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_curso' => $idCurso,
            'id_programa' => $idPrograma,
            'fecha' => $fecha
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar estado de asistencia de un estudiante
    public function actualizarAsistencia(int $idEstudiante, int $idCurso, int $idPrograma, string $fecha, string $estado) {
        $sql = "
            UPDATE asistencias
            SET estado = :estado
            WHERE id_estudiante = :id_estudiante
            AND EXISTS (
                SELECT 1 FROM programa_curso pc
                WHERE pc.id_programa = :id_programa
                AND pc.id_curso = :id_curso
            )
            AND fecha = :fecha
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'estado' => $estado,
            'id_estudiante' => $idEstudiante,
            'id_curso' => $idCurso,
            'id_programa' => $idPrograma,
            'fecha' => $fecha
        ]);
    }
}

?>
