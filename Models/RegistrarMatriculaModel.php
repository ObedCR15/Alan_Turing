<?php
class RegistrarMatriculaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function verificarMatriculaExistente($id_estudiante, $id_programa) {
        $query = "SELECT COUNT(*) FROM matriculas 
                  WHERE id_estudiante = :id_estudiante 
                  AND id_programa = :id_programa";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':id_estudiante' => $id_estudiante,
            ':id_programa' => $id_programa
        ]);
        return $stmt->fetchColumn() > 0;
    }

    public function registrarMatricula($id_estudiante, $id_programa, $monto_matricula, $estado_matricula, $descuento) {
        $monto_final = $monto_matricula - ($monto_matricula * $descuento / 100);
        
        $query = "INSERT INTO matriculas 
                 (id_estudiante, id_programa, monto_matricula, estado_matricula, descuento) 
                 VALUES (:id_estudiante, :id_programa, :monto_final, :estado_matricula, :descuento)";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':id_estudiante' => $id_estudiante,
            ':id_programa' => $id_programa,
            ':monto_final' => $monto_final,
            ':estado_matricula' => $estado_matricula,
            ':descuento' => $descuento
        ]);
        
        $programa_detalle = $this->obtenerProgramaDetalles($id_programa);
        $this->registrarPensiones(
            $id_estudiante,
            $id_programa,
            $programa_detalle['pension'],
            $programa_detalle['duracion'],
            ($estado_matricula == 'Pagado'),
            $descuento
        );
        
        return "✅ Matrícula registrada exitosamente";
    }

    public function registrarPensiones($id_estudiante, $id_programa, $monto_pension, $duracion, $pagarPrimera = false, $descuento) {
        $monto_pension = $monto_pension - ($monto_pension * $descuento / 100);

        for ($cuota = 1; $cuota <= $duracion; $cuota++) {
            $fecha_pago = date('Y-m-d', strtotime("+$cuota month"));
            $estado = ($pagarPrimera && $cuota == 1) ? 'Pagado' : 'Pendiente';

            $query = "INSERT INTO pension 
                     (id_estudiante, id_programa, numero_cuota, fecha_pago, monto_pension, estado_pension, beca) 
                     VALUES (:id_estudiante, :id_programa, :cuota, :fecha, :monto, :estado, :beca)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':id_estudiante' => $id_estudiante,
                ':id_programa' => $id_programa,
                ':cuota' => $cuota,
                ':fecha' => $fecha_pago,
                ':monto' => $monto_pension,
                ':estado' => $estado,
                ':beca' => $descuento
            ]);
        }
        return true;
    }

    public function obtenerProgramaDetalles($id_programa) {
        $query = "SELECT nombre_programa, costo, pension, duracion, pensiones 
                  FROM programas 
                  WHERE id_programa = :id_programa";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id_programa' => $id_programa]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerProgramas() {
        $query = "SELECT id_programa, nombre_programa, costo, pension, duracion, pensiones 
                  FROM programas 
                  WHERE fecha_inicio <= CURDATE() 
                  AND fecha_fin >= CURDATE()
                  ORDER BY nombre_programa";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarEstudiantePorNombreApellido($nombre_apellido) {
        $query = "SELECT id_estudiante, numero_matricula, nombre, apellido, DNI, edad 
                  FROM estudiantes 
                  WHERE CONCAT(nombre, ' ', apellido) LIKE :nombre_apellido 
                  OR nombre LIKE :nombre_apellido 
                  OR apellido LIKE :nombre_apellido
                  ORDER BY apellido, nombre";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':nombre_apellido', "%$nombre_apellido%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstudiantePorId($id_estudiante) {
        $query = "SELECT id_estudiante, numero_matricula, nombre, apellido, DNI, edad 
                  FROM estudiantes 
                  WHERE id_estudiante = :id_estudiante";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id_estudiante' => $id_estudiante]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>