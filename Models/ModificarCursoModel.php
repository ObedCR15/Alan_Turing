<?php
require_once('../Config/conexion.php');

class ModificarCursoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener un curso por ID junto con los datos del docente
    public function obtenerCursoPorId($id) {
        // Consulta que obtiene los datos del curso y el nombre y apellido del docente
        $stmt = $this->pdo->prepare("SELECT c.id_curso, c.nombre_curso, c.descripcion, d.id_docente, d.nombre AS nombre_docente, d.apellido AS apellido_docente, d.especialidad
                                     FROM cursos c
                                     JOIN docentes d ON c.id_docente = d.id_docente
                                     WHERE c.id_curso = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Modificar un curso
    public function modificarCurso($id, $nombre, $descripcion, $id_docente) {
        $stmt = $this->pdo->prepare("UPDATE cursos SET nombre_curso = :nombre, descripcion = :descripcion, id_docente = :id_docente WHERE id_curso = :id");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':id_docente', $id_docente, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Obtener todos los docentes con su especialidad
    public function obtenerDocentes() {
        $stmt = $this->pdo->prepare("SELECT id_docente, nombre, apellido, especialidad FROM docentes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
