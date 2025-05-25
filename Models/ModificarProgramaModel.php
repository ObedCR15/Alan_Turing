<?php
// ModificarProgramaModel.php

class ModificarProgramaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener los cursos asociados a un programa
    public function getCursosAsociados($programa_id) {
        $sql = "SELECT c.id_curso, c.nombre_curso 
                FROM cursos c
                JOIN programa_curso pc ON c.id_curso = pc.id_curso 
                WHERE pc.id_programa = :id_programa";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_programa', $programa_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los cursos disponibles
    public function getCursos() {
        $sql = "SELECT * FROM cursos";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar el programa
    public function updatePrograma($programa_id, $nombre_programa, $descripcion, $costo, $pension, $fecha_inicio, $fecha_fin, $duracion, $pensiones) {
        $sql = "UPDATE programas 
                SET nombre_programa = :nombre_programa, descripcion = :descripcion, costo = :costo, pension = :pension, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin, duracion = :duracion, pensiones = :pensiones
                WHERE id_programa = :id_programa";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nombre_programa', $nombre_programa);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':costo', $costo);
        $stmt->bindParam(':pension', $pension);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->bindParam(':duracion', $duracion);
        $stmt->bindParam(':pensiones', $pensiones);
        $stmt->bindParam(':id_programa', $programa_id);
        return $stmt->execute();
    }

    // Eliminar los cursos asociados a un programa
    public function deleteCursosAsociados($programa_id) {
        $sql = "DELETE FROM programa_curso WHERE id_programa = :id_programa";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_programa', $programa_id);
        return $stmt->execute();
    }

    // Asociar cursos a un programa
    public function insertCursosAsociados($programa_id, $cursos) {
        foreach ($cursos as $curso_id) {
            $sql = "INSERT INTO programa_curso (id_programa, id_curso) VALUES (:id_programa, :id_curso)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_programa', $programa_id);
            $stmt->bindParam(':id_curso', $curso_id);
            $stmt->execute();
        }
    }
}
?>
