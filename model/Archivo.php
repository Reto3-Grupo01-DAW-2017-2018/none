<?php
class Archivo {
    private $table = "archivo";
    private $conexion;

    private $idArchivo;
    private $nombreArchivo;
    private $rutaArchivo;
    private $participante;
    private $proyecto;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    /**
     * @return mixed
     */
    public function getIdArchivo()
    {
        return $this->idArchivo;
    }

    /**
     * @param mixed $idArchivo
     */
    public function setIdArchivo($idArchivo)
    {
        $this->idArchivo = $idArchivo;
    }

    /**
     * @return mixed
     */
    public function getNombreArchivo()
    {
        return $this->nombreArchivo;
    }

    /**
     * @param mixed $nombreArchivo
     */
    public function setNombreArchivo($nombreArchivo)
    {
        $this->nombreArchivo = $nombreArchivo;
    }

    /**
     * @return mixed
     */
    public function getRutaArchivo()
    {
        return $this->rutaArchivo;
    }

    /**
     * @param mixed $rutaArchivo
     */
    public function setRutaArchivo($rutaArchivo)
    {
        $this->rutaArchivo = $rutaArchivo;
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

        $consulta = $this->conexion->prepare("INSERT INTO " . $this->table . " (nombreArchivo,rutaArchivo,participante,proyecto)
                                        VALUES (:nombreArchivo,:rutaArchivo,:participante,:proyecto)");
        $save = $consulta->execute(array(
            "nombreArchivo" => $this->nombreArchivo,
            "rutaArchivo" => $this->rutaArchivo,
            "participante" => $this->participante,
            "proyecto" => $this->proyecto
        ));
        $this->conexion = null;

        return $save;
    }

    public function getAll(){
        /*Nota, este get all esta para coger todos los archivos de un proyecto (se filtra por el proyecto)*/
        $consulta = $this->conexion->prepare("SELECT idArchivo,nombreArchivo,rutaArchivo,participante,proyecto FROM " . $this->table . " WHERE proyecto = :proyecto");
        $consulta->execute(array(
                "proyecto" => $this->proyecto)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;

    }

    public function getAllByUser($iduser){
        /*Nota, este get all esta para coger todos los archivos de un usuario (se filtra por el usuario)*/
        $consulta = $this->conexion->prepare("SELECT idArchivo,nombreArchivo,rutaArchivo,participante,proyecto FROM " . $this->table . " WHERE participante IN (Select distinct idParticipante from participante where usuario = :iduser)");
        $consulta->execute(array(
                "iduser" => $iduser)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;

    }

    public function getArchivoById(){

        $consulta = $this->conexion->prepare("SELECT idArchivo,nombreArchivo,rutaArchivo,participante,proyecto FROM " . $this->table . " WHERE idArchivo = :idArchivo");
        $consulta->execute(array(
                "idArchivo" => $this->idArchivo)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado=$consulta->fetchObject();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }

    /**/

    public function update(){
        $consulta = $this->conexion->prepare("UPDATE " . $this->table . " SET nombreArchivo = :nombreArchivo, rutaArchivo = :rutaArchivo, participante = :participante, proyecto = :proyecto WHERE idArchivo = :idArchivo");
        $update = $consulta->execute(array(
            "nombreArchivo" => $this->nombreArchivo,
            "rutaArchivo" => $this->rutaArchivo,
            "participante" => $this->participante,
            "proyecto" => $this->proyecto,
            "idArchivo" => $this->idArchivo
        ));
        $this->conexion = null;
        return $update;
    }

    public function remove(){

        $consulta = $this->conexion->prepare("DELETE FROM " . $this->table . " WHERE idArchivo = :idArchivo" );
        $consulta->execute(array(
                "idArchivo" => $this->idArchivo)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado = $consulta->rowCount();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }
}
?>
