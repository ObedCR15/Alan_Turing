<?php
class DocenteEliminarAsistenciaModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function eliminarAsistencia(int $idCurso, int $idPrograma, string $fecha) {
        try {
            $this->pdo->beginTransaction();
            
            $sql = "
                DELETE a FROM asistencias a
                JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
                JOIN matriculas m ON e.id_estudiante = m.id_estudiante
                WHERE m.id_programa = :id_programa
                AND a.fecha = :fecha
                AND EXISTS (
                    SELECT 1 FROM programa_curso pc
                    WHERE pc.id_programa = :id_programa
                    AND pc.id_curso = :id_curso
                )
            ";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id_curso' => $idCurso,
                'id_programa' => $idPrograma,
                'fecha' => $fecha
            ]);
            
            $this->pdo->commit();
            return $stmt->rowCount();
            
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al eliminar asistencia: " . $e->getMessage());
            return false;
        }
    }
}