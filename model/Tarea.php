<?php
class Tarea {
    private $table = "tarea";
    private $conexion;

    private $idTarea;
    private $nombreTarea;
    private $fechaInicioTarea;
    private $fechaFinTarea;
    private $urgente;
    private $participante;
    private $proyecto;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    /**
     * @return mixed
     */
    public function getIdTarea()
    {
        return $this->idTarea;
    }

    /**
     * @param mixed $idTarea
     */
    public function setIdTarea($idTarea)
    {
        $this->idTarea = $idTarea;
    }

    /**
     * @return mixed
     */
    public function getNombreTarea()
    {
        return $this->nombreTarea;
    }

    /**
     * @param mixed $nombreTarea
     */
    public function setNombreTarea($nombreTarea)
    {
        $this->nombreTarea = $nombreTarea;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioTarea()
    {
        return $this->fechaInicioTarea;
    }

    /**
     * @param mixed $fechaInicioTarea
     */
    public function setFechaInicioTarea($fechaInicioTarea)
    {
        $this->fechaInicioTarea = $fechaInicioTarea;
    }

    /**
     * @return mixed
     */
    public function getFechaFinTarea()
    {
        return $this->fechaFinTarea;
    }

    /**
     * @param mixed $fechaFinTarea
     */
    public function setFechaFinTarea($fechaFinTarea)
    {
        $this->fechaFinTarea = $fechaFinTarea;
    }

    /**
     * @return mixed
     */
    public function getUrgente()
    {
        return $this->urgente;
    }

    /**
     * @param mixed $urgente
     */
    public function setUrgente($urgente)
    {
        $this->urgente = $urgente;
    }

    /**
     * @return mixed
     */
    public function getParticipante()
    {
        return $this->participante;
    }

    /**
     * @param mixed $participante
     */
    public function setParticipante($participante)
    {
        $this->participante = $participante;
    }

    /**
     * @return mixed
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }

    /**
     * @param mixed $proyecto
     */
    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function save(){

        $consulta = $this->conexion->prepare("INSERT INTO " . $this->table . " (nombreTarea,fechaInicioTarea,fechaFinTarea,urgente,participante,proyecto)
                                        VALUES (:nombreTarea,:fechaInicioTarea,:fechaFinTarea,:urgente,:participante,:proyecto)");
        $save = $consulta->execute(array(
            "nombreTarea" => $this->nombreTarea,
            "fechaInicioTarea" => $this->fechaInicioTarea,
            "fechaFinTarea" => $this->fechaFinTarea,
            "urgente" => $this->urgente,
            "participante" => $this->participante,
            "proyecto" => $this->proyecto
        ));
        $this->conexion = null;

        return $save;
    }

    public function getAll(){
        /*Nota, este get all esta para coger todos las tareas de un proyecto (se filtra por el proyecto)*/
        $consulta = $this->conexion->prepare("SELECT idTarea,nombreTarea,fechaInicioTarea,fechaFinTarea,urgente,participante,proyecto FROM " . $this->table . " WHERE proyecto = :proyecto ORDER BY urgente AND fechaFinTarea");
        $consulta->execute(array(
                "proyecto" => $this->proyecto)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;

    }

    public function getTareaById(){

        $consulta = $this->conexion->prepare("SELECT idTarea,nombreTarea,fechaInicioTarea,fechaFinTarea,urgente,participante,proyecto FROM " . $this->table . " WHERE idTarea = :idTarea");
        $consulta->execute(array(
                "idTarea" => $this->idTarea)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado=$consulta->fetchObject();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }

    /**/

    public function update(){
        $consulta = $this->conexion->prepare("UPDATE " . $this->table . " SET nombreTarea = :nombreTarea, fechaInicioTarea = :fechaInicioTarea, fechaFinTarea = :fechaFinTarea, urgente = :urgente, participante = :participante, proyecto = :proyecto WHERE idTarea = :idTarea");
        $update = $consulta->execute(array(
            "nombreTarea" => $this->nombreTarea,
            "fechaInicioTarea" => $this->fechaInicioTarea,
            "fechaFinTarea" => $this->fechaFinTarea,
            "urgente" => $this->urgente,
            "participante" => $this->participante,
            "proyecto" => $this->proyecto,
            "idTarea" => $this->idTarea
        ));
        $this->conexion = null;
        return $update;
    }

    public function remove(){

        $consulta = $this->conexion->prepare("DELETE FROM " . $this->table . " WHERE idTarea = :idTarea" );
        $consulta->execute(array(
                "idTarea" => $this->idTarea)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado = $consulta->rowCount();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }
}
?>
