<?php
class EstudianteCalificacionModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Obtiene las calificaciones y asistencias de un estudiante,
     * organizadas por programa y curso, con promedio y detalle.
     */
    public function obtenerCalificacionesEstudiante(int $idEstudiante): array {
        $sql = "
            SELECT 
                p.id_programa,
                p.nombre_programa,
                p.duracion,
                c.id_curso,
                c.nombre_curso,
                d.nombre AS docente_nombre,
                d.apellido AS docente_apellido
            FROM programas p
            INNER JOIN matriculas m ON p.id_programa = m.id_programa
            INNER JOIN programa_curso pc ON p.id_programa = pc.id_programa
            INNER JOIN cursos c ON pc.id_curso = c.id_curso
            LEFT JOIN docentes d ON c.id_docente = d.id_docente
            WHERE m.id_estudiante = :id_estudiante
            ORDER BY p.nombre_programa, c.nombre_curso
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_estudiante' => $idEstudiante]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $programas = [];
    
        foreach ($result as $row) {
            $idPrograma = $row['id_programa'];
            $idCurso = $row['id_curso'];
    
            if (!isset($programas[$idPrograma])) {
                $programas[$idPrograma] = [
                    'nombre_programa' => $row['nombre_programa'],
                    'duracion' => $row['duracion'],
                    'cursos' => []
                ];
            }
    
            // Calificaciones
            $sqlNotas = "
                SELECT cal.calificacion, cal.descripcion
                FROM calificaciones cal
                WHERE cal.id_estudiante = :id_estudiante
                  AND cal.id_curso = :id_curso
                  AND cal.id_programa = :id_programa
            ";
            $stmtNotas = $this->pdo->prepare($sqlNotas);
            $stmtNotas->execute([
                'id_estudiante' => $idEstudiante,
                'id_curso' => $idCurso,
                'id_programa' => $idPrograma
            ]);
            $notas = $stmtNotas->fetchAll(PDO::FETCH_ASSOC);
    
            $sumaNotas = 0;
            $cantidadNotas = count($notas);
            foreach ($notas as $nota) {
                $sumaNotas += $nota['calificacion'];
            }
            $promedioNotas = $cantidadNotas > 0 ? round($sumaNotas / $cantidadNotas, 2) : 0;
    
            // Asistencias
            $sqlAsistencias = "
            SELECT 
                COUNT(*) AS total_asistencias,
                SUM(CASE WHEN estado = 'Presente' THEN 1 ELSE 0 END) AS asistencias_presentes
            FROM asistencias
            WHERE id_estudiante = :id_estudiante AND id_curso = :id_curso
             ";
        
            $stmtAsistencias = $this->pdo->prepare($sqlAsistencias);
            $stmtAsistencias->execute([
                'id_estudiante' => $idEstudiante,
                'id_curso' => $idCurso
            ]);
            $asistenciaData = $stmtAsistencias->fetch(PDO::FETCH_ASSOC);
    
            $totalAsistencias = $asistenciaData['total_asistencias'] ?? 0;
            $asistenciasPresentes = $asistenciaData['asistencias_presentes'] ?? 0;
            $porcentajeAsistencia = $totalAsistencias > 0 ? round(($asistenciasPresentes / $totalAsistencias) * 100, 2) : 0;
    
            $programas[$idPrograma]['cursos'][$idCurso] = [
                'nombre_curso' => $row['nombre_curso'],
                'docente_nombre' => $row['docente_nombre'],
                'docente_apellido' => $row['docente_apellido'],
                'calificaciones' => $notas,
                'promedio_notas' => $promedioNotas,
                'cantidad_notas' => $cantidadNotas,
                'cantidad_asistencias' => $totalAsistencias,
                'promedio_asistencia' => $porcentajeAsistencia
            ];
        }
    
        return $programas;
    }
    public function obtenerDetalleFaltas(int $idEstudiante, int $idCurso): array {
        $sql = "SELECT fecha, estado FROM asistencias 
                WHERE id_estudiante = :id_estudiante 
                  AND id_curso = :id_curso
                  AND estado <> 'Presente'
                ORDER BY fecha DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_estudiante' => $idEstudiante,
            'id_curso' => $idCurso
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
}
?>
