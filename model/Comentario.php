<?php
class Comentario {
    private $table = "comentario";
    private $conexion;

    private $idComentario;
    private $contenido;
    private $fecha;
    private $participante;
    private $proyecto;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    /**
     * @return mixed
     */
    public function getIdComentario()
    {
        return $this->idComentario;
    }

    /**
     * @param mixed $idComentario
     */
    public function setIdComentario($idComentario)
    {
        $this->idComentario = $idComentario;
    }

    /**
     * @return mixed
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * @param mixed $contenido
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
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

        $consulta = $this->conexion->prepare("INSERT INTO " . $this->table . " (contenido,fecha,participante,proyecto)
                                        VALUES (:contenido,SYSDATE(),:participante,:proyecto)");
        $save = $consulta->execute(array(
            "contenido" => $this->contenido,
            "participante" => $this->participante,
            "proyecto" => $this->proyecto
        ));
        $this->conexion = null;

        return $save;
    }

    public function getAll(){
        /*Nota, este get all esta para coger todos los comentarios de un proyecto (se filtra por el proyecto)*/
        $consulta = $this->conexion->prepare("SELECT idComentario,contenido,fecha,participante,proyecto FROM " . $this->table . " WHERE proyecto = :proyecto ORDER BY fecha");
        $consulta->execute(array(
                "proyecto" => $this->proyecto)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;

    }

    public function getComentarioById(){

        $consulta = $this->conexion->prepare("SELECT idComentario,contenido,fecha,participante,proyecto FROM " . $this->table . " WHERE idComentario = :idComentario");
        $consulta->execute(array(
                "idComentario" => $this->idComentario)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado=$consulta->fetchObject();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }

    /**/

    public function update(){
        $consulta = $this->conexion->prepare("UPDATE " . $this->table . " SET nombre = :nombre, descripcion = :descripcion, fechaInicioProyecto = :fechaInicioProyecto, responsable = :responsable WHERE idProyecto = :idProyecto");
        $update = $consulta->execute(array(
            "nombre" => $this->nombre,
            "descripcion" => $this->descripcion,
            "fechaInicioProyecto" => $this->anyo,
            "responsable" => $this->tipo,
            "idProyecto" => $this->idProyecto
        ));
        $this->conexion = null;
        return $update;
    }

    public function remove(){

        $consulta = $this->conexion->prepare("DELETE FROM " . $this->table . " WHERE idProyecto = :idProyecto" );
        $consulta->execute(array(
                "idProyecto" => $this->idProyecto)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado = $consulta->rowCount();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }
}
?>
