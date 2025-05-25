<?php
require_once('../Config/conexion.php');

class EliminarCursoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Eliminar las relaciones dependientes de la tabla `programa_curso`
    private function eliminarRelacionProgramaCurso($id_curso) {
        $stmt = $this->pdo->prepare("DELETE FROM programa_curso WHERE id_curso = :id_curso");
        $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Eliminar un curso por ID
    public function eliminarCurso($id) {
        // Primero, eliminamos las relaciones en la tabla programa_curso
        if ($this->eliminarRelacionProgramaCurso($id)) {
            // Ahora eliminamos el curso
            $stmt = $this->pdo->prepare("DELETE FROM cursos WHERE id_curso = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }
        return false;
    }
}
?>
