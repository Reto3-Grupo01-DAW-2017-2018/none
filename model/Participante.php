<?php
class Participante {
    private $table = "participante";
    private $conexion;

    private $idParticipante;
    private $usuario;
    private $proyecto;

    public function __construct($conexion) {
		$this->conexion = $conexion;
    }

    /**
     * @return mixed
     */
    public function getIdParticipante()
    {
        return $this->idParticipante;
    }

    /**
     * @param mixed $idParticipante
     */
    public function setIdParticipante($idParticipante)
    {
        $this->idParticipante = $idParticipante;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
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

        $consulta = $this->conexion->prepare("INSERT INTO " . $this->table . " (idParticipante,usuario,proyecto)
                                        VALUES (:idParticipante,:usuario,:proyecto)");
        $save = $consulta->execute(array(
            "idParticipante" => $this->idParticipante,
            "usuario" => $this->usuario,
            "proyecto" => $this->proyecto
        ));
        $this->conexion = null;

        return $save;
    }

    public function getAll(){
    /*Nota, este get all esta para coger todos los participante de un proyecto (se filtra por el proyecto)*/
        $consulta = $this->conexion->prepare("SELECT idParticipante,usuario,proyecto FROM " . $this->table . " WHERE proyecto = :proyecto");
        $consulta->execute(array(
                "proyecto" => $this->proyecto)
        );

        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;

    }

    public function getParticipanteById(){

        $consulta = $this->conexion->prepare("SELECT idParticipante,usuario,proyecto FROM " . $this->table . " WHERE idParticipante = :idParticipante");
        $consulta->execute(array(
                "idUser" => $this->idUser)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado=$consulta->fetchObject();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }

    /*Para este caso no tiene sentido una funcion de update, o se agrega un participante al proyecto o se borra*/

    public function remove(){

        $consulta = $this->conexion->prepare("DELETE FROM " . $this->table . " WHERE idParticipante = :idParticipante" );
        $consulta->execute(array(
            "idParticipante" => $this->idParticipante)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado = $consulta->rowCount();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }
}
?>
