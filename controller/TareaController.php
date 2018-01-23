<?php
require_once __DIR__ . "/BaseController.php";

class TareaController extends BaseController {
    public function __construct() {
        parent::__construct();
        require_once __DIR__. "/../model/Tarea.php";
    }
    
    /*-------------------------------------------------------------------
    Función que, según la acción pasada en la url, manda a cada función correspondiente*/
    public function run($accion){
        switch($accion) { 
            case "index" :
                $this->index();
                break;
            case "tareasUsuario" :
                $this->buscarTareasUser();
                break;
            case "aniadirTarea" :
                $this->view('aniadirTarea', "");
                break;
            case "nuevaTarea" :
                $this->guardarTarea();
                break;
            case "verDetalle" :
                $this->mostrarDatosTarea();
                break;
            case "eliminar" :
                $this->borrarTarea();
                break;
            case "modificarTarea" :
                $this->modificarDatosTarea();
                break;
            default:
                $this->index();
                break;
        }
    }
    
    /*-------------------------------------------------------------------
    Función que carga la lista de tareas del proyecto indicado, conseguida del modelo (Tarea)*/
    public function index() {
        //DE MOMENTO NO SE USA, CREO
        
        //Creamos el objeto 'Tarea'
        /*$tarea = new Tarea($this->conexion);
        $tarea->setProyecto($_GET['proyecto']);
        $listaTareas = $tarea->getAll();
        
        //Ahora conseguiremos los datos del usuario que tiene asiganada cada tarea
        $listaUsuariosTareas = array();
        require_once __DIR__. '/../model/Usuario.php';
        foreach ($listaTareas as $tarea) {
            $usuarioTarea = new Usuario($this->conexion);
            $usuarioTarea->setIdUser($tarea->usuario);
            array_push($listaUsuariosTareas, $usuarioTarea->getUsuarioByParticipante());
        }
        
        ///HACER LO DE ARRIBA PARA MOSTRAR EL NOMBRE DEL USUARIO QUEE TIENE ASIGNADA CADA TAREA
        
        //Retornamos la info necesaria para mostrarla en la vista 'proyectoView.php'
        //return $listaTareas;
        
        //Cargamos la vista tareasView.php con la función 'view()' y le pasamos valores (usaremos 'tareas')
        $this->view('tareas', array(
            'tareas' => $listaTareas,
            'usuarios' => $listaUsuariosTareas,
            'titulo' => 'TAREAS'
        ));*/
    }
    
    /*--------------------------------------------------------------
    Función que manda buscar las tareas del usuario*/
    public function buscarTareasUser() {

        //Ahora buscamos las tareas en cada una de las participaciones del usuario, del modelo 'Tarea.php'
        if(isset($_SESSION["user"])){
            $tareaUser = new Tarea($this->conexion);
            $listaTareasUser=$tareaUser->getAllByUser($_SESSION["user"]->idUser);

        echo $this->twig->render("tareasUserView.html",array(
            "user" => $_SESSION["user"],
            "tareasUser" => $listaTareasUser,
            "titulo" => "Tareas del Usuario - Nonecollab"
        ));
        }
    }
    
    /*--------------------------------------------------------------
    Función para crear la nueva tarea (objeto 'Tarea') y mandarlo a su clase ('Tarea.php')*/
    public function guardarTarea() {
        if(isset($_POST['guardar'])) {
            //Construimos un nuevo objeto 'tarea' completo para mandar a BD            
            $tarea = new Tarea($this->conexion);            
            $tarea->setNombreTarea($_POST['nombreTarea']);
            $tarea->setFechaInicioTarea($_POST['fechaInicioTarea']);
            $tarea->setFechaFinTarea($_POST['fechaFinTarea']);
            $tarea->setUrgente($_POST['urgente']);
            $tarea->setParticipante($_POST['participante']);
            $tarea->setProyecto($_POST['proyecto']);            
            $insercion = $tarea->save();
        }
        
        //AQUÍ HABRÁ QUE CARGAR OTRA VISTA, NO LA INDICADA 'index.php' (ARREGLARLO)
        //Mandamos a la vista principal
        header('Location: index.php');
    }
    
    /*--------------------------------------------------------------
    Función manda al modelo para buscar los datos de la tarea seleccionada en el boton 'Ver Tarea' */
    public function mostrarDatosTarea() {
        //Creamos el objeto solo con el Id y con esto sacaremos todos sus datos de BD
        $tareaDetalle = new Tarea($this->conexion);
        $tareaDetalle ->setIdTarea($_GET['idTarea']);
        $profile = $tareaDetalle->getTareaById();
        
        //Mandamos a la función view() para crear la vista 'detalleTareaView'
        /*$this->view('tarea',array(
            "tarea"=>$profile,
            "titulo" => "DETALLE TAREA"
        ));*/
        echo $this->twig->render("tareasUserView.html",array(
            "user" => $_SESSION["user"],
            "tarea"=>$profile,
            "titulo" => "Detalle Tarea - Nonecollab"
        ));
    }    
    
    /*-------------------------------------------------------------------
    Función que manda a modificar los datos de la tarea seleccionada*/
    public function modificarDatosTarea() {        
        //Creamos el objeto completo y lo mandamos a actualizar al modelo
        $tareaModificar = new Tarea($this->conexion);
        $tareaModificar->setIdTarea($_POST['idTarea']);
        $tareaModificar->setNombreTarea($_POST['nombreTarea']);
        $tareaModificar->setFechaInicioTarea($_POST['fechaInicioTarea']);
        $tareaModificar->setFechaFinTarea($_POST['fechaFinTarea']);
        $tareaModificar->setUrgente($_POST['urgente']);
        $tareaModificar->setParticipante($_POST['participante']);        
        $tareaModificar->setProyecto($_POST['proyecto']);        
        $update = $tareaModificar->update();
        
        //Volvemos a cargar index.php pasándole los datos del 'controller', 'action' y el id de la tarea para cargar de nuevo 'detalleTareaView.php' 
        header('Location: index.php?controller=tareas&action=verDetalle&idTarea='. $tareaModificar->getIdTarea());
    }
    
    /*-------------------------------------------------------------------
    Función que manda a borrar la tarea seleccionada*/
    public function borrarTarea() {
        //Creamos el objeto solo con el Id y lo mandamos al modelo para borrar
        $tareaBorrar = new Tarea($this->conexion);
        $tareaBorrar ->setIdTarea($_GET['idTarea']);
        $delete = $tareaBorrar->remove();        
        
        //AQUÍ HABRÁ QUE CARGAR OTRA VISTA, NO LA INDICADA 'index.php' (ARREGLARLO)
        //Volvemos a cargar index.php
        header('Location: index.php');
    }
    
    /*------------------------------------------------------------------
    Función para crear la vista con el nombre que le pasemos y con los datos que le indiquemos*/
    public function view($vista, $datos) {
        $data = $datos;
        
        require_once __DIR__. '/../views/'. $vista. 'View.php';        
    }
}
