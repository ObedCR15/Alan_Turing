<?php
class GestionarMatriculaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener las matrículas de los estudiantes por programa
    public function obtenerMatriculas($id_programa = null) {
        // Consulta modificada para incluir el monto de la pensión del programa
        $query = "SELECT m.id_estudiante, m.id_programa, e.numero_matricula, e.nombre AS nombre_estudiante, 
                         e.apellido AS apellido_estudiante, e.DNI AS dni_estudiante, p.nombre_programa, 
                         m.fecha_matricula, m.monto_matricula, m.estado_matricula, m.descuento, e.edad, 
                         p.duracion, p.pension AS monto_pension
                  FROM matriculas m
                  JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                  JOIN programas p ON m.id_programa = p.id_programa"; 
    
        if ($id_programa) {
            $query .= " WHERE m.id_programa = :id_programa";
        }
    
        $stmt = $this->pdo->prepare($query);
        
        if ($id_programa) {
            $stmt->bindParam(':id_programa', $id_programa);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los programas disponibles
    public function obtenerProgramas() {
        $query = "SELECT id_programa, nombre_programa FROM programas";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retorna el array de programas
    }
}
?>
