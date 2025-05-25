<?php
class GestionarProgramasModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener todos los programas con sus cursos
    public function obtenerProgramasConCursos() {
        $query = "SELECT p.id_programa, p.nombre_programa, p.descripcion, p.costo, p.pension, 
                         p.fecha_inicio, p.fecha_fin, p.duracion, p.pensiones,
                         GROUP_CONCAT(c.nombre_curso) AS cursos
                  FROM programas p
                  LEFT JOIN programa_curso pc ON p.id_programa = pc.id_programa
                  LEFT JOIN cursos c ON pc.id_curso = c.id_curso
                  GROUP BY p.id_programa";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar un programa
    public function eliminarPrograma($id_programa) {
        try {
            // Comenzamos la transacción
            $this->pdo->beginTransaction();

            // Primero, eliminamos las relaciones en la tabla intermedia 'programa_curso'
            $stmt1 = $this->pdo->prepare("DELETE FROM programa_curso WHERE id_programa = :id");
            $stmt1->bindParam(':id', $id_programa);
            $stmt1->execute();

            // Luego, eliminamos el programa de la tabla 'programas'
            $stmt2 = $this->pdo->prepare("DELETE FROM programas WHERE id_programa = :id");
            $stmt2->bindParam(':id', $id_programa);
            $stmt2->execute();

            // Confirmamos la transacción
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            // Si hay algún error, revertimos la transacción
            $this->pdo->rollBack();
            return false;
        }
    }
}
?>
