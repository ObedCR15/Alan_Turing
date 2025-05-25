<?php
require_once '../Config/conexion.php';

class LoginEstudianteModel {
    public static function autenticar($dni, $password) {
        try {
            $stmt = $GLOBALS['pdo']->prepare("
                SELECT id_estudiante, nombre, apellido, clave 
                FROM estudiantes 
                WHERE DNI = :dni
            ");
            $stmt->bindParam(':dni', $dni);
            $stmt->execute();
            
            $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($estudiante && password_verify($password, $estudiante['clave'])) {
                return [
                    'id' => $estudiante['id_estudiante'],
                    'nombre' => $estudiante['nombre'] . ' ' . $estudiante['apellido']
                ];
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Error de autenticación: " . $e->getMessage());
            return false;
        }
    }
}
?>