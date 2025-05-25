<?php
class GestionarPensionModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener la cantidad de cuotas de un programa
    public function obtenerCantidadCuotas($id_programa) {
        $stmt = $this->pdo->prepare("SELECT pensiones FROM programas WHERE id_programa = :id_programa");
        $stmt->execute(['id_programa' => $id_programa]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['pensiones'] : 0;
    }

    // Obtener las pensiones de un estudiante para un programa específico
    public function obtenerPensionesPorPrograma($id_programa) {
        $stmt = $this->pdo->prepare("SELECT * FROM pension WHERE id_programa = :id_programa");
        $stmt->execute(['id_programa' => $id_programa]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear pensiones para un programa específico
    public function crearPensiones($id_programa, $id_estudiante, $cantidad_cuotas, $monto) {
        for ($i = 1; $i <= $cantidad_cuotas; $i++) {
            $stmt = $this->pdo->prepare("INSERT INTO pension (id_programa, id_estudiante, numero_cuota, monto_pension, estado_pension) VALUES (:id_programa, :id_estudiante, :numero_cuota, :monto_pension, 'Pendiente')");
            $stmt->execute([
                'id_programa' => $id_programa,
                'id_estudiante' => $id_estudiante,
                'numero_cuota' => $i,
                'monto_pension' => $monto / $cantidad_cuotas  // Distribuir el monto entre las cuotas
            ]);
        }
    }

    // Actualizar el estado de una pensión
    public function actualizarEstadoPension($id_estudiante, $id_programa, $numero_cuota, $estado_pension) {
        $stmt = $this->pdo->prepare("UPDATE pension SET estado_pension = :estado_pension WHERE id_estudiante = :id_estudiante AND id_programa = :id_programa AND numero_cuota = :numero_cuota");
        return $stmt->execute([
            'estado_pension' => $estado_pension,
            'id_estudiante' => $id_estudiante,
            'id_programa' => $id_programa,
            'numero_cuota' => $numero_cuota
        ]);
    }

    // Guardar los cambios cuando se presiona el botón guardar
    public function guardarCambios($data, $id_estudiante) {
        // Puedes agregar más lógica aquí para guardar otros datos si es necesario
        return true;
    }
}

?>
