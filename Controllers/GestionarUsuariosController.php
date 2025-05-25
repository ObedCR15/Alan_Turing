<?php
require_once('../Models/GestionarUsuariosModel.php');

class GestionarUsuariosController {
    private $model;

    public function __construct($pdo) {
        $this->model = new GestionarUsuariosModel($pdo);
    }

    public function obtenerAdministradores() {
        try {
            return $this->model->obtenerTodos();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener administradores: " . $e->getMessage());
        }
    }

    public function obtenerAdministradorPorId($id) {
        try {
            return $this->model->obtenerPorId($id);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener administrador: " . $e->getMessage());
        }
    }

    public function agregarAdministrador($datos) {
        $this->validarDatos($datos);
        try {
            return $this->model->crear($datos);
        } catch (PDOException $e) {
            throw new Exception("Error al crear administrador: " . $e->getMessage());
        }
    }

    public function actualizarAdministrador($id, $datos) {
        $this->validarDatos($datos);
        try {
            return $this->model->actualizar($id, $datos);
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar administrador: " . $e->getMessage());
        }
    }

    public function eliminarUsuario($id) {
        try {
            return $this->model->eliminar($id);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar administrador: " . $e->getMessage());
        }
    }

    private function validarDatos($datos) {
        $camposRequeridos = ['nombre', 'apellido', 'DNI', 'email'];
        foreach ($camposRequeridos as $campo) {
            if (empty(trim($datos[$campo]))) {
                throw new Exception("El campo $campo es obligatorio");
            }
        }

        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Formato de email inválido");
        }

        if (!preg_match('/^\d{8}$/', $datos['DNI'])) {
            throw new Exception("El DNI debe tener 8 dígitos numéricos");
        }
    }
}