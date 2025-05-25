<?php
$pdo = require_once '../conexion.php'; // ← Tu archivo original
require_once '../Models/GestionarEstudianteModel.php';

$estudianteModel = new GestionarEstudianteModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
    $datos = [
        'numero_matricula' => $_POST['numero_matricula'],
        'nombre'           => $_POST['nombre'],
        'apellido'         => $_POST['apellido'],
        'DNI'              => $_POST['DNI'],
        'edad'             => $_POST['edad'],
        'direccion'        => $_POST['direccion'],
        'celular'          => $_POST['celular'],
        'email'            => $_POST['email'],
        'clave'            => password_hash($_POST['clave'], PASSWORD_DEFAULT),
    ];

    $registrado = $estudianteModel->registrarEstudiante($datos);
    header("Location: ../Views/GestionarEstudiante.php?mensaje=" . ($registrado ? "ok" : "error"));
    exit();
}
?>
