<?php
require_once __DIR__ . "/BaseController.php";

class ParticipanteController extends BaseController {
    public function __construct() {
        parent::__construct();
        require_once __DIR__. "/../model/Participante.php";
    }
    
    /*-------------------------------------------------------------------
    Función que, según la acción pasada en la url, manda a cada función correspondiente*/
    public function run($accion){
        switch($accion) { 
            case "participantesPorProyecto" :
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
        $participanteProyecto->setProyecto($_GET['proyecto']);
        
        //Conseguimos todos los participantes en el proyecto indicado
        $listaParticipantesProyecto = $participanteProyecto->getAllParticipantes();
        
        //Cargamos la vista participantesEnProyectoView.php con la función 'view()' y le pasamos valores (usaremos 'participantesProyecto')
        $this->view('participantesProyecto', array(
            'participantesProyecto' => $listaParticipantesProyecto,
            'titulo' => 'PARTICIPANTES EN PROYECTO'
        ));
    }
    
    /*-------------------------------------------------------------------
    Función que carga la lista de proyectos en los que está trabajando el participante indicado, conseguida del modelo (Participante)*/
    public function listadoProyectosParticipante() {
        //Creamos el objeto 'Participante'
        $proyectoParticipando = new Participante($this->conexion);
        $proyectoParticipando->setUsuario($_GET['usuario']);
        
        //Conseguimos todos los proyectos en los que está trabajando el participante indicado
        $listaProyectosParticipante = $proyectoParticipando->getAllProyectos();
        
        //Cargamos la vista proyectosParticipanteView.php con la función 'view()' y le pasamos valores (usaremos 'proyectosParticipante')
        $this->view('proyectosParticipante', array(
            'proyectosParticipante' => $listaProyectosParticipante,
            'titulo' => 'PROYECTOS DEL PARTICIPANTE'
        ));
    }    
    
    /*--------------------------------------------------------------
    Función para crear el nuevo participante (objeto 'Participante') y mandarlo a su clase ('Participante.php')*/
    public function guardarParticipante() {
        if(isset($_POST['guardar'])) {
            //Construimos un nuevo objeto 'participante' completo para mandar a BD            
            $participante = new Participante($this->conexion);            
            $participante->setUsuario($_POST['usuario']);
            $participante->setProyecto($_POST['proyecto']);
            $insercion = $participante->save();
        }
        
        //AQUÍ HABRÁ QUE CARGAR OTRA VISTA, NO LA INDICADA 'index.php' (ARREGLARLO)
        //Mandamos a la vista principal
        header('Location: index.php');
    }
    
    /*--------------------------------------------------------------
    Función manda al modelo para buscar los datos del participante seleccionado en el boton 'Ver Participante' */
    public function mostrarDatosParticipante() {
        //Creamos el objeto solo con el Id y con esto sacaremos todos sus datos de BD
        $participanteDetalle = new Participante($this->conexion);
        $participanteDetalle ->setIdParticipante($_GET['idParticipante']);
        $profile = $participanteDetalle->getParticipanteById();
        
        //Mandamos a la función view() para crear la vista 'detalleParticipanteView'
        $this->view('detalleParticipante',array(
            "participante"=>$profile,
            "titulo" => "DETALLE PARTICIPANTE"
        ));
    }
    
    /*-------------------------------------------------------------------
    Función que manda a borrar el participante seleccionado*/
    public function borrarParticipante() {
        //Creamos el objeto solo con el Id y lo mandamos al modelo para borrar
        $participanteBorrar = new Participante($this->conexion);
        $participanteBorrar ->setIdParticipante($_GET['idParticipante']);
        $delete = $participanteBorrar->remove();
        
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
