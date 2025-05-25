<?php
class DocenteCalificacionModel {
    private $pdo;
    private $docenteId;

    public function __construct(PDO $pdo, int $docenteId) {
        $this->pdo = $pdo;
        $this->docenteId = $docenteId;
    }

    public function obtenerCursosProgramas(): array {
        $sql = "SELECT DISTINCT c.id_curso, c.nombre_curso, p.id_programa, p.nombre_programa
                FROM cursos c
                INNER JOIN programa_curso pc ON c.id_curso = pc.id_curso
                INNER JOIN programas p ON pc.id_programa = p.id_programa
                WHERE c.id_docente = :id_docente
                ORDER BY c.nombre_curso, p.nombre_programa";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_docente' => $this->docenteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Este método agrupa calificaciones por descripción
    public function obtenerCalificacionesAgrupadasPorDescripcion(int $idCurso, int $idPrograma): array {
        $sql = "SELECT cal.descripcion, 
                       COUNT(*) as total_calificaciones,
                       MAX(cal.fecha_registro) as ultima_fecha
                FROM calificaciones cal
                WHERE cal.id_curso = :id_curso
                  AND cal.id_programa = :id_programa
                  AND cal.id_docente = :id_docente
                GROUP BY cal.descripcion
                ORDER BY MAX(cal.fecha_registro) DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_curso' => $idCurso,
            'id_programa' => $idPrograma,
            'id_docente' => $this->docenteId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
