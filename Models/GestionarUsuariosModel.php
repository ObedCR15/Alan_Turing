<?php
class GestionarUsuariosModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodos() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM administradores ORDER BY id_administrador DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error en la consulta: " . $e->getMessage());
        }
    }

    public function obtenerPorId($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM administradores WHERE id_administrador = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                throw new Exception("Administrador no encontrado");
            }
            
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Error en la consulta: " . $e->getMessage());
        }
    }

    public function crear($datos) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO administradores 
                (nombre, apellido, celular, email, DNI, direccion, clave)
                VALUES (:nombre, :apellido, :celular, :email, :DNI, :direccion, :clave)
            ");
            
            $clave = password_hash($datos['DNI'], PASSWORD_BCRYPT);
            
            return $stmt->execute([
                ':nombre' => $datos['nombre'],
                ':apellido' => $datos['apellido'],
                ':celular' => $datos['celular'] ?? null,
                ':email' => $datos['email'],
                ':DNI' => $datos['DNI'],
                ':direccion' => $datos['direccion'] ?? null,
                ':clave' => $clave
            ]);
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Error de duplicado
                throw new Exception("El DNI o Email ya están registrados");
            }
            throw new Exception("Error al crear: " . $e->getMessage());
        }
    }

    public function actualizar($id, $datos) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE administradores SET
                    nombre = :nombre,
                    apellido = :apellido,
                    celular = :celular,
                    email = :email,
                    DNI = :DNI,
                    direccion = :direccion
                WHERE id_administrador = :id
            ");
            
            return $stmt->execute([
                ':nombre' => $datos['nombre'],
                ':apellido' => $datos['apellido'],
                ':celular' => $datos['celular'] ?? null,
                ':email' => $datos['email'],
                ':DNI' => $datos['DNI'],
                ':direccion' => $datos['direccion'] ?? null,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                throw new Exception("El DNI o Email ya están registrados");
            }
            throw new Exception("Error al actualizar: " . $e->getMessage());
        }
    }

    public function eliminar($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM administradores WHERE id_administrador = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
}