<?php
class EliminarMatriculaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para eliminar una matrícula
    public function eliminarMatricula($id_estudiante, $id_programa) {
        try {
            // Eliminar pensiones asociadas
            $query = "DELETE FROM pension WHERE id_estudiante = :id_estudiante AND id_programa = :id_programa";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
            $stmt->bindParam(':id_programa', $id_programa, PDO::PARAM_INT);
            $stmt->execute();

            // Eliminar matrícula
            $query = "DELETE FROM matriculas WHERE id_estudiante = :id_estudiante AND id_programa = :id_programa";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
            $stmt->bindParam(':id_programa', $id_programa, PDO::PARAM_INT);
            $stmt->execute();

            return true; // Matrícula eliminada con éxito
        } catch (Exception $e) {
            return false; // Error en la eliminación
        }
    }
}
?>
