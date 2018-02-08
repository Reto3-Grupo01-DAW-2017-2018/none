<?php
require_once __DIR__ . "/BaseController.php";


class ProyectoController extends BaseController {
    
    public function __construct() {        
        parent::__construct();
        require_once __DIR__. "/../model/Proyecto.php";
        require_once __DIR__. "/../model/Participante.php";
        require_once __DIR__ . "/../model/Tarea.php";
    }
    
    /*-------------------------------------------------------------------
    Función que, según la acción pasada en la url, manda a cada función correspondiente*/
    public function run($accion){
        switch($accion) { 
            case "index" :
                $this->proyectosUsuario();
                break;
            case "nuevoProyecto" :
                $this->anadirProyecto();
                break;
            case "verDetalle" :
                $this->mostrarDatosProyecto();
                break;
            case "eliminar" :
                $this->borrarProyecto();
                break;
            case "modificarProyecto" :
                $this->modificarDatosProyecto();
                break;
            default:
                $this->proyectosUsuario();
                break;
        }
    }

    /*-------------------------------------------------------------------
    Función que carga la lista de proyectos del usuario indicado en 'responsable', conseguida del modelo 'Proyecto.php'
    y la lista de los proyectos en los que participa el usuario, conseguida del modelo 'Participante.php'  */
    public function proyectosUsuario() {
        //Creamos el objeto 'Proyecto'
        $proyecto = new Proyecto($this->conexion);
        $proyecto->setResponsable($_SESSION['user']->idUser);

        //Conseguimos todas los proyectos del usuario através del modelo 'Proyecto.php'
        $listaProyectosUsuario = $proyecto->getAll();

        //Ahora conseguimos la lista de los proyectos en los que participa el usuario a través del modelo 'Participante.php'
        //Creamos el objeto Participante
        $participanteEnProyectos = new Participante($this->conexion);
        $participanteEnProyectos->setUsuario($_SESSION['user']->idUser);
        $listaProyectosParticipados = $participanteEnProyectos->getAll();

        //Filtramos para que solo muestre los proyectos de los que el usuario no es responsable (solo colabora)
        $listaProyectosSoloParticipando = array();
        for ($y = 0; $y < count($listaProyectosParticipados); $y++){
            if($listaProyectosParticipados[$y]['responsable'] != $listaProyectosParticipados[$y]['usuario']){
                array_push($listaProyectosSoloParticipando, $listaProyectosParticipados[$y]);
            }
        }
        //cargamos la lista de nombres de usuario para hacer la busqueda de usuarios para cuando añadamos proyectos nuevos
        $usuarios= new Usuario($this->conexion);
        $listaUsuarios=$usuarios->getAll();
        //Cargamos la vista boardView.php
        echo $this->twig->render("boardView.html",array(
            "user" => $_SESSION["user"],
            "proyectos" => $listaProyectosUsuario,
            'participando' => $listaProyectosSoloParticipando,
            "usuarios"=> $listaUsuarios,
            "titulo" => "Proyecto - Nonecollab"
        ));
    }

    /*--------------------------------------------------------------
    Función para crear nuevos proyectos y los participante iniciales */
    public function anadirProyecto()
    {
        $proyecto= new Proyecto($this->conexion);
        //creo proyecto
        $proyecto->setNombre($_GET["nombreProy"]);
        $proyecto->setDescripcion($_GET["descProy"]);
        $proyecto->setResponsable($_SESSION["user"]->idUser);
        $save=$proyecto->save();
        //compruebo si se ha creado cambiar por max id
        if($save!=0){
            $proyecto2=new Proyecto($this->conexion);
            $proyecto3=$proyecto2->getProyectoUltimo();

            //añado los participantes
            $invitados=$_GET['invitados'];
            foreach ($invitados as $invitado)
            {
                $invitado1=new Participante($this->conexion);
                $invitado1->setUsuario($invitado);
                $invitado1->setProyecto($proyecto3->idProyecto);
                //consulta para añadir participante al proyecto
                $invitado1->save();
            }
        }
        header('Location: index.php');
    }
    
    /*--------------------------------------------------------------
    Función manda al modelo para buscar los datos del proyecto seleccionado en el boton 'Ver Proyecto' */
    public function mostrarDatosProyecto() {
        if(isset($_GET["proyecto"])) {
            //Creamos el objeto solo con el Id y con esto sacaremos todos sus datos de BD
            $proyectoDetalle = new Proyecto($this->conexion);
            $proyectoDetalle->setIdProyecto($_GET['proyecto']);
            $profile = $proyectoDetalle->getProyectoById();

            //Creamos el objeto Tarea con el id del proyecto seleccionado
            $tareaProyecto = new Tarea($this->conexion);
            $tareaProyecto->setProyecto($proyectoDetalle->getIdProyecto());
            $listadoTareasProyecto = $tareaProyecto->getAll();

            //Buscamos los participantes en cada proyecto
            $participanteProyecto = new Participante($this->conexion);
            $participanteProyecto->setProyecto($proyectoDetalle->getIdProyecto());
            $listaParticipantesEnProyecto = $participanteProyecto->getAllParticipantes();

            $participante = $_GET['participante'];
            $origen = $_GET['origen'];

            //Mandamos a la vista 'proyectoView'
            echo $this->twig->render("proyectoView.html", array(
                "user" => $_SESSION["user"],
                "proyecto" => $profile,
                "idParticipante" => $participante,
                "tareas" => $listadoTareasProyecto,
                "participantes" => $listaParticipantesEnProyecto,
                "origen" => $origen,
                "titulo" => "Proyecto - Nonecollab"
            ));
        }
    }    
    
    /*-------------------------------------------------------------------
    Función que manda a modificar los datos del proyecto seleccionado*/
    public function modificarDatosProyecto() {        
        //Creamos el objeto completo y lo mandamos a actualizar al modelo
        $proyectoModificar = new Proyecto($this->conexion);
        $proyectoModificar->setIdProyecto($_POST['idProyecto']);
        $proyectoModificar->setNombre($_POST['nuevoNombre']);
        $proyectoModificar->setDescripcion($_POST['nuevoDescripcion']);
        $proyectoModificar->setFechaInicioProyecto($_POST['nuevoFechaInicioProyecto']);
        $proyectoModificar->setResponsable($_POST['nuevoResponsable']);
        $update = $proyectoModificar->update();

        //Volvemos a cargar proyectoView
        header('Location: index.php?controller=proyectos&action=verDetalle&idProyecto='. $proyectoModificar->getIdProyecto());
    }
    
    /*-------------------------------------------------------------------
    Función que manda a borrar el proyecto seleccionado*/
    public function borrarProyecto() {
        //Creamos el objeto solo con el Id y lo mandamos al modelo para borrar
        $proyectoBorrar = new Proyecto($this->conexion);
        $proyectoBorrar ->setIdProyecto($_GET['proyecto']);
        $delete = $proyectoBorrar->remove();

        $this->proyectosUsuario();
    }

}
