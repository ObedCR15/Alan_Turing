<?php
class DocenteRegistrarAsistenciaModel {
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

    public function obtenerProgramasPorCurso(int $idCurso) {
        $sql = "SELECT DISTINCT p.id_programa, p.nombre_programa
                FROM programas p
                INNER JOIN programa_curso pc ON p.id_programa = pc.id_programa
                WHERE pc.id_curso = :id_curso";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_curso' => $idCurso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstudiantesPorCursoYPrograma(int $idCurso, int $idPrograma) {
        $sql = "SELECT e.id_estudiante, e.numero_matricula, e.nombre, e.apellido
                FROM estudiantes e
                INNER JOIN matriculas m ON e.id_estudiante = m.id_estudiante
                WHERE m.id_programa = :id_programa
                AND EXISTS (
                    SELECT 1 FROM programa_curso pc
                    WHERE pc.id_programa = m.id_programa
                    AND pc.id_curso = :id_curso
                )
                ORDER BY e.apellido, e.nombre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_programa' => $idPrograma,
            'id_curso' => $idCurso
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarAsistencia(int $idEstudiante, string $fecha, string $estado, int $idCurso) {
        $sqlCheck = "SELECT COUNT(*) FROM asistencias WHERE id_estudiante = :id_estudiante AND fecha = :fecha AND id_curso = :id_curso";
        $stmtCheck = $this->pdo->prepare($sqlCheck);
        $stmtCheck->execute(['id_estudiante' => $idEstudiante, 'fecha' => $fecha, 'id_curso' => $idCurso]);
        $exists = $stmtCheck->fetchColumn() > 0;

        if ($exists) {
            $sqlUpdate = "UPDATE asistencias SET estado = :estado WHERE id_estudiante = :id_estudiante AND fecha = :fecha AND id_curso = :id_curso";
            $stmtUpdate = $this->pdo->prepare($sqlUpdate);
            return $stmtUpdate->execute([
                'estado' => $estado,
                'id_estudiante' => $idEstudiante,
                'fecha' => $fecha,
                'id_curso' => $idCurso
            ]);
        } else {
            $sqlInsert = "INSERT INTO asistencias (id_estudiante, fecha, estado, id_curso) VALUES (:id_estudiante, :fecha, :estado, :id_curso)";
            $stmtInsert = $this->pdo->prepare($sqlInsert);
            return $stmtInsert->execute([
                'id_estudiante' => $idEstudiante,
                'fecha' => $fecha,
                'estado' => $estado,
                'id_curso' => $idCurso
            ]);
        }
    }

    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    public function commit() {
        return $this->pdo->commit();
    }
    
    public function rollBack() {
        return $this->pdo->rollBack();
    }
}
