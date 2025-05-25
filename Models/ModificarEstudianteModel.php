<?php
require_once('../Config/conexion.php');

class ModificarEstudianteModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Obtener estudiante por ID
    public function obtenerEstudiantePorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM estudiantes WHERE id_estudiante = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar datos del estudiante
    public function actualizarEstudiante($data) {
        $sql = "
            UPDATE estudiantes SET
                nombre = :nombre,
                apellido = :apellido,
                dni = :dni,
                edad = :edad,
                direccion = :direccion,
                celular = :celular,
                email = :email
            WHERE id_estudiante = :id
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre'    => $data['nombre'],
            ':apellido'  => $data['apellido'],
            ':dni'       => $data['dni'],
            ':edad'      => $data['edad'],
            ':direccion' => $data['direccion'],
            ':celular'   => $data['celular'],
            ':email'     => $data['email'],
            ':id'        => $data['id_estudiante']
        ]);
    }

    // Restablecer la contraseña con el DNI
    public function restablecerClaveConDNI($id_estudiante) {
        // Actualiza la contraseña al valor del DNI encriptado
        $sql = "UPDATE estudiantes SET clave = :clave WHERE id_estudiante = :id";
        $stmt = $this->pdo->prepare($sql);

        // Obtén el DNI del estudiante
        $stmtGetDNI = $this->pdo->prepare("SELECT DNI FROM estudiantes WHERE id_estudiante = :id");
        $stmtGetDNI->execute([':id' => $id_estudiante]);
        $dni = $stmtGetDNI->fetch(PDO::FETCH_ASSOC)['DNI'];

        // Encriptamos el DNI y lo establecemos como la nueva clave
        $claveEncriptada = password_hash($dni, PASSWORD_DEFAULT);

        // Actualiza la contraseña
        return $stmt->execute([':clave' => $claveEncriptada, ':id' => $id_estudiante]);
    }
}
?>
