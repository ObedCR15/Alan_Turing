<?php
require_once '../Config/conexion.php';

class LoginDocenteModel {
    public static function autenticar($dni, $password) {
        try {
            $stmt = $GLOBALS['pdo']->prepare("
                SELECT id_docente, nombre, apellido, clave 
                FROM docentes 
                WHERE DNI = :dni
            ");
            $stmt->bindParam(':dni', $dni);
            $stmt->execute();
            
            $docente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($docente && password_verify($password, $docente['clave'])) {
                return [
                    'id' => $docente['id_docente'],
                    'nombre' => $docente['nombre'] . ' ' . $docente['apellido']
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
