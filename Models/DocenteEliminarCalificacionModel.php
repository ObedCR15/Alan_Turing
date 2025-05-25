<?php
class DocenteEliminarCalificacionModel {
    private $pdo;
    private $docenteId;

    public function __construct(PDO $pdo, int $docenteId) {
        $this->pdo = $pdo;
        $this->docenteId = $docenteId;
    }

    public function eliminarCalificacionesPorDescripcion(int $idCurso, int $idPrograma, string $descripcion): bool {
        try {
            $sql = "DELETE FROM calificaciones 
                    WHERE id_docente = :id_docente
                      AND id_curso = :id_curso
                      AND id_programa = :id_programa
                      AND descripcion = :descripcion";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id_docente' => $this->docenteId,
                'id_curso' => $idCurso,
                'id_programa' => $idPrograma,
                'descripcion' => $descripcion
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al eliminar calificaciones: " . $e->getMessage());
            return false;
        }
    }
}
