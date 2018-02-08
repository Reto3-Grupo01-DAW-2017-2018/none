<?php
require_once __DIR__ . "/BaseController.php";

class TareaController extends BaseController {
    public function __construct() {
        parent::__construct();
        require_once __DIR__. "/../model/Tarea.php";
		require_once __DIR__. "/../model/Participante.php";
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
            case "guardarFinalizada" :
                $this->guardarFinalizada();
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
            if(count($listaTareasUser)<1){
                $listaTareasUser=null;
            }

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

        //Primero buscamos la participación del usuario en sesión en esta tarea
        $participacion = new Participante($this->conexion);
        $participacion->setUsuario($_SESSION['user']->idUser);
        $participacion->setProyecto($_POST['proyecto']);
        $idParticipacion = $participacion->getParticipanteByUsuarioAndProyecto();

        //Construimos un nuevo objeto 'tarea' completo para mandar a BD
        $tarea = new Tarea($this->conexion);
        $tarea->setNombreTarea($_POST['nombreTarea']);
        $tarea->setFechaInicioTarea($_POST['fechaInicioTarea']);
        $tarea->setFechaFinTarea($_POST['fechaFinTarea']);
        $tarea->setUrgente($_POST['urgente']);
        $tarea->setEditada('no');
        $tarea->setFinalizada('no');
        $tarea->setParticipanteAsignado($_POST['encargadoTarea']);
        $tarea->setParticipante($idParticipacion->idParticipante);
        $tarea->setProyecto($_POST['proyecto']);
        $insercion = $tarea->save();

        //Recargamos la vista proyectoView.hml de nuevo
        header('Location: index.php?controller=proyecto&action=verDetalle&proyecto='. $_POST['proyecto']. '&participante='. $_POST['encargadoTarea']. '&origen=proyecto');
    }

    /*--------------------------------------------------------------
    Función manda al modelo para buscar los datos de la tarea seleccionada */
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
        $tareaModificar->setIdTarea($_POST['editandoIdTarea']);
        $tareaModificar->setNombreTarea($_POST['editandoNombreTarea']);
        $tareaModificar->setFechaInicioTarea($_POST['editandoFechaInicioTarea']);
        $tareaModificar->setFechaFinTarea($_POST['editandoFechaFinTarea']);
        $tareaModificar->setUrgente($_POST['editandoUrgente']);        
        $tareaModificar->setEditada('si');
        $tareaModificar->setFinalizada($_POST['editandoFinalizarTarea']);        
        $tareaModificar->setParticipanteAsignado($_POST['editandoEncargadoTarea']);
        $tareaModificar->setParticipante($_POST['creadorTarea']);
        $tareaModificar->setProyecto($_POST['proyecto']);
        $update = $tareaModificar->update();

        //Volvemos a cargar proyectoView.php
        header('Location: index.php?controller=proyecto&action=verDetalle&proyecto='. $_POST['proyecto']. '&participante='. $_POST['creadorTarea'] .'&origen=proyecto');

    }

    /*-------------------------------------------------------------------
    Función que manda marcar como finalizada la tarea seleccionada*/
    public function guardarFinalizada() {
        //Creamos el objeto solo con el Id y lo mandamos al modelo para borrar
        if(isset($_POST['idTarea'])&&isset($_POST['finalizada'])){
            $tarea=new Tarea($this->conexion);
            $tarea->setFinalizada($_POST['finalizada']);
            $tarea->setIdTarea($_POST['idTarea']);
            $resultado=$tarea->updateFinalizada();
            if($resultado<1){
                echo "0";
            }else{
                echo "1";
            }
        }
    }
    
    /*-------------------------------------------------------------------
    Función que manda a borrar la tarea seleccionada*/
    public function borrarTarea() {
        //Creamos el objeto solo con el Id y lo mandamos al modelo para borrar
        $tareaBorrar = new Tarea($this->conexion);
        $tareaBorrar ->setIdTarea($_GET['tarea']);
        $delete = $tareaBorrar->remove();

        //Volvemos a cargar proyectoView.php
        header('Location: index.php?controller=proyecto&action=verDetalle&proyecto='. $_GET['proyecto']. '&participante='. $_GET['participante']. '&origen=proyecto');
    }
}
