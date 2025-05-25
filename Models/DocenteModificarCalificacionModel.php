<?php
class DocenteModificarCalificacionModel {
    private $pdo;
    private $docenteId;

    public function __construct(PDO $pdo, int $docenteId) {
        $this->pdo = $pdo;
        $this->docenteId = $docenteId;
    }

    public function obtenerCalificacionesPorDescripcion(int $idCurso, int $idPrograma, string $descripcion): array {
        $sql = "SELECT cal.id_estudiante, cal.calificacion, e.numero_matricula, e.nombre, e.apellido
                FROM calificaciones cal
                JOIN estudiantes e ON cal.id_estudiante = e.id_estudiante
                WHERE cal.id_docente = :id_docente
                  AND cal.id_curso = :id_curso
                  AND cal.id_programa = :id_programa
                  AND cal.descripcion = :descripcion";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_docente' => $this->docenteId,
            'id_curso' => $idCurso,
            'id_programa' => $idPrograma,
            'descripcion' => $descripcion
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarCalificacionesYDescripcion(int $idCurso, int $idPrograma, string $descripcionAnterior, string $descripcionNueva, array $notas): bool {
        try {
            $this->pdo->beginTransaction();

            // Actualizar la descripción
            $sqlDesc = "UPDATE calificaciones
                        SET descripcion = :descripcion_nueva
                        WHERE id_docente = :id_docente
                          AND id_curso = :id_curso
                          AND id_programa = :id_programa
                          AND descripcion = :descripcion_anterior";
            $stmtDesc = $this->pdo->prepare($sqlDesc);
            $stmtDesc->execute([
                'descripcion_nueva' => $descripcionNueva,
                'id_docente' => $this->docenteId,
                'id_curso' => $idCurso,
                'id_programa' => $idPrograma,
                'descripcion_anterior' => $descripcionAnterior,
            ]);

            // Actualizar calificaciones
            $sqlCal = "UPDATE calificaciones
                       SET calificacion = :calificacion
                       WHERE id_estudiante = :id_estudiante
                         AND id_docente = :id_docente
                         AND id_curso = :id_curso
                         AND id_programa = :id_programa
                         AND descripcion = :descripcion_nueva";
            $stmtCal = $this->pdo->prepare($sqlCal);

            foreach ($notas as $idEstudiante => $nota) {
                $stmtCal->execute([
                    'calificacion' => $nota,
                    'id_estudiante' => $idEstudiante,
                    'id_docente' => $this->docenteId,
                    'id_curso' => $idCurso,
                    'id_programa' => $idPrograma,
                    'descripcion_nueva' => $descripcionNueva,
                ]);
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al actualizar calificaciones y descripción: " . $e->getMessage());
            return false;
        }
    }
}
