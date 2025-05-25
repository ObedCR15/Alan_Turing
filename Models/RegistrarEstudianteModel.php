<?php
class RegistrarEstudianteModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function registrar(array $datos): bool {
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
            ':clave'            => password_hash($datos['clave'], PASSWORD_DEFAULT) // Encripta la contraseña
        ]);
    }

    public function generarNumeroMatricula(): string {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM estudiantes");
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        $contador = (int)$fila['total'] + 1;
        return 'MAT' . str_pad($contador, 3, '0', STR_PAD_LEFT);
    }
}
