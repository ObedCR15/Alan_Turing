<?php

class ModificarDocenteModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM docentes WHERE id_docente = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($data) {
        $sql = "UPDATE docentes SET nombre = :nombre, apellido = :apellido, email = :email, especialidad = :especialidad, DNI = :DNI, celular = :celular, edad = :edad, direccion = :direccion WHERE id_docente = :id_docente";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':apellido' => $data['apellido'],
            ':email' => $data['email'],
            ':especialidad' => $data['especialidad'] ?? null,
            ':DNI' => $data['DNI'],
            ':celular' => $data['celular'] ?? null,
            ':edad' => $data['edad'] ?? null,
            ':direccion' => $data['direccion'] ?? null,
            ':id_docente' => $data['id_docente'],
        ]);
    }

    public function restablecerClaveConDNI($id_docente) {
        $stmt = $this->pdo->prepare("SELECT DNI FROM docentes WHERE id_docente = :id");
        $stmt->execute([':id' => $id_docente]);
        $dni = $stmt->fetchColumn();

        if (!$dni) {
            return false;
        }

        $claveEncriptada = password_hash($dni, PASSWORD_DEFAULT);
        $updateStmt = $this->pdo->prepare("UPDATE docentes SET clave = :clave WHERE id_docente = :id");
        return $updateStmt->execute([
            ':clave' => $claveEncriptada,
            ':id' => $id_docente,
        ]);
    }
}
?>
