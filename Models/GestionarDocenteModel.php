<?php
class GestionarDocenteModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Obtener todos los docentes
    public function obtenerDocentes() {
        $sql = "SELECT * FROM docentes ORDER BY id_docente DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un docente por ID
    public function obtenerDocentePorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM docentes WHERE id_docente = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Registrar nuevo docente
    public function registrarDocente($data) {
        $sql = "
            INSERT INTO docentes (nombre, apellido, email, especialidad, celular, DNI, direccion, clave, edad)
            VALUES (:nombre, :apellido, :email, :especialidad, :celular, :dni, :direccion, :clave, :edad)
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre'       => $data['nombre'],
            ':apellido'     => $data['apellido'],
            ':email'        => $data['email'],
            ':especialidad' => $data['especialidad'],
            ':celular'      => $data['celular'],
            ':dni'          => $data['DNI'],
            ':direccion'    => $data['direccion'],
            ':clave'        => password_hash($data['clave'], PASSWORD_DEFAULT),
            ':edad'         => $data['edad']
        ]);
    }

    // Actualizar datos del docente
    public function actualizarDocente($data) {
        $sql = "
            UPDATE docentes SET
                nombre = :nombre,
                apellido = :apellido,
                email = :email,
                especialidad = :especialidad,
                celular = :celular,
                DNI = :dni,
                direccion = :direccion,
                edad = :edad
            WHERE id_docente = :id
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre'       => $data['nombre'],
            ':apellido'     => $data['apellido'],
            ':email'        => $data['email'],
            ':especialidad' => $data['especialidad'],
            ':celular'      => $data['celular'],
            ':dni'          => $data['DNI'],
            ':direccion'    => $data['direccion'],
            ':edad'         => $data['edad'],
            ':id'           => $data['id_docente']
        ]);
    }

    // Eliminar docente
    public function eliminarDocente($id) {
        $stmt = $this->pdo->prepare("DELETE FROM docentes WHERE id_docente = :id");
        return $stmt->execute([':id' => $id]);
    }
}
