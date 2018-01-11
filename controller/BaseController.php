<?php
class BaseController{

    private $conectar;
    private $conexion;

    public function __construct() {
        require_once  __DIR__ . "/../core/Conectar.php";

        $this->conectar=new Conectar();
        $this->conexion=$this->conectar->conexion();
    }
}
?>
