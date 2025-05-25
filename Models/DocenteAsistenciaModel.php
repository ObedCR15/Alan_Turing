<?php
class DocenteAsistenciaModel {
    private $pdo;
    private $docenteId;

    public function __construct(PDO $pdo, int $docenteId) {
        $this->pdo = $pdo;
        $this->docenteId = $docenteId;
    }

    public function obtenerCursos() {
        $sql = "SELECT id_curso, nombre_curso FROM cursos WHERE id_docente = :id_docente";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_docente' => $this->docenteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener programas para un curso seleccionado
    public function obtenerProgramasPorCurso(int $idCurso) {
        $sql = "SELECT DISTINCT p.id_programa, p.nombre_programa
                FROM programas p
                INNER JOIN programa_curso pc ON p.id_programa = pc.id_programa
                WHERE pc.id_curso = :id_curso";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_curso' => $idCurso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener fechas de asistencia de acuerdo al curso y programa seleccionado
    public function obtenerFechasAsistenciaPorPrograma(int $idCurso, int $idPrograma) {
        $sql = "SELECT DISTINCT fecha FROM asistencias a
                INNER JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
                INNER JOIN matriculas m ON e.id_estudiante = m.id_estudiante
                INNER JOIN programas p ON m.id_programa = p.id_programa
                WHERE p.id_programa = :id_programa
                AND EXISTS (
                    SELECT 1 FROM programa_curso pc
                    WHERE pc.id_programa = p.id_programa
                    AND pc.id_curso = :id_curso
                )
                ORDER BY fecha DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_curso' => $idCurso,
            'id_programa' => $idPrograma
        ]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Obtener el resumen de asistencia por programa para una fecha
    public function resumenAsistenciaPorPrograma(int $idCurso, int $idPrograma, string $fecha) {
        $sql = "
            SELECT p.nombre_programa,
                   COUNT(*) AS total_estudiantes,
                   SUM(CASE WHEN a.estado = 'Presente' THEN 1 ELSE 0 END) AS presentes
            FROM asistencias a
            INNER JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
            INNER JOIN matriculas m ON e.id_estudiante = m.id_estudiante
            INNER JOIN programas p ON m.id_programa = p.id_programa
            WHERE p.id_programa = :id_programa
              AND EXISTS (
                    SELECT 1 FROM programa_curso pc
                    WHERE pc.id_programa = p.id_programa
                    AND pc.id_curso = :id_curso
                )
              AND a.fecha = :fecha
            GROUP BY p.nombre_programa
            ORDER BY p.nombre_programa
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_curso' => $idCurso,
            'id_programa' => $idPrograma,
            'fecha' => $fecha
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
