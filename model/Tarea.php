<?php
class Tarea {
    private $table = "tarea";
    private $conexion;

    private $idTarea;
    private $nombreTarea;
    private $fechaInicioTarea;
    private $fechaFinTarea;
    private $urgente;
    private $editada;
    private $finalizada;
    private $participanteAsignado;
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
    function getEditada() {
        return $this->editada;
    }

    /**
     * @param mixed $editada
     */
    function setEditada($editada) {
        $this->editada = $editada;
    }
    
    /**
     * @return mixed
     */
    function getFinalizada() {
        return $this->finalizada;
    }
    
    /**
     * @param mixed $finalizada
     */
    function setFinalizada($finalizada) {
        $this->finalizada = $finalizada;
    }
    
    /**
     * @return mixed
     */
    function getParticipanteAsignado() {
        return $this->participanteAsignado;
    }

    /**
     * @param mixed $participanteAsignado
     */
    function setParticipanteAsignado($participanteAsignado) {
        $this->participanteAsignado = $participanteAsignado;
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

        $consulta = $this->conexion->prepare("INSERT INTO " . $this->table . " (nombreTarea,fechaInicioTarea,fechaFinTarea,urgente,editada,finalizada,participanteAsignado,participante,proyecto)
                                        VALUES (:nombreTarea,:fechaInicioTarea,:fechaFinTarea,:urgente,:editada,:finalizada,:participanteAsignado,:participante,:proyecto)");
        $save = $consulta->execute(array(
            "nombreTarea" => $this->nombreTarea,
            "fechaInicioTarea" => $this->fechaInicioTarea,
            "fechaFinTarea" => $this->fechaFinTarea,
            "urgente" => $this->urgente,
            "editada" => $this->editada,
            "finalizada" => $this->finalizada,
            "participanteAsignado" => $this->participanteAsignado,
            "participante" => $this->participante,
            "proyecto" => $this->proyecto
        ));
        $this->conexion = null;

        return $save;
    }

    public function getAll(){
        /*Nota, este get all esta para coger todos las tareas de un proyecto (se filtra por el proyecto)*/
        $consulta = $this->conexion->prepare("SELECT t.idTarea,t.nombreTarea,DATE_FORMAT(t.fechaInicioTarea,'%d/%m/%Y')AS fechaInicioTarea,DATE_FORMAT(t.fechaFinTarea,'%d/%m/%Y')AS fechaFinTarea,t.urgente,t.editada,t.finalizada,t.participanteAsignado,t.participante,t.proyecto,u.idUser,u.username,v.idUser AS idCreadorTarea,v.username AS creadorTarea
                                            FROM " . $this->table . " t 
                                            JOIN participante p 
                                            ON t.participanteAsignado = p.idParticipante 
                                            JOIN usuario u 
                                            ON p.usuario = u.idUser 
                                            JOIN participante q 
                                            ON t.participante = q.idParticipante
                                            JOIN usuario v 
                                            ON q.usuario = v.idUser 
                                            WHERE t.proyecto = :proyecto 
                                            ORDER BY t.urgente AND t.fechaFinTarea");
        $consulta->execute(array(
                "proyecto" => $this->proyecto)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;

    }

    public function getAllByUser($iduser){
        /*Nota, este get all esta para coger todas las tareas de un usuario (se filtra por el usuario)*/
        $consulta = $this->conexion->prepare("SELECT t.idTarea, t.nombreTarea, t.fechaInicioTarea, t.fechaFinTarea, t.urgente, t.editada, t.finalizada, t.participanteAsignado, t.participante, t.proyecto, p.nombre AS nombreProyecto, p.responsable
                                            FROM ". $this->table. " t  
                                            JOIN proyecto p 
                                            ON t.proyecto = p.idProyecto 
                                            WHERE participante 
                                            IN (SELECT DISTINCT idParticipante FROM participante WHERE usuario = :iduser)");
        $consulta->execute(array(
            "iduser" => $iduser
        ));
        /* Fetch all of the remaining rows in the result set */
        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;

    }

    public function getTareaById(){

        $consulta = $this->conexion->prepare("SELECT idTarea,nombreTarea,DATE_FORMAT(fechaInicioTarea,'%d/%m/%Y')AS fechaInicioTarea,DATE_FORMAT(fechaFinTarea,'%d/%m/%Y')AS fechaFinTarea,urgente,editada,finalizada,participanteAsignado,participante,proyecto FROM " . $this->table . " WHERE idTarea = :idTarea");
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
        $consulta = $this->conexion->prepare("UPDATE " . $this->table . " SET nombreTarea = :nombreTarea, fechaInicioTarea = :fechaInicioTarea, fechaFinTarea = :fechaFinTarea, urgente = :urgente, editada = :editada, finalizada = :finalizada, participanteAsignado = :participanteAsignado, proyecto = :proyecto WHERE idTarea = :idTarea");
        $update = $consulta->execute(array(
            "nombreTarea" => $this->nombreTarea,
            "fechaInicioTarea" => $this->fechaInicioTarea,
            "fechaFinTarea" => $this->fechaFinTarea,
            "urgente" => $this->urgente,
            "editada" => $this->editada,
            "finalizada" => $this->finalizada,
            "participanteAsignado" => $this->participanteAsignado,
            "proyecto" => $this->proyecto,
            "idTarea" => $this->idTarea
        ));
        $this->conexion = null;
        return $update;
    }
    
    public function updateFinalizada(){
        $consulta = $this->conexion->prepare("UPDATE " . $this->table . " SET finalizada = :finalizada WHERE idTarea = :idTarea");
        $update = $consulta->execute(array(
            "finalizada" => $this->finalizada,
            "idTarea" => $this->idTarea
        ));
        $resultado = $consulta->rowCount();
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
