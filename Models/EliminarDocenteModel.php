<?php
class EliminarDocenteModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function eliminarPorId($id_docente) {
        $sql = "DELETE FROM docentes WHERE id_docente = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id_docente]);
    }
}
