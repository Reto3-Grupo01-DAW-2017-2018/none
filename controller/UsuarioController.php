<?php
class UsuarioController{
    /**
     * Ejecuta la acción correspondiente.
     *
     */
    public function run($accion){
        switch($accion)
        {
            case "index" :
                $this->index();
                break;
            case "alta" :
                $this->crear();
                break;
            case "baja" :
                $this->deleteById();
                break;
            case "update" :
                $this->update();
                break;
            case "detalle" :
                $this->crearDetalleVinoView();
                break;
            default:
                $this->index();
                break;
        }
    }

    /**
     * Carga la página principal de empleados con la lista de
     * empleados que consigue del modelo.
     *
     */
    public function index(){
        if(!isset($_SESSION["user"])){
            /*Aqui cargamos la vista de bienvenida/login*/
            echo $this->twig->render("loginView.html");
        }else{
            /*cargamos la vista de la lista de proyectos*/
            echo $this->twig->render("listaProyectos.html");
        }

    }

    /**
     * Crea un nuevo empleado a partir de los parámetros POST
     * y vuelve a cargar el index.php.
     *
     */
    public function crear(){
        if(isset($_POST["nombre"])){

            //Creamos un usuario
            $vino = new Proyecto($this->conexion);
            $vino->setNombre($_POST["nombre"]);
            $vino->setDescripcion($_POST["descripcion"]);
            $vino->setAnyo($_POST["anyo"]);
            $vino->setTipo($_POST["tipo"]);
            $vino->setAlcohol($_POST["alcohol"]);
            $vino->setBodega($_GET["idbodega"]);
            $save=$vino->save();
        }
        header('Location: index.php');
    }

    public function deleteById(){
        if(isset($_GET["idvino"])) {
            //borramos un usuario
            $vino = new Proyecto($this->conexion);
            $vino->setIdvino($_GET["idvino"]);
            $filasborradas=$vino->remove();
        }
        header('Location: index.php');
    }

    public function crearDetalleVinoView(){
        if(isset($_GET["idvino"])){
            //borramos un usuario
            $vino = new Proyecto($this->conexion);
            $vino->setIdvino($_GET["idvino"]);
            $vino=$vino->getVinoById();

            //$this->view("detalleVino",$vino);
            echo $this->twig->render("detalleVinoView.html", array(
                    "idvino"=>$vino->getIdvino(),
                    "nombre"=>$vino->getNombre(),
                    "descripcion"=>$vino->getDescripcion(),
                    "anyo"=>$vino->getAnyo(),
                    "tipo"=>$vino->getTipo(),
                    "alcohol"=>$vino->getAlcohol(),
                    "bodega"=>$vino->getBodega()
                    ));
        }
    }

    public function update(){
        if(isset($_POST["idvino"])){
            //borramos un usuario
            $vino = new Proyecto($this->conexion);
            $vino->setIdvino($_POST["idvino"]);
            $vino->setNombre($_POST["nombre"]);
            $vino->setDescripcion($_POST["descripcion"]);
            $vino->setAnyo($_POST["anyo"]);
            $vino->setTipo($_POST["tipo"]);
            $vino->setAlcohol($_POST["alcohol"]);
            $vino->setBodega($_POST["bodega"]);
            $update=$vino->update();
        }
        header('Location: index.php');
    }
}
?>
