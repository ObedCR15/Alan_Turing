<?php
require_once '../Config/conexion.php'; // Conexión a la base de datos

class LoginAdministradorModel {
    // Método para verificar las credenciales del administrador
    public static function login($dni, $password) {
        global $pdo;

        // Preparamos la consulta para buscar al administrador con ese DNI
        $stmt = $pdo->prepare("SELECT * FROM administradores WHERE dni = :dni");
        $stmt->execute(['dni' => $dni]);

        // Obtener el resultado de la consulta
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el administrador existe y si la contraseña es correcta
        if ($admin && password_verify($password, $admin['clave'])) {
            return $admin;  // Si las credenciales son correctas, devolver los datos del administrador
        }

        return false;  // Si las credenciales son incorrectas, devolver false
    }
}
?>
