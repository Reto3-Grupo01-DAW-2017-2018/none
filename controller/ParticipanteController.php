<?php
require_once __DIR__ . "/BaseController.php";

class ParticipanteController extends BaseController {
    public function __construct() {
        parent::__construct();
        require_once __DIR__. "/../model/Participante.php";
        require_once __DIR__. "/../model/Proyecto.php";
        require_once __DIR__. "/../model/Usuario.php";
    }
    
    /*-------------------------------------------------------------------
    Función que, según la acción pasada en la url, manda a cada función correspondiente*/
    public function run($accion){
        switch($accion) { 
            case "listadoParticipantes" :
                $this->listarParticipantesProyecto();
                break;
            case "listadoProyectos" :
                $this->listadoProyectosParticipante();
                break;            
            case "aniadirParticipante" :
                $this->view('aniadirParticipante', "");
                break;
            case "nuevoParticipante" :
                $this->guardarParticipante();
                break;
            case "verDetalle" :
                $this->mostrarDatosParticipante();
                break;
            case "eliminar" :
                $this->borrarParticipante();
                break;
            default:
                $this->listarParticipantesProyecto();
                break;
        }
    }
    
    /*-------------------------------------------------------------------
    Función que carga la lista de participantes del proyecto indicado conseguida del modelo (Participante)*/
    public function listarParticipantesProyecto() {
        //Creamos el objeto 'Participante'
        $participanteProyecto = new Participante($this->conexion);
        $proyecto= new Proyecto($this->conexion);
        $noInvitados= new Usuario ($this->conexion);
        $idUs="";
        $usuarios=[];
        //die(var_dump($_GET['proyecto']));
        $participanteProyecto->setProyecto($_GET['proyecto']);
        $proyecto->setIdProyecto($_GET['proyecto']);
        $save=$proyecto->getProyectoById();
        //die(var_dump($save));
        
        
        //Conseguimos todos los participantes en el proyecto indicado
        $listaParticipantesProyecto = $participanteProyecto->getAllParticipantes();

        $noInvitados2=$noInvitados->getNoInvitados($_GET['proyecto']);
        
        //Cargamos la vista participantesProyectoView.php
        echo $this->twig->render('paticipantesProyectoView.html', array(
            'user' => $_SESSION['user'],
            'proyecto' => $save,
            'usuarios' => $listaParticipantesProyecto,
            'noInvitados' => $noInvitados2,
            'titulo' => 'PARTICIPANTES EN PROYECTO'
        ));
    }
    
    /*--------------------------------------------------------------
    Función para crear el nuevo participante (objeto 'Participante') y mandarlo a su clase ('Participante.php')*/
    public function guardarParticipante() {
            //Construimos un nuevo objeto 'participante' completo para mandar a BD
            $invitados=$_POST['invitados'];  

            foreach ($invitados as $invitado)
            {
                $participante = new Participante($this->conexion); 
                $participante->setUsuario($invitado);
                $participante->setProyecto($_POST['proyecto']);
                $insercion = $participante->save();
            }

        //Mandamos cargar la vista participantesProyectoView
        header('Location: index.php?controller=participante&action=listadoParticipantes&proyecto='.$_POST['proyecto']);
    }
    
    /*-------------------------------------------------------------------
    Función que manda a borrar el participante seleccionado*/
    public function borrarParticipante() {
        //Creamos el objeto solo con el Id y lo mandamos al modelo para borrar
        $participanteBorrar = new Participante($this->conexion);
        $participanteBorrar ->setIdParticipante($_GET['participante']);
        $delete = $participanteBorrar->remove();

        //Volvemos a cargar participantesProyectoView.php
        if($_GET['realizar']=="expulsar"){
            header('Location: index.php?controller=participante&action=listadoParticipantes&proyecto='.$_GET['proyecto']);
        }
        //Si no cargamos index.php
        elseif ($_GET['realizar']=="abandonar"){
            header('Location: index.php');
        }
    }
}
