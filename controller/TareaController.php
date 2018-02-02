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
            case "tareasUsuario" :
                $this->buscarTareasUser();
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
                $this->buscarTareasUser();
                break;
        }
    }

    /*--------------------------------------------------------------
    Función que manda buscar las tareas del usuario logueado*/
    public function buscarTareasUser() {

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

        //Construimos un nuevo objeto 'tarea' completo para mandar a BD
        $tarea = new Tarea($this->conexion);
        $tarea->setNombreTarea($_POST['nombreTarea']);
        $tarea->setFechaInicioTarea($_POST['fechaInicioTarea']);
        $tarea->setFechaFinTarea($_POST['fechaFinTarea']);
        $tarea->setUrgente($_POST['urgente']);
        $tarea->setParticipante($_POST['encargadoTarea']);
        $tarea->setProyecto($_POST['proyecto']);
        $insercion = $tarea->save();

        //AQUI, SI QUEREMOS, SE PUEDE PONER UN MODAL AVISANDO DE QUE SE HA GUARDADO O PASAR DIRECTAMENTE A LA VISTA DEL PROYECTO

        //Recargamos la vista proyectoView.hml de nuevo
        header('Location: index.php?controller=proyecto&action=verDetalle&proyecto='. $_POST['proyecto']);
    }

    /*--------------------------------------------------------------
    Función manda al modelo para buscar los datos de la tarea seleccionada en el boton 'Ver Tarea' */
    public function mostrarDatosTarea() {
        //Creamos el objeto solo con el Id y con esto sacaremos todos sus datos de BD
        $tareaDetalle = new Tarea($this->conexion);
        $tareaDetalle ->setIdTarea($_GET['idTarea']);
        $profile = $tareaDetalle->getTareaById();

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
}
