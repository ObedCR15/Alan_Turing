<?php
class GestionarCursoModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerCursos() {
        $sql = "
            SELECT c.id_curso, c.nombre_curso, c.descripcion, d.nombre AS nombre_docente, d.apellido AS apellido_docente
            FROM cursos c
            JOIN docentes d ON c.id_docente = d.id_docente
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDocentes() {
        $sql = "SELECT id_docente, nombre, apellido FROM docentes";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarCurso($nombre_curso, $descripcion, $id_docente) {
        $sql = "INSERT INTO cursos (nombre_curso, descripcion, id_docente) VALUES (:nombre, :descripcion, :docente)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre_curso,
            ':descripcion' => $descripcion,
            ':docente' => $id_docente
        ]);
    }

    public function eliminarCurso($id_curso) {
        $sql = "DELETE FROM cursos WHERE id_curso = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id_curso]);
    }

    public function obtenerCursoPorId($id) {
        $sql = "SELECT * FROM cursos WHERE id_curso = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarCurso($id, $nombre, $descripcion, $id_docente) {
        $sql = "UPDATE cursos SET nombre_curso = :nombre, descripcion = :descripcion, id_docente = :docente WHERE id_curso = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':docente' => $id_docente,
            ':id' => $id
        ]);
    }
}
?>
