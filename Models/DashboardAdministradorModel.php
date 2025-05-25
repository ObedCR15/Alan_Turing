<?php
// /Models/DashboardAdministradorModel.php
require_once('../Config/conexion.php');  // Incluir la conexión PDO directamente

class DashboardAdministradorModel {
    private $pdo;

    public function __construct() {
        // Utilizar la conexión PDO directamente desde el archivo de conexión
        global $pdo;  // Usamos la variable global $pdo que ya está definida en el archivo de conexión
        $this->pdo = $pdo;
    }

    // Verificar si el administrador existe y la contraseña es correcta
    public function verificarAdministrador($email, $clave) {
        $query = "SELECT * FROM administradores WHERE email = :email AND clave = :clave";
        $stmt = $this->pdo->prepare($query);  // Usamos la conexión PDO
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':clave', $clave);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Retorna el administrador si existe
    }

    // Obtener el total de pagos realizados
    public function getTotalPagos() {
        $query = "SELECT SUM(monto_pension) AS total_pagos FROM pension WHERE estado_pension = 'Pagado'";
        $stmt = $this->pdo->prepare($query);  // Usamos la conexión PDO
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_pagos'];
    }

    // Obtener el total de matrículas
    public function getTotalMatriculas() {
        $query = "SELECT COUNT(*) AS total_matriculas FROM matriculas WHERE estado_matricula = 'Pagado'";
        $stmt = $this->pdo->prepare($query);  // Usamos la conexión PDO
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_matriculas'];
    }

    // Obtener el total de programas
    public function getTotalProgramas() {
        $query = "SELECT COUNT(*) AS total_programas FROM programas";
        $stmt = $this->pdo->prepare($query);  // Usamos la conexión PDO
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_programas'];
    }

    // Obtener el total de usuarios (administradores)
    public function getTotalUsuarios() {
        $query = "SELECT COUNT(*) AS total_usuarios FROM administradores";
        $stmt = $this->pdo->prepare($query);  // Usamos la conexión PDO
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_usuarios'];
    }
}
?>
