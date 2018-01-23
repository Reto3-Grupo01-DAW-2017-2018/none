<?php
//Configuración global
require_once 'config/global.php';
session_start();
//Cargamos el controlador y ejecutamos la accion
if(isset($_GET["controller"])){
    // Cargamos la instancia del controlador correspondiente
    $controllerObj=cargarControlador($_GET["controller"]);
    // Lanzamos la acción
    lanzarAccion($controllerObj);
}else{
    if(!isset($_SESSION["user"])){
        $controllerObj=cargarControlador(CONTROLADOR_DEFECTO);
    }else{
        $controllerObj=cargarControlador("proyecto");
    }
    // Cargamos la instancia del controlador por defecto

    // Lanzamos la acción
    lanzarAccion($controllerObj);
}

function cargarControlador($controller){

    switch ($controller) {
        case 'usuario':
            $strFileController='controller/UsuarioController.php';
            require_once $strFileController;
            $controllerObj=new UsuarioController();
            break;
        case 'proyecto':
            $strFileController='controller/ProyectoController.php';
            require_once $strFileController;
            $controllerObj=new ProyectoController();
            break;
        case 'archivo':
            $strFileController='controller/ArchivoController.php';
            require_once $strFileController;
            $controllerObj=new ArchivoController();
            break;
        case 'tarea':
            $strFileController='controller/TareaController.php';
            require_once $strFileController;
            $controllerObj=new TareaController();
            break;
        default:
            $strFileController='controller/UsuarioController.php';
            require_once $strFileController;
            $controllerObj=new UsuarioController();
            break; 
    }
    return $controllerObj;
}

function lanzarAccion($controllerObj){
    if(isset($_GET["action"])){
        $controllerObj->run($_GET["action"]);
    }else{
        $controllerObj->run(ACCION_DEFECTO);
    }
}

?>