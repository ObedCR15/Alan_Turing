<?php
class EstudianteMatriculaModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Retorna true si el estudiante está matriculado (al menos 1 registro)
    public function estaMatriculado(int $idEstudiante): bool {
        $sql = "SELECT COUNT(*) FROM matriculas WHERE id_estudiante = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    // Obtiene los datos completos del estudiante
    public function obtenerDatosMatricula(int $idEstudiante): ?array {
        $sql = "SELECT 
                    e.nombre, e.apellido, e.edad, e.DNI,
                    m.fecha_matricula, m.monto_matricula, m.estado_matricula,
                    p.nombre_programa, p.costo
                FROM estudiantes e
                JOIN matriculas m ON e.id_estudiante = m.id_estudiante
                JOIN programas p ON m.id_programa = p.id_programa
                WHERE e.id_estudiante = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);

        return $datos ?: null;
    }
}
