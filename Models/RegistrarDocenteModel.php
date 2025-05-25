<?php
class RegistrarDocenteModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function registrar($data) {
        $sql = "INSERT INTO docentes 
                    (nombre, apellido, email, especialidad, DNI, celular, direccion, clave, edad)
                VALUES 
                    (:nombre, :apellido, :email, :especialidad, :dni, :celular, :direccion, :clave, :edad)";

        $stmt = $this->pdo->prepare($sql);

        // La contraseña será igual al DNI
        $dni = trim($data['DNI']);
        $clave = password_hash($dni, PASSWORD_DEFAULT); // encriptación segura

        return $stmt->execute([
            ':nombre'       => $data['nombre'],
            ':apellido'     => $data['apellido'],
            ':email'        => $data['email'],
            ':especialidad' => $data['especialidad'],
            ':dni'          => $dni,
            ':celular'      => $data['celular'],
            ':direccion'    => $data['direccion'],
            ':clave'        => $clave,
            ':edad'         => $data['edad']
        ]);
    }
}
