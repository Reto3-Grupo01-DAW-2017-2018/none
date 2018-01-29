<?php
require_once __DIR__ . "/BaseController.php";


class ProyectoController extends BaseController {
    
    public function __construct() {        
        parent::__construct();
        require_once __DIR__. "/../model/Proyecto.php";
    }
    
    /*-------------------------------------------------------------------
    Función que, según la acción pasada en la url, manda a cada función correspondiente*/
    public function run($accion){
        switch($accion) { 
            case "index" :
                $this->proyectosUsuario();
                break;
            case "nuevoProyecto" :
                $this->guardarProyecto();
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

        //HE COMENTADO LO SIGUIENTE (HARÁ FALTA LUEGO) (VENDRA EN SESION)
        //$proyecto->setResponsable($_GET['responsable']);

        //HE PUESTO LO SIGUIENTE PARA COMPROBACION
        $proyecto->setResponsable($_SESSION['user']->idUser);

        //Conseguimos todas los proyectos del usuario através del modelo 'Proyecto.php'
        $listaProyectosUsuario = $proyecto->getAll();

        //Ahora conseguimos la lista de los proyectos en los que participa el usuario a través del modelo 'Participante.php'
        require_once __DIR__. "/../model/Participante.php";
        //Creamos el objeto Participante
        $participanteEnProyectos = new Participante($this->conexion);
        $participanteEnProyectos->setUsuario($_SESSION['user']->idUser);
        $listaParticipandoEnProyectos = $participanteEnProyectos->getAll();

        //Buscamos los datos de cada proyecto en los que participa el usuario a través del modelo 'Proyecto.php'
        $listaProyectosParticipados = array();
        foreach ($listaParticipandoEnProyectos as $participacion) {
            $proyectoParticipado = new Proyecto($this->conexion);
            $proyectoParticipado->setIdProyecto($participacion['proyecto']);
            array_push($listaProyectosParticipados, $proyectoParticipado->getProyectoById());
        }

        //Filtramos para que solo muestre los proyectos de los que el usuario no es responsable (solo colabora)
        $listaProyectosSoloParticipando = array();
        for ($y = 0; $y < count($listaProyectosParticipados); $y++){
            if($listaProyectosParticipados[$y]->responsable != $listaParticipandoEnProyectos[$y]['usuario']){
                array_push($listaProyectosSoloParticipando, $listaProyectosParticipados[$y]);
            }
        }

        //Cargamos la vista proyectosView.php con la función 'view()' y le pasamos valores (usaremos 'proyectos' para los proyectos del usuario y 'participando' para los proyectos en los que participa)
        echo $this->twig->render("boardView.html",array(
            "user" => $_SESSION["user"],
            "proyectos" => $listaProyectosUsuario,
            'participando' => $listaProyectosSoloParticipando,
            "titulo" => "Proyecto - Nonecollab"
        ));
    }

    /*--------------------------------------------------------------
    Función para crear el nuevo proyecto (objeto 'Proyecto') y mandarlo a su clase ('Proyecto.php')*/
    public function guardarProyecto() {
        if(isset($_POST['guardar'])) {
            //Construimos un nuevo objeto 'proyecto' completo para mandar a BD            
            $proyecto = new Proyecto($this->conexion);           
            $proyecto->setNombre($_POST['nombre']);
            $proyecto->setDescripcion($_POST['descripcion']);
            $proyecto->setFechaInicioProyecto($_POST['fechaInicioProyecto']);
            $proyecto->setResponsable($_POST['responsable']);
            $insercion = $proyecto->save();
        }
        
        //AQUÍ HABRÁ QUE CARGAR OTRA VISTA, NO LA INDICADA 'index.php' (ARREGLARLO)
        //Mandamos a la vista principal
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

            //Para conseguir todas las tareas en este proyecto, las conseguimos del modelo 'Tarea.php'
            include_once __DIR__ . "/../model/Tarea.php";
            //Creamos el objeto Tarea con el id del proyecto seleccionado
            $tareaProyecto = new Tarea($this->conexion);
            $tareaProyecto->setProyecto($proyectoDetalle->getIdProyecto());
            $listadoTareasProyecto = $tareaProyecto->getAll();

            include_once __DIR__. '/../model/Participante.php';
            $participanteProyecto = new Participante($this->conexion);
            $participanteProyecto->setProyecto($proyectoDetalle->getIdProyecto());
            $listaParticipantesEnProyecto = $participanteProyecto->getAllParticipantes();

            //Mandamos a la función view() para crear la vista 'detalleComentarioView'
            echo $this->twig->render("proyectoView.html", array(
                "user" => $_SESSION["user"],
                "proyecto" => $profile,
                "tareas" => $listadoTareasProyecto,
                "participantes" => $listaParticipantesEnProyecto,
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
        
        //Volvemos a cargar index.php pasándole los datos del 'controller', 'action' y el id del proyecto para cargar de nuevo 'detalleProyectoView.php' 
        header('Location: index.php?controller=proyectos&action=verDetalle&idProyecto='. $proyectoModificar->getIdProyecto());
    }
    
    /*-------------------------------------------------------------------
    Función que manda a borrar el proyecto seleccionado*/
    public function borrarProyecto() {
        //Creamos el objeto solo con el Id y lo mandamos al modelo para borrar
        $proyectoBorrar = new Proyecto($this->conexion);
        $proyectoBorrar ->setIdProyecto($_GET['idProyecto']);
        $delete = $proyectoBorrar->remove();
        
        //AQUÍ HABRÁ QUE CARGAR OTRA VISTA, NO LA INDICADA 'index.php' (ARREGLARLO)
        //Volvemos a cargar index.php
        header('Location: index.php');
    }

}
