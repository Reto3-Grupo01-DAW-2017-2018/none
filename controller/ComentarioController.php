<?php
require_once __DIR__ . "/BaseController.php";

class ComentarioController extends BaseController {
    public function __construct() {
        parent::__construct();
        require_once __DIR__. "/../model/Comentario.php";
    }

    /*-------------------------------------------------------------------
    Función que, según la acción pasada en la url, manda a cada función correspondiente*/
    public function run($accion){
        switch($accion) {
            case "comentariosPorProyecto" :
                $this->listarComentariosProyecto();
                break;
            case "comentariosUsuario" :
                $this->buscarComentariosUser();
                break;
            case "aniadirComentario" :
                $this->view('aniadirComentario', "");
                break;
            case "nuevoComentario" :
                $this->guardarComentario();
                break;
            case "verDetalle" :
                $this->mostrarDatosComentario();
                break;
            case "eliminar" :
                $this->borrarComentario();
                break;
            case "modificarComentario" :
                $this->modificarDatosComentario();
                break;
            default:
                $this->listarComentariosProyecto();
                break;
        }
    }

    /*-------------------------------------------------------------------
    Función que carga la lista de comentarios del proyecto indicado, conseguida del modelo (Archivo)*/
    public function listarComentariosProyecto() {
        //Creamos el objeto 'Comentario'
        $comentario = new Comentario($this->conexion);
        $comentario->setProyecto($_GET['proyecto']);

        //Conseguimos todas los comentarios (lista de los comentarios en BD)
        $listaComentarios = $comentario->getAll();

        //Cargamos la vista comentariosView.php con la función 'view()' y le pasamos valores (usaremos 'comentarios')
        $this->view('comentariosProyecto', array(
            'comentarios' => $listaComentarios,
            'titulo' => 'COMENTARIOS'
        ));
    }

    /*--------------------------------------------------------------
    Función que carga la lista de todos los comentarios del usuario logueado */
    public function buscarComentariosUser() {

        if(isset($_SESSION['user'])) {
            //Creamos el objeto 'Comentario'
            $comentarioUser = new Comentario($this->conexion);
            $listaComentariosUser = $comentarioUser->getAllByUser($_SESSION['user']->idUser);

            //Mandamos crear la vista 'comentariosUserView' usando twig
            echo $this->twig->render("comentariosUserView.html",array(
                "user" => $_SESSION["user"],
                "comentariosUser" => $listaComentariosUser,
                "titulo" => "Comentarios del Usuario - Nonecollab"
            ));
        }
    }

    /*--------------------------------------------------------------
    Función para crear el nuevo comentario (objeto 'Comentario') y mandarlo a su clase ('Comentario.php')*/
    public function guardarComentario() {
        if(isset($_POST['guardar'])) {
            //Construimos un nuevo objeto 'comentario' completo para mandar a BD            
            $comentario = new Comentario($this->conexion);
            $comentario->setContenido($_POST['contenido']);
            $comentario->setFecha($_POST['fecha']);
            $comentario->setEditado($_POST['editado']);
            $comentario->setParticipante($_POST['participante']);
            $comentario->setProyecto($_POST['proyecto']);

            $insercion = $comentario->save();
        }

        //AQUÍ HABRÁ QUE CARGAR OTRA VISTA, NO LA INDICADA 'index.php' (ARREGLARLO)
        //Mandamos a la vista principal
        header('Location: index.php');
    }

    /*--------------------------------------------------------------
    Función manda al modelo para buscar los datos del comentario seleccionado en el boton 'Ver Comentario' */
    public function mostrarDatosComentario() {
        //Creamos el objeto solo con el Id y con esto sacaremos todos sus datos de BD
        $comentarioDetalle = new Comentario($this->conexion);
        $comentarioDetalle ->setIdComentario($_GET['idComentario']);
        $profile = $comentarioDetalle->getComentarioById();

        //Mandamos a la función view() para crear la vista 'detalleComentarioView'
        $this->view('detalleComentario',array(
            "comentario"=>$profile,
            "titulo" => "DETALLE COMENTARIO"
        ));
    }

    /*-------------------------------------------------------------------
    Función que manda a modificar los datos del comentario seleccionado*/
    public function modificarDatosComentario() {
        //Creamos el objeto completo y lo mandamos a actualizar al modelo
        $comentarioModificar = new Comentario($this->conexion);
        $comentarioModificar->setIdComentario($_POST['idComentario']);
        $comentarioModificar->setContenido($_POST['nuevoContenido']);
        $comentarioModificar->setFecha($_POST['nuevoFecha']);
        $comentarioModificar->setEditado($_POST['nuevoEditado']);
        $comentarioModificar->setParticipante($_POST['nuevoParticipante']);
        $comentarioModificar->setProyecto($_POST['nuevoProyecto']);
        $update = $comentarioModificar->update();

        //Volvemos a cargar index.php pasándole los datos del 'controller', 'action' y el id del comentario para cargar de nuevo 'detalleComentarioView.php' 
        header('Location: index.php?controller=comentarios&action=verDetalle&idComentario='. $comentarioModificar->getIdComentario());
    }

    /*-------------------------------------------------------------------
    Función que manda a borrar el comentario seleccionado*/
    public function borrarComentario() {
        //Creamos el objeto solo con el Id y lo mandamos al modelo para borrar
        $comentarioBorrar = new Comentario($this->conexion);
        $comentarioBorrar ->setIdComentario($_GET['idComentario']);
        $delete = $comentarioBorrar->remove();

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
