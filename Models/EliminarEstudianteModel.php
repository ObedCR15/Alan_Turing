<?php
class EliminarEstudianteModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function eliminar($id_estudiante) {
        $sql = "DELETE FROM estudiantes WHERE id_estudiante = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id_estudiante]);
    }
}
