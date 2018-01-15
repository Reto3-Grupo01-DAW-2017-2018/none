<?php
class Proyecto {
    private $table = "proyecto";
    private $conexion;

    private $idProyecto;
    private $nombre;
    private $descripcion;
    private $fechaInicioProyecto;
    private $responsable;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    /**
     * @return mixed
     */
    public function getIdProyecto()
    {
        return $this->idProyecto;
    }

    /**
     * @param mixed $idProyecto
     */
    public function setIdProyecto($idProyecto)
    {
        $this->idProyecto = $idProyecto;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioProyecto()
    {
        return $this->fechaInicioProyecto;
    }

    /**
     * @param mixed $fechaInicioProyecto
     */
    public function setFechaInicioProyecto($fechaInicioProyecto)
    {
        $this->fechaInicioProyecto = $fechaInicioProyecto;
    }

    /**
     * @return mixed
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * @param mixed $responsable
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    }

    public function save(){

        $consulta = $this->conexion->prepare("INSERT INTO " . $this->table . " (nombre,descripcion,fechaInicioProyecto,responsable)
                                        VALUES (:nombre,:descripcion,SYSDATE(),:responsable)");
        $save = $consulta->execute(array(
            "nombre" => $this->nombre,
            "descripcion" => $this->descripcion,
            "responsable" => $this->responsable
        ));
        $this->conexion = null;

        return $save;
    }

    public function getAll(){

        $consulta = $this->conexion->prepare("SELECT idProyecto,nombre,descripcion,fechaInicioProyecto,responsable FROM " . $this->table . " WHERE responsable = :responsable ORDER BY fechaInicioProyecto");
        $consulta->execute(array(
                "responsable" => $this->responsable)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;

    }

    public function getProyectoById(){

        $consulta = $this->conexion->prepare("SELECT idProyecto,nombre,descripcion,fechaInicioProyecto,responsable FROM " . $this->table . " WHERE idProyecto = :idProyecto");
        $consulta->execute(array(
                "idProyecto" => $this->idProyecto)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado=$consulta->fetchObject();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }

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
