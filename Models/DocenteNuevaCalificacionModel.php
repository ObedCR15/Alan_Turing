<?php
class DocenteNuevaCalificacionModel {
    private $pdo;
    private $docenteId;

    public function __construct(PDO $pdo, int $docenteId) {
        $this->pdo = $pdo;
        $this->docenteId = $docenteId;
    }

    // Obtener cursos con sus programas asignados al docente
    public function obtenerCursosAsignadosDocente(): array {
        $sql = "SELECT DISTINCT c.id_curso, c.nombre_curso, p.id_programa, p.nombre_programa 
                FROM cursos c
                JOIN programa_curso pc ON c.id_curso = pc.id_curso
                JOIN programas p ON pc.id_programa = p.id_programa
                WHERE c.id_docente = :id_docente
                ORDER BY p.nombre_programa, c.nombre_curso";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_docente' => $this->docenteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener estudiantes matriculados en un curso y programa específicos
    public function obtenerEstudiantesMatriculados(int $idCurso, int $idPrograma): array {
        $sql = "SELECT DISTINCT e.id_estudiante, e.numero_matricula, e.nombre, e.apellido
                FROM estudiantes e
                JOIN matriculas m ON e.id_estudiante = m.id_estudiante
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

    // Guardar calificaciones
    // Cambiar el método guardarCalificaciones para incluir id_programa
    public function guardarCalificaciones(int $idCurso, int $idPrograma, array $notas, string $descripcion): bool {
        try {
            $this->pdo->beginTransaction();
            
            $sql = "INSERT INTO calificaciones 
                    (id_estudiante, id_curso, id_programa, id_docente, calificacion, descripcion)
                    VALUES (:id_estudiante, :id_curso, :id_programa, :id_docente, :calificacion, :descripcion)";
            $stmt = $this->pdo->prepare($sql);

            foreach ($notas as $idEstudiante => $nota) {
                $stmt->execute([
                    ':id_estudiante' => $idEstudiante,
                    ':id_curso' => $idCurso,
                    ':id_programa' => $idPrograma,  // <-- Aquí se agrega
                    ':id_docente' => $this->docenteId,
                    ':calificacion' => $nota,
                    ':descripcion' => $descripcion
                ]);
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al guardar las calificaciones: " . $e->getMessage());
            return false;
        }
    }

}
