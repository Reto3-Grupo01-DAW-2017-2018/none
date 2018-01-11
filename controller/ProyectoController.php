<?php
class ProyectoController extends BaseController{


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
                $this->crearDetalleBodegaView();
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
        
        //Creamos el objeto empleado
        $bodega=new Usuario(parent::conexion);
        
        //Conseguimos todos los empleados
        $bodega=$bodega->getAll();
       
        //Cargamos la vista index y le pasamos valores
        $this->view("index",$bodega);
    }
    
   /**
    * Crea un nuevo empleado a partir de los parámetros POST 
    * y vuelve a cargar el index.php.
    *
    */
    public function crear(){
        if(isset($_POST["nombre"])){
            
            //Creamos un usuario
            $bodega = new Usuario($this->conexion);
            $bodega->setNombre($_POST["nombre"]);
            $bodega->setDireccion($_POST["direccion"]);
            $bodega->setEmail($_POST["email"]);
            $bodega->setTelefono($_POST["telefono"]);
            $bodega->setContacto($_POST["contacto"]);
            $bodega->setFechafundacion($_POST["fechafundacion"]);
            $bodega->setRestaurante($_POST["restaurante"]);
            $bodega->setHotel($_POST["hotel"]);
            $save=$bodega->save();
        }
        header('Location: index.php');
    }

    public function deleteById(){
        if(isset($_GET["idbodega"])) {
            //borramos un usuario
            $bodega = new Usuario($this->conexion);
            $bodega->setIdbodega($_GET["idbodega"]);
            $filasborradas=$bodega->remove();
        }
        header('Location: index.php');
    }

    public function crearDetalleBodegaView(){
        if(isset($_GET["idbodega"])){
            //borramos un usuario
            $bodega = new Usuario($this->conexion);
            $bodega->setIdbodega($_GET["idbodega"]);
            $bodega=$bodega->getBodegaById();

            $this->view("detalle",$bodega);
        }
    }

    public function update(){
        if(isset($_POST["idbodega"])){
            //borramos un usuario
            $bodega = new Usuario($this->conexion);
            $bodega->setIdbodega($_POST["idbodega"]);
            $bodega->setNombre($_POST["nombre"]);
            $bodega->setDireccion($_POST["direccion"]);
            $bodega->setEmail($_POST["email"]);
            $bodega->setTelefono($_POST["telefono"]);
            $bodega->setContacto($_POST["contacto"]);
            $bodega->setFechafundacion($_POST["fechafundacion"]);
            $bodega->setRestaurante($_POST["restaurante"]);
            $bodega->setHotel($_POST["hotel"]);
            $update=$bodega->update();
        }
        header('Location: index.php');
    }
    
   /**
    * Crea la vista que le pasemos con los datos indicados.
    *
    */
    public function view($vista,$datos){
        $data = $datos;
        require_once  __DIR__ . "/../view/" . $vista . "View.php";
    }

}
?>
