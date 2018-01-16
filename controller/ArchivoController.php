<?php

class ArchivoController extends BaseController {
    public function __construct() {
        require_once __DIR__. "/../model/Archivo.php";
    }
    
    /*-------------------------------------------------------------------
    Función que, según la acción pasada en la url, manda a cada función correspondiente*/
    public function run($accion){
        switch($accion) { 
            case "index" :
                $this->index();
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
            case "descargarArchivo" :
                $this->descargarArchivo();
                break;
            default:
                $this->index();
                break;
        }
    }
    
    /*-------------------------------------------------------------------
    Función que carga la lista de archivos en el proyecto indicado, conseguida del modelo (Archivo)*/
    public function index() {
        //Creamos el objeto 'Archivo'
        $archivo = new Archivo($this->conexion);
        $archivo->setProyecto($_GET['proyecto']);
        
        //Conseguimos todas los archivos (lista de los archivos en BD)
        $listaArchivos = $archivo->getAll();
        
        //Cargamos la vista archivosView.php con la función 'view()' y le pasamos valores (usaremos 'archivos')
        $this->view('archivos', array(
            'archivos' => $listaArchivos,
            'titulo' => 'ARCHIVOS'
        ));
    }
    
    /*--------------------------------------------------------------
    Función para crear el nuevo archivo (objeto 'Archivo') y mandarlo a su clase (Archivo.php)*/
    public function guardarArchivo() {
        if(isset($_POST['guardar'])) {
            //Construimos un nuevo objeto 'archivo' completo para mandar a BD            
            $archivo = new Archivo($this->conexion);           
            $archivo->setNombreArchivo($_POST['nombreArchivo']);
            $archivo->setRutaArchivo($_POST['rutaArchivo']);
            $archivo->setParticipante($_POST['participante']);
            $archivo->setProyecto($_POST['proyecto']);

            $insercion = $archivo->save();
        }
        
        //AQUÍ HABRÁ QUE CARGAR OTRA VISTA, NO LA INDICADA 'index.php' (ARREGLARLO)
        //Mandamos a la vista principal
        header('Location: index.php?controlador=proyecto');
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
        //Creamos el objeto solo con el Id y lo mandamos al modelo para borrar
        $archivoBorrar = new Archivo($this->conexion);
        $archivoBorrar ->setIdArchivo($_GET['idArchivo']);
        $delete = $archivoBorrar->remove();
        
        //AQUÍ HABRÁ QUE CARGAR OTRA VISTA, NO LA INDICADA 'index.php' (ARREGLARLO)
        //Volvemos a cargar index.php
        header('Location: index.php');
    }
    /*------------------------------------------------------------------
    Función que borra el zip si esta creado de una descarga anterior, despues lo crea zip con los archivos seleccionados y ejecuta la descarga.*/
    public function descargarArchivo($archivos){
        $idsArchivos=array_slice($archivos,1,1);
        $archivos = [];
        if(file_exists(__DIR__.'../zip/archivosByCheck.zip')){
            unlink(__DIR__.'../zip/archivosByCheck.zip');
        }
        $zip = new ZipArchive;
        if ($zip->open(__DIR__.'../../zip/curriculumsByCheck.zip',  ZipArchive::CREATE) === TRUE) {
            foreach ($idsArchivos as $id) {
                $archivo=/*Aquí va la query*/;
                array_push($archivos, $archivo);
            }
            foreach ($archivos as $archivo){
                $archivo = __DIR__."/../".$archivo->getRutaArchivo();
                if(file_exists($archivo) && $archivo->getRutaArchivo() != ""){
                    $zip->addFile($archivo, $archivo->getNombreArchivo());
                }
            }
        }
        $zip->close();
        clearstatcache();
        if(file_exists(__DIR__.'../zip/archivosByCheck.zip')){
            $file = __DIR__.'../zip/archivosByCheck.zip';
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
        header('Location:../Vista/Profesor/ofertaAlumnos.php');
    }
    
    /*------------------------------------------------------------------
    Función para crear la vista con el nombre que le pasemos y con los datos que le indiquemos*/
    public function view($vista, $datos) {
        $data = $datos;
        
        require_once __DIR__. '/../views/'. $vista. 'View.php';        
    }
}
