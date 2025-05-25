<?php
class EstudianteCambiarClaveModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerClaveActual(int $idEstudiante): ?string {
        $sql = "SELECT clave FROM estudiantes WHERE id_estudiante = :id_estudiante";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_estudiante' => $idEstudiante]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['clave'] : null;
    }

    public function actualizarClave(int $idEstudiante, string $nuevaClaveHash): bool {
        $sql = "UPDATE estudiantes SET clave = :clave WHERE id_estudiante = :id_estudiante";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'clave' => $nuevaClaveHash,
            'id_estudiante' => $idEstudiante
        ]);
    }
}
?>
