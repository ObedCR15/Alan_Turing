<?php
class RegistrarCursoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrarCurso($nombre, $descripcion, $id_docente) {
        try {
            $query = "INSERT INTO cursos (nombre_curso, descripcion, id_docente) VALUES (:nombre, :descripcion, :id_docente)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':id_docente', $id_docente);

            if ($stmt->execute()) {
                return "Curso registrado exitosamente.";
            } else {
                return "Error al registrar el curso.";
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function obtenerDocentes() {
        $query = "SELECT id_docente, nombre, apellido, especialidad FROM docentes";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
