<?php

class EstudiantePagoModel {
    private $pdo;

    // Constructor recibe la conexión PDO
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Obtener los pagos del estudiante agrupados por programa
    public function obtenerPagosEstudiante(int $idEstudiante): array {
        $sql = "
            SELECT 
                p.nombre_programa, p.duracion,
                m.monto_matricula, m.estado_matricula,
                mp.numero_cuota, mp.fecha_pago, mp.monto_pension, mp.estado_pension
            FROM programas p
            JOIN matriculas m ON p.id_programa = m.id_programa
            LEFT JOIN pension mp ON m.id_estudiante = mp.id_estudiante AND m.id_programa = mp.id_programa
            WHERE m.id_estudiante = :id_estudiante
            ORDER BY p.nombre_programa, mp.numero_cuota ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();

        // Agrupar los pagos por programa
        $programas = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $programaNombre = $row['nombre_programa'];

            // Si el programa no está en el array, lo agregamos
            if (!isset($programas[$programaNombre])) {
                $programas[$programaNombre] = [
                    'duracion' => $row['duracion'],
                    'monto_matricula' => $row['monto_matricula'],
                    'estado_matricula' => $row['estado_matricula'],
                    'pensiones' => []
                ];
            }

            // Añadir la información de las pensiones dentro del programa
            $programas[$programaNombre]['pensiones'][] = [
                'numero_cuota' => $row['numero_cuota'],
                'fecha_pago' => $row['fecha_pago'],
                'monto_pension' => $row['monto_pension'],
                'estado_pension' => $row['estado_pension']
            ];
        }

        return $programas;
    }
}
?>
