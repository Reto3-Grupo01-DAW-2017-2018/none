<?php
require_once __DIR__ . "/BaseController.php";

class ComentarioController extends BaseController {
    public function __construct() {
        parent::__construct();
        require_once __DIR__. "/../model/Comentario.php";
        require_once __DIR__. "/../model/Participante.php";
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
        //Sacamos la participación del usuario mediante el proyecto y el usuario
        $participante=new Participante($this->conexion);
        $participante->setUsuario($_SESSION["user"]->idUser);
        $participante->setProyecto($_GET['proyecto']);
        $participante=$participante->getParticipanteByUsuarioAndProyecto();
        $idParticipante=$participante->idParticipante;
        //Conseguimos todas los comentarios (lista de los comentarios en BD)
        $listaComentarios = $comentario->getAll();
        if(count($listaComentarios)<1){
            $listaComentarios=null;
        }
        //Cargamos la vista comentariosView.php con la función 'view()' y le pasamos valores (usaremos 'comentarios')
        echo $this->twig->render("comentariosProyectoView.html",array(
            "user" => $_SESSION["user"],
            "comentariosProyecto" => $listaComentarios,
            "idProyecto" => $_GET['proyecto'],
            "nombreProyecto" => $_GET['nombreProyecto'],
            "responsable" => $_GET['responsable'],
            "participante" => $idParticipante,
            "titulo" => "Comentarios en el Proyecto - Nonecollab"
        ));
    }

    /*--------------------------------------------------------------
    Función que carga la lista de todos los comentarios del usuario logueado */
    public function buscarComentariosUser() {

        if(isset($_SESSION['user'])) {
            //Creamos el objeto 'Comentario'
            $comentarioUser = new Comentario($this->conexion);
            $listaComentariosUser = $comentarioUser->getAllByUser($_SESSION['user']->idUser);
            if(count($listaComentariosUser)<1){
                $listaComentariosUser=null;
            }
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

        //Construimos un nuevo objeto 'comentario' completo para mandar a BD
        $comentario = new Comentario($this->conexion);
        $comentario->setContenido($_POST['contenidoNuevoComentario']);
        $comentario->setFecha(date('Y-m-d H:i:s'));
        $comentario->setEditado('no');
        $comentario->setParticipante($_POST['participante']);
        $comentario->setProyecto($_POST['proyecto']);
        $insercion = $comentario->save();

        //Mandamos a la función que carga la vista comentariosProyectoView.html
        $this->listarComentariosProyecto();
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
        $comentarioModificar->setFecha(date('Y-m-d H:i:s'));
        $comentarioModificar->setEditado('si');
        $comentarioModificar->setParticipante($_POST['participante']);
        $comentarioModificar->setProyecto($_POST['proyecto']);
        $update = $comentarioModificar->update();

        //Mandamos a la función que carga la vista comentariosProyectoView.html
        $this->listarComentariosProyecto();
    }

    /*-------------------------------------------------------------------
    Función que manda a borrar el comentario seleccionado*/
    public function borrarComentario() {
        //Creamos el objeto solo con el Id y lo mandamos al modelo para borrar
        $comentarioBorrar = new Comentario($this->conexion);
        $comentarioBorrar ->setIdComentario($_GET['comentario']);
        $delete = $comentarioBorrar->remove();

        //Mandamos a la función listarComentariosProyecto para recargar la página comentariosProyectoView
        $this->listarComentariosProyecto();
    }

}
