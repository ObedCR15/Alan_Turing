<?php
class DocenteVerEstudiantesModel {
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

    public function obtenerEstudiantesPorCurso(int $idCurso) {
        $sql = "SELECT p.nombre_programa, e.numero_matricula, e.nombre, e.apellido, e.email
                FROM estudiantes e
                INNER JOIN matriculas m ON e.id_estudiante = m.id_estudiante
                INNER JOIN programas p ON m.id_programa = p.id_programa
                INNER JOIN programa_curso pc ON p.id_programa = pc.id_programa
                INNER JOIN cursos c ON pc.id_curso = c.id_curso
                WHERE c.id_curso = :id_curso AND c.id_docente = :id_docente
                ORDER BY p.nombre_programa, e.apellido, e.nombre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_curso' => $idCurso,
            'id_docente' => $this->docenteId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
