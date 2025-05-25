<?php
class GestionarPagosModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener los pagos por programa
    public function obtenerPagosPorPrograma($id_programa = null) {
        $query = "SELECT 
                    programas.nombre_programa, 
                    matriculas.id_programa,
                    estudiantes.id_estudiante, 
                    estudiantes.nombre AS nombre_estudiante, 
                    estudiantes.apellido AS apellido_estudiante, 
                    estudiantes.DNI AS dni_estudiante, 
                    matriculas.estado_matricula, 
                    matriculas.monto_matricula
                  FROM estudiantes
                  JOIN matriculas ON estudiantes.id_estudiante = matriculas.id_estudiante
                  JOIN programas ON matriculas.id_programa = programas.id_programa
                  WHERE 1=1";
    
        if ($id_programa) {
            $query .= " AND matriculas.id_programa = :id_programa";
        }
    
        $query .= " ORDER BY programas.nombre_programa, estudiantes.apellido";
    
        $stmt = $this->pdo->prepare($query);
    
        if ($id_programa) {
            $stmt->bindParam(':id_programa', $id_programa, PDO::PARAM_INT);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los programas
    public function obtenerProgramas() {
        $query = "SELECT id_programa, nombre_programa FROM programas";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener ingresos totales de las pensiones pagadas
    public function obtenerIngresos() {
        $query = "SELECT SUM(monto_pension) AS total_ingresos
                  FROM pension
                  WHERE estado_pension = 'Pagado'";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_ingresos'] ?? 0;
    }

    // Obtener ingresos de pensiones
    public function obtenerIngresosPensiones() {
        $query = "SELECT SUM(monto_pension) AS ingresos_pensiones 
                  FROM pension 
                  WHERE estado_pension = 'Pagado'";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['ingresos_pensiones'] ?? 0;
    }

    // Obtener ingresos de matrículas
    public function obtenerIngresosMatriculas() {
        $query = "SELECT SUM(monto_matricula) AS ingresos_matriculas 
                  FROM matriculas 
                  WHERE estado_matricula = 'Pagado'";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['ingresos_matriculas'] ?? 0;
    }

    // Obtener las pensiones asociadas a un programa
    public function obtenerPensionesPorPrograma($id_programa) {
        $query = "SELECT
                    pension.id_pension,
                    pension.numero_cuota,
                    pension.monto_pension,
                    pension.estado_pension,
                    estudiantes.nombre AS nombre_estudiante,
                    estudiantes.apellido AS apellido_estudiante
                FROM pension
                JOIN estudiantes ON pension.id_estudiante = estudiantes.id_estudiante
                WHERE pension.id_programa = :id_programa
                ORDER BY pension.numero_cuota";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_programa', $id_programa, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar estado de pensión
    public function actualizarEstadoPension($id_estudiante, $id_programa, $numero_cuota, $estado_pension) {
        $query = "UPDATE pension 
                  SET estado_pension = :estado_pension 
                  WHERE id_estudiante = :id_estudiante 
                  AND id_programa = :id_programa 
                  AND numero_cuota = :numero_cuota";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':estado_pension', $estado_pension);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->bindParam(':id_programa', $id_programa, PDO::PARAM_INT);
        $stmt->bindParam(':numero_cuota', $numero_cuota, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
