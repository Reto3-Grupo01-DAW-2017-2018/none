<?php
class BaseController{

    protected $conectar;
    protected $conexion;

    public function __construct() {
        require_once  __DIR__ . "/../core/Conectar.php";
        require_once __DIR__ . "/../vendor/autoload.php";
        require_once __DIR__ . "/../model/Usuario.php";

        $loader = new Twig_Loader_Filesystem(__DIR__."/../view");
        $this->twig = new Twig_Environment($loader, array('debug' => true));

        $this->conectar=new Conectar();
        $this->conexion=$this->conectar->conexion();
    }
    /*public function render($view){
        echo $this->twig->render($view);
    }*/

}
?>
