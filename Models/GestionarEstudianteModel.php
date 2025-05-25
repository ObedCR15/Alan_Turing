<?php
class GestionarEstudianteModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerEstudiantes() {
        $stmt = $this->pdo->query("SELECT * FROM estudiantes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarEstudiante($datos) {
        $sql = "INSERT INTO estudiantes 
                (numero_matricula, nombre, apellido, DNI, edad, direccion, celular, email, clave)
                VALUES (:numero_matricula, :nombre, :apellido, :DNI, :edad, :direccion, :celular, :email, :clave)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':numero_matricula' => $datos['numero_matricula'],
            ':nombre'           => $datos['nombre'],
            ':apellido'         => $datos['apellido'],
            ':DNI'              => $datos['DNI'],
            ':edad'             => $datos['edad'],
            ':direccion'        => $datos['direccion'],
            ':celular'          => $datos['celular'],
            ':email'            => $datos['email'],
            ':clave'            => $datos['clave']
        ]);
    }
}
?>
