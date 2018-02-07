<?php
class Comentario {
    private $table = "comentario";
    private $conexion;

    private $idComentario;
    private $contenido;
    private $fecha;
    private $editado;
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
    function getEditado() {
        return $this->editado;
    }

    /**
     * @param mixed $editado
     */
    function setEditado($editado) {
        $this->editado = $editado;
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

        $consulta = $this->conexion->prepare("INSERT INTO " . $this->table . " (contenido,fecha,editado,participante,proyecto)
                                        VALUES (:contenido,:fecha,:editado,:participante,:proyecto)");
        $save = $consulta->execute(array(
            "contenido" => $this->contenido,
            "fecha" => $this-> fecha,
            "editado" => $this->editado,
            "participante" => $this->participante,
            "proyecto" => $this->proyecto
        ));
        $this->conexion = null;

        return $save;
    }

    public function getAll(){
        /*Nota, este get all esta para coger todos los comentarios de un proyecto, tambien sacaremos datos del participante y del usuario (se filtra por el proyecto)*/
        $consulta = $this->conexion->prepare("SELECT c.idComentario,c.contenido,DATE_FORMAT(c.fecha,'%d/%m/%Y %H:%i')AS fechaHora,c.editado,c.participante,c.proyecto,p.usuario,p.proyecto,u.idUser,u.username
                                            FROM " . $this->table . " c 
                                            INNER JOIN participante p  
                                            ON c.participante = p.idParticipante 
                                            INNER JOIN usuario u 
                                            ON p.usuario = u.idUser 
                                            WHERE c.proyecto = :proyecto 
                                            ORDER BY fecha");
        $consulta->execute(array(
                "proyecto" => $this->proyecto)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;

    }

    public function getAllByUser($iduser){
        /*Nota, este get all esta para coger todos los comentarios de un usuario (se filtra por el usuario) y con un JOIN a Proyecto para sacar el nombre del proyecto */
        $consulta = $this->conexion->prepare("SELECT c.idComentario, c.contenido, DATE_FORMAT(c.fecha,'%d/%m/%Y') AS fecha, c.editado, c.participante, c.proyecto, p.nombre AS nombreProyecto
                                            FROM " . $this->table . " c 
                                            JOIN proyecto p 
                                            ON c.proyecto = p.idProyecto 
                                            WHERE participante 
                                            IN (SELECT DISTINCT idParticipante FROM participante WHERE usuario = :iduser)");
        $consulta->execute(array(
                "iduser" => $iduser)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;
    }

    public function getComentarioById(){

        $consulta = $this->conexion->prepare("SELECT idComentario,contenido,DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,editado,participante,proyecto FROM " . $this->table . " WHERE idComentario = :idComentario");
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
        $consulta = $this->conexion->prepare("UPDATE " . $this->table . " SET contenido = :contenido, fecha = :fecha, editado = :editado, participante = :participante, proyecto = :proyecto WHERE idComentario = :idComentario");
        $update = $consulta->execute(array(
            "idComentario" => $this->idComentario,
            "contenido" => $this->contenido,
            "fecha" => $this->fecha,
            "editado" => $this->editado,
            "participante" => $this->participante,
            "proyecto" => $this->proyecto
        ));
        $this->conexion = null;
        return $update;
    }

    public function remove(){

        $consulta = $this->conexion->prepare("DELETE FROM " . $this->table . " WHERE idComentario = :idComentario" );
        $consulta->execute(array(
                "idComentario" => $this->idComentario)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado = $consulta->rowCount();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }
}
?>
