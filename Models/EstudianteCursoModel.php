<?php

class EstudianteCursoModel {
    private $pdo;

    // Constructor recibe la conexión PDO
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Obtener los cursos del estudiante agrupados por programa
    public function obtenerCursosEstudiante(int $idEstudiante): array {
        $sql = "
            SELECT 
                p.nombre_programa, p.duracion,
                c.nombre_curso, c.descripcion, 
                d.nombre AS docente_nombre, d.apellido AS docente_apellido
            FROM programas p
            JOIN matriculas m ON p.id_programa = m.id_programa
            JOIN programa_curso pc ON p.id_programa = pc.id_programa
            JOIN cursos c ON pc.id_curso = c.id_curso
            JOIN docentes d ON c.id_docente = d.id_docente
            WHERE m.id_estudiante = :id_estudiante
            ORDER BY p.nombre_programa, c.nombre_curso
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();
        
        // Agrupar los cursos por programa
        $programas = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Si el programa no está en el array, lo agregamos
            if (!isset($programas[$row['nombre_programa']])) {
                $programas[$row['nombre_programa']] = [
                    'duracion' => $row['duracion'],
                    'cursos' => []
                ];
            }
            
            // Añadimos el curso al programa correspondiente
            $programas[$row['nombre_programa']]['cursos'][] = [
                'nombre_curso' => $row['nombre_curso'],
                'descripcion' => $row['descripcion'],
                'docente_nombre' => $row['docente_nombre'],
                'docente_apellido' => $row['docente_apellido']
            ];
        }

        return $programas;
    }
}
?>
