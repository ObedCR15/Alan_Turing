<?php
class RegistrarProgramaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener los cursos disponibles
    public function obtenerCursos() {
        try {
            $stmt = $this->pdo->prepare("SELECT id_curso, nombre_curso FROM cursos");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    // Registrar el programa
    public function registerProgram($nombre_programa, $descripcion, $costo, $pension, $fecha_inicio, $fecha_fin, $duracion, $pensiones, $cursos) {
        try {
            $this->pdo->beginTransaction(); // Comienza la transacción

            // Insertar el nuevo programa en la base de datos
            $stmt = $this->pdo->prepare("INSERT INTO programas (nombre_programa, descripcion, costo, pension, fecha_inicio, fecha_fin, duracion, pensiones)
                                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $nombre_programa,
                $descripcion,
                $costo,
                $pension,
                $fecha_inicio,
                $fecha_fin,
                $duracion,
                $pensiones,
            ]);

            $id_programa = $this->pdo->lastInsertId(); // Obtener el ID del último programa insertado

            // Insertar los cursos seleccionados
            if (!empty($cursos)) {
                foreach ($cursos as $id_curso) {
                    $stmt = $this->pdo->prepare("INSERT INTO programa_curso (id_programa, id_curso) VALUES (?, ?)");
                    $stmt->execute([$id_programa, $id_curso]);
                }
            }

            $this->pdo->commit(); // Confirmar la transacción
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack(); // Revertir la transacción si algo falla
            error_log("Error al registrar el programa: " . $e->getMessage());
            return false;
        }
    }
}
?>
