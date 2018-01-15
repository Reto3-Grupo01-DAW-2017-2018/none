<?php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../model/Usuario.php";
session_start();
class UsuarioController extends BaseController{


    /**
     * Ejecuta la acción correspondiente.
     */

    public function run($accion){
        switch($accion)
        {
            case "index" :
                $this->index();
                break;
            case "alta" :
                $this->crear();
                break;
            case "baja" :
                $this->deleteById();
                break;
            case "update" :
                $this->update();
                break;
            case "perfil" :
                $this->crearPerfilView();
                break;
            case "custom" :
                $this->crearCustomView();
                break;
            case "login" :
                $this->login();
                break;
            case "logout" :
                $this->logout();
                break;
            default:
                $this->index();
                break;
        }
    }

    /**
     * Carga la página dependiendo de si hay usuario en sesion o no.
     */
    public function index(){
        if(!isset($_SESSION["user"])){
            /*Aqui cargamos la vista de bienvenida/login*/
            $this->view("login","");
            //echo $this->twig->render("loginView.html");
        }else{
            /*cargamos la vista de la lista de proyectos*/
            $this->view("board","");
        }
    }

    /**
     * Crea un nuevo Usuario a partir de los parámetros POST
     */
    public function crear(){
        if(isset($_POST["username"])){

            //Creamos un usuario
            $usuario = new Usuario($this->conexion);
            $usuario->setUsername($_POST["username"]);
            $usuario->setPassword($_POST["password"]);
            $usuario->setEmail($_POST["email"]);
            $resultado=$usuario->save();
            //Despues comprobamos si se ha insertado y si se ha insertado cogemos los datos para hacer un login "manual"
            if($resultado!=0){
                session_start();
                $user=$usuario->getUsuarioByUsername();
                $_SESSION["user"]=serialize($user);
            }else{
                //no se ha insertado ninguna fila, sacar mensaje de error.
            }
        }
        header('Location: index.php');
    }

    /**
     * Borra el usuario logueado a partir de los datos de la sesion, OJO!
     */
    public function deleteById(){
        if(isset($_SESSION["user"])) {
            //borramos el usuario logueado Cuidado!
            $user=unserialize($_SESSION["user"]);
            $filasborradas=$user->remove();
            session_destroy();
        }
        header('Location: index.php');
    }

    /**
     * Comprueba el login mediante el (username||email) y password, si esta correcto nos devuelve el objeto completo y lo guardamos en session
     */
    public function login(){
        if(isset($_POST["username"])&&isset($_POST["password"])){
            $usuario = new Usuario($this->conexion);
            $usuario->setUsername($_POST["username"]);
            $usuario->setPassword($_POST["password"]);
            $user=$usuario->getUsuarioLogin();
            if($user!=null){
                $_SESSION["user"]=serialize($user);
            }
        }
        header('Location: index.php');
    }

    /**
     * Comprueba el login mediante el (username||email) y password, si esta correcto nos devuelve el objeto completo y lo guardamos en session
     */
    public function logout(){
        if(isset($_SESSION["user"])){
            session_destroy();
        }
        header('Location: index.php');
    }

    /**
     * Como para crear esta view no necesitamos datos (estan en session), comprobamos que la session exita y lanzamos la view.
     */
    public function crearPerfilView(){
        if(isset($_SESSION["user"])){
            $this->view("perfil","");
        }
    }

    /**
     * Como para crear esta view no necesitamos datos (estan en session), comprobamos que la session exita y lanzamos la view.
     */
    public function crearCustomView(){
        if(isset($_SESSION["user"])){
            $this->view("personalizar","");
        }
    }

    public function update(){
        if(isset($_POST["iduser"])){
            //borramos un usuario
            $usuario = new Usuario($this->conexion);
            $usuario->setIdUsuario($_POST["iduser"]);
            $usuario->setUsername($_POST["username"]);
            $usuario->setPassword($_POST["password"]);
            $usuario->setEmail($_POST["email"]);
            $update=$usuario->update();
        }
        header('Location: index.php');
    }

    /**
     * Crea la vista que le pasemos con los datos indicados.
     */
    public function view($vista,$datos){
        $data = $datos;

        require_once  __DIR__ . "/../view/" . $vista . "View.php";
    }
}
?>
