<?php
require_once '../Config/conexion.php';

class LoginAdministradorCambiarClaveModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Obtener la clave actual del administrador
    public function obtenerClaveActual(int $idAdministrador): ?string {
        $sql = "SELECT clave FROM administradores WHERE id_administrador = :id_administrador";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_administrador' => $idAdministrador]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['clave'] : null;
    }

    // Actualizar la contraseña del administrador
    public function actualizarClave(int $idAdministrador, string $nuevaClaveHash): bool {
        $sql = "UPDATE administradores SET clave = :clave WHERE id_administrador = :id_administrador";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'clave' => $nuevaClaveHash,
            'id_administrador' => $idAdministrador
        ]);
    }
}
?>
