<?php
require_once('../Config/conexion.php');

class EliminarProgramaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function eliminarPrograma($id_programa) {
        try {
            $stmt1 = $this->pdo->prepare("DELETE FROM programa_curso WHERE id_programa = :id");
            $stmt1->bindParam(':id', $id_programa, PDO::PARAM_INT);
            $stmt1->execute();

            $stmt2 = $this->pdo->prepare("DELETE FROM programas WHERE id_programa = :id");
            $stmt2->bindParam(':id', $id_programa, PDO::PARAM_INT);
            $stmt2->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
