<?php
class DocenteCambiarClaveModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerClaveActual(int $idDocente): ?string {
        $sql = "SELECT clave FROM docentes WHERE id_docente = :id_docente";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_docente' => $idDocente]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['clave'] : null;
    }

    public function actualizarClave(int $idDocente, string $nuevaClaveHash): bool {
        $sql = "UPDATE docentes SET clave = :clave WHERE id_docente = :id_docente";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'clave' => $nuevaClaveHash,
            'id_docente' => $idDocente
        ]);
    }
}
?>
