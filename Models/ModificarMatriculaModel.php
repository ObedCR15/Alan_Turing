<?php
class ModificarMatriculaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener estudiante con su matrícula
    public function obtenerEstudianteConMatricula($id_estudiante) {
        $stmt = $this->pdo->prepare("SELECT e.*, m.id_programa, m.monto_matricula, m.estado_matricula, m.descuento 
                                     FROM estudiantes e
                                     JOIN matriculas m ON e.id_estudiante = m.id_estudiante
                                     WHERE e.id_estudiante = ?");
        $stmt->execute([$id_estudiante]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener detalles del programa
    public function obtenerProgramaDetalles($id_programa) {
        $stmt = $this->pdo->prepare("SELECT nombre_programa, costo, pension 
                                     FROM programas 
                                     WHERE id_programa = ?");
        $stmt->execute([$id_programa]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener programas activos
    public function obtenerProgramasActivos() {
        $stmt = $this->pdo->query("SELECT id_programa, nombre_programa, costo, pension 
                                   FROM programas 
                                   WHERE fecha_fin >= CURDATE() 
                                   ORDER BY nombre_programa");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar matrícula
    public function actualizarMatricula($id_estudiante, $datos) {
        // Actualizamos la matrícula en lugar de insertar un nuevo registro
        $stmt = $this->pdo->prepare("
            UPDATE matriculas 
            SET id_programa = :programa,
                monto_matricula = :monto,
                estado_matricula = :estado,
                descuento = :descuento
            WHERE id_estudiante = :id_estudiante AND id_programa = :id_programa
        ");
    
        return $stmt->execute([
            ':programa' => $datos['id_programa'],
            ':monto' => $datos['monto_matricula'],
            ':estado' => $datos['estado_matricula'],
            ':descuento' => $datos['descuento'],
            ':id_estudiante' => $id_estudiante,
            ':id_programa' => $datos['id_programa'] // Asegúrate de que el ID del programa se pase correctamente
        ]);
    }
}
?>
