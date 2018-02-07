<?php
require_once __DIR__ . "/BaseController.php";

class ArchivoController extends BaseController {
    public function __construct() {
        parent::__construct();
        require_once __DIR__. "/../model/Archivo.php";
        require_once __DIR__. "/../model/Participante.php";
    }
    
    /*-------------------------------------------------------------------
    Función que, según la acción pasada en la url, manda a cada función correspondiente*/
    public function run($accion){
        switch($accion) { 
            case "archivosPorProyecto" :
                $this->archivosPorProyecto();
                break;
            case "cargarArchivos":
                $this->cargarArchivosAjax();
                break;
            case "archivosUsuario" :
                $this->buscarArchivosUser();
                break;
            case "aniadirArchivo" :
                $this->view('aniadirArchivo', "");
                break;
            case "nuevoArchivo" :
                $this->guardarArchivo();
                break;
            case "verDetalle" :
                $this->mostrarDatosArchivo();
                break;
            case "eliminar" :
                $this->borrarArchivo();
                break;
            case "modificarArchivo" :
                $this->modificarDatosArchivo();
                break;
            case "descargarArchivos" :
                $this->descargarArchivos();
                break;
            case "descargarArchivosSelect" :
                $this->descargarArchivosSelect();
                break;
            default:
                $this->cargarArchivosAjax();
                break;
        }
    }
    
    /*-------------------------------------------------------------------
    Función que carga la lista de archivos en el proyecto indicado, conseguida del modelo (Archivo)*/
    public function archivosPorProyecto() {
        //Creamos el objeto 'Archivo'
        if(isset($_GET["proyecto"])&&isset($_GET["proyectoNombre"])&&isset($_GET["responsable"])){
            $archivo = new Archivo($this->conexion);
            $archivo->setProyecto($_GET['proyecto']);

            //Conseguimos todas los archivos (lista de los archivos en BD)
            $listaArchivos = $archivo->getAll();
            if(count($listaArchivos)==0){
                $listaArchivos=null;
            }

            $proyecto=array(
                "idProyecto"=>$_GET["proyecto"],
                "nombre"=>$_GET["proyectoNombre"],
                "responsable"=>$_GET["responsable"]
            );

            echo $this->twig->render("archivosProyectoView.html",array(
                "user" => $_SESSION["user"],
                "archivos" => $listaArchivos,
                "proyecto"=>$proyecto,
                "titulo" => "Archivos - Nonecollab"
            ));
        }

    }

    /*-------------------------------------------------------------------
    Función que carga la lista de archivos en el proyecto indicado, conseguida del modelo (Archivo)*/
    public function cargarArchivosAjax() {
        //Creamos el objeto 'Archivo'
        if(isset($_GET["idProyecto"])){
            $archivo = new Archivo($this->conexion);
            $archivo->setProyecto($_GET['idProyecto']);
            //Conseguimos todas los archivos (lista de los archivos en BD)
            $listaArchivos = $archivo->getAll();
            $listaArchivosJson=json_encode($listaArchivos);
            echo($listaArchivosJson);
        }

    }

    /*-------------------------------------------------------------------
    Función que carga la lista de todos los archivos del usuario logueado */
    public function buscarArchivosUser() {

        if(isset($_SESSION['user'])) {
            $archivoUser = new Archivo($this->conexion);
            $listaArchivosUser = $archivoUser->getAllByUser($_SESSION["user"]->idUser);

            echo $this->twig->render("archivosUserView.html",array(
                "user" => $_SESSION["user"],
                "archivosUser" => $listaArchivosUser,
                "titulo" => "Archivos del Usuario - Nonecollab"
            ));
        }
    }

    /*--------------------------------------------------------------
    Función para crear el nuevo archivo (objeto 'Archivo') y subirlo a la carpeta del servidor*/
    public function guardarArchivo() {
        if(isset($_GET['idProyecto'])) {
            //CREAMOS VARIABLE PARA GUARDAR LA RUTA A LA CARPETA DE DESTINO DND GUARDAR LA FOTO SUBIDA
            $carpetaDestinoGuardarFoto = __DIR__."\..\data\\".$_GET['idProyecto']."\\";
            if(realpath($carpetaDestinoGuardarFoto)==false){
                mkdir($carpetaDestinoGuardarFoto);
            }
            //RECOGEMOS LOS DATOS DEL ARCHIVO SUBIDO EN EL INPUT FILE DEL FORMULARIO
            $archivos= $_FILES['archivos'];
            //for($x=0;$x<count($archivos);$x++){

            $nombreArchivoSubido = $archivos["name"];
            $archivoTmp = $archivos["tmp_name"];
            //CONFIRMAMOS QUE LA RUTA DE LA FOTO SUBIDA SE HA GUARDADO EN LA CARPETA DE DESTINO
            if(file_exists($carpetaDestinoGuardarFoto.$nombreArchivoSubido) == true || move_uploaded_file($archivoTmp, $carpetaDestinoGuardarFoto.$nombreArchivoSubido) == false) {
                //header("Location: index.php?controller=archivo&action=archivosPorProyecto&proyecto=".$_GET."&proyectoNombre=".$_GET['nombreProyecto']);
                echo "false";
            }
            else {
                //Construimos un nuevo objeto 'archivo' completo para mandar a BD
                $archivo = new Archivo($this->conexion);
                $archivo->setNombreArchivo($nombreArchivoSubido);
                $archivo->setRutaArchivo("/data/".$_GET['idProyecto']."/".$nombreArchivoSubido);
                /*necesitamos conocer el id del participante hacemos la consulta con el id del
                usuario + el id del proyecto*/
                $participante = new Participante ($this->conexion);
                $participante->setProyecto($_GET['idProyecto']);
                $participante->setUsuario($_SESSION['user']->idUser);
                $participante2=$participante->getParticipanteByUsuarioAndProyecto();
                /*Ahora pasamos el participante a el archivo*/
                $archivo->setParticipante($participante2->idParticipante);
                $archivo->setProyecto($_GET['idProyecto']);
                $insercion = $archivo->save();
                //COMPROBAMOS QUE SE HA HECHO EL INSERT
                if($insercion < 1) {
                    //header("Location: index.php?controller=archivo&action=archivosPorProyecto&proyecto=".$_GET['idProyecto']."&proyectoNombre=".$_GET['nombreProyecto']);
                    echo "false";
                }
                else {
                    //header("Location: index.php?controller=archivo&action=archivosPorProyecto&proyecto=".$_GET['idProyecto']."&proyectoNombre=".$_GET['nombreProyecto']);
                    echo "true";
                }
            }
            //}

        }

        //AQUÍ HABRÁ QUE CARGAR OTRA VISTA, NO LA INDICADA 'index.php' (ARREGLARLO)
        //Mandamos a la vista principal
        //header("Location: index.php?controller=archivo&action=archivosPorProyecto&proyecto=".$_GET['idProyecto']."&proyectoNombre=".$_GET['nombreProyecto']);
    }
    
    /*--------------------------------------------------------------
    Función manda al modelo para buscar los datos del archivo seleccionado en el boton 'Ver Archivo' */
    public function mostrarDatosArchivo() {
        //Creamos el objeto solo con el Id y con esto sacaremos todos sus datos de BD
        $archivoDetalle = new Archivo($this->conexion);
        $archivoDetalle ->setIdArchivo($_GET['idArchivo']);
        $profile = $archivoDetalle->getArchivoById();
        
        //Mandamos a la función view() para crear la vista 'detalleArchivoView'
        $this->view('detalleArchivo',array(
            "archivo"=>$profile,
            "titulo" => "DETALLE ARCHIVO"
        ));
    }   
    
    /*-------------------------------------------------------------------
    Función que manda a modificar los datos del archivo seleccionado*/
    public function modificarDatosArchivo() {        
        //Creamos el objeto completo y lo mandamos a actualizar al modelo
        $archivoModificar = new Archivo($this->conexion);
        $archivoModificar->setIdArchivo($_POST['idArchivo']);
        $archivoModificar->setNombreArchivo($_POST['nuevoNombreArchivo']);
        $archivoModificar->setRutaArchivo($_POST['nuevoRutaArchivo']);
        $archivoModificar->setParticipante($_POST['nuevoParticipante']);
        $archivoModificar->setProyecto($_POST['nuevoProyecto']);
        $update = $archivoModificar->update();
        
        //Volvemos a cargar index.php pasándole los datos del 'controller', 'action' y el id del archivo para cargar de nuevo 'detalleArchivoView.php' 
        header('Location: index.php?controller=archivos&action=verDetalle&idArchivo='. $archivoModificar->getIdArchivo());
    }
    
    /*-------------------------------------------------------------------
    Función que manda a borrar el archivo seleccionado*/
    public function borrarArchivo() {
        if(isset($_GET['idArchivo'])&&isset($_GET['proyecto'])&&isset($_GET['nombreArchivo'])&&isset($_GET['responsable'])){
            $path=__DIR__."/../data/".$_GET['proyecto']."/".$_GET['nombreArchivo'];
            if(unlink($path)==true){
                //Creamos el objeto solo con el Id y lo mandamos al modelo para borrar
                $archivoBorrar = new Archivo($this->conexion);
                $archivoBorrar->setIdArchivo($_GET['idArchivo']);
                $delete = $archivoBorrar->remove();
            }
        }
        header("Location: index.php?controller=archivo&action=archivosPorProyecto&proyecto=".$_GET['proyecto']."&proyectoNombre=".$_GET['nombreProyecto']."&responsable=".$_GET['responsable']);
    }

    /*------------------------------------------------------------------
    Función que borra el zip si esta creado de una descarga anterior, despues lo crea zip con los archivos seleccionados y ejecuta la descarga.*/
    public function descargarArchivosSelect(){
        $idsArchivos=$_POST["archivosSeleccionados"];
        $archivos = [];
        if(file_exists(__DIR__.'/../zip/archivosByCheck.zip')){
            unlink(__DIR__.'/../zip/archivosByCheck.zip');
        }
        $zip = new ZipArchive;
        if ($zip->open(__DIR__.'/../zip/archivosByCheck.zip',  ZipArchive::CREATE) === TRUE) {
            foreach ($idsArchivos as $id) {
                $archivo= new Archivo($this->conexion);
                $archivo->setIdArchivo($id);
                $archivo=$archivo->getArchivoById();

                array_push($archivos, $archivo);
            }
            foreach ($archivos as $archivo){
                $ruta = __DIR__."/..".$archivo->rutaArchivo;
                if(file_exists($ruta) && $archivo->rutaArchivo != ""){
                    $zip->addFile($ruta,$archivo->nombreArchivo);
                }
            }
        }
        $zip->close();
        clearstatcache();
        if(file_exists(__DIR__.'/../zip/archivosByCheck.zip')){
            $file = __DIR__.'/../zip/archivosByCheck.zip';
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header( "Content-Disposition: attachment; filename=".basename($file));
            header( "Content-Description: File Transfer");
            header('Accept-Ranges: bytes');
            header('Content-Length: ' . filesize($file));
            @readfile($file);
            //die(var_dump($file));
        }
        header("Location: index.php?controller=archivo&action=archivosPorProyecto&proyecto=".$_GET['proyecto']."&proyectoNombre=".$_GET['nombreProyecto']);
    }

    /*------------------------------------------------------------------
    Función que borra el zip si esta creado de una descarga anterior, despues lo crea zip con los archivos seleccionados y ejecuta la descarga.*/
    public function descargarArchivos(){
        if(isset($_GET['proyecto'])&&isset($_GET['nombreProyecto'])){
            if(file_exists(__DIR__.'/../zip/archivos.zip')){
                unlink(__DIR__.'/../zip/archivos.zip');
            }
            $zip = new ZipArchive;
            if ($zip->open(__DIR__.'/../zip/archivos.zip',  ZipArchive::CREATE) === TRUE) {
                $archivo= new Archivo($this->conexion);
                $archivo->setProyecto($_GET['proyecto']);
                $archivos=$archivo->getAll();
                foreach ($archivos as $archivo){
                    $ruta = __DIR__."/..".$archivo["rutaArchivo"];
                    if(file_exists($ruta) && $archivo["rutaArchivo"] != ""){
                        $zip->addFile($ruta,$archivo["nombreArchivo"]);
                    }
                }
            }
            $zip->close();
            clearstatcache();
            if(file_exists(__DIR__.'/../zip/archivos.zip')){
                $file = __DIR__.'/../zip/archivos.zip';
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header( "Content-Disposition: attachment; filename=".basename($file));
                header( "Content-Description: File Transfer");
                header('Accept-Ranges: bytes');
                header('Content-Length: ' . filesize($file));
                @readfile($file);
                //die(var_dump($file));
            }
            header("Location: index.php?controller=archivo&action=archivosPorProyecto&proyecto=".$_GET['proyecto']."&proyectoNombre=".$_GET['nombreProyecto']);
        }
    }
}
