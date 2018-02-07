<?php
require_once __DIR__ . "/BaseController.php";
class UsuarioController extends BaseController{

    public function __construct(){
        parent::__construct();
        require_once __DIR__ . "/../model/Usuario.php";
    }

    /**
     * Ejecuta la acción correspondiente.
     */

    public function run($accion){
        switch($accion)
        {
            case "index" :
                $this->index();
                break;
            case "correo" :
                $this->comprobarC();
                break;
            case "username" :
                $this->username();
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
            case "registerView" :
                $this->crearRegisterView();
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
            case "quienesSomos":
                $this->abrirQuienesSomos();
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
            //$this->view("login","");
            echo $this->twig->render("loginView.html",array(
                "titulo"=>"Login - Nonecollab"
            ));
        }
    }

    /**
     * Funcion para comporbar si existe en la bd nuest5ro correo
     */
    public function comprobarC()
    {

        if(isset($_POST["email"]))
        {
            $correo=$_POST["email"]."";
            $usuario=new Usuario($this->conexion);
            $usuario->setEmail($correo);
            $usuario->correo();
        }
    }

    /**
     * Funcion para comporbar si existe en la bd nuest5ro correo
     */
    public function username()
    {
        if(isset($_POST["username"]))
        {
            $username=$_POST["username"]."";
            $usuario=new Usuario($this->conexion);
            $usuario->setUsername($username);
            $usuario->username();
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
                $usuario = new Usuario ($this->conexion);
                $usuario->setUsername($_POST["username"]);
                $user=$usuario->getUsuarioByUsername();
                $_SESSION["user"]=$user;
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
            $user=$_SESSION["user"];
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
                $_SESSION["user"]=$user;
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
            echo $this->twig->render("updateView.html",array(
                "user"=>$_SESSION["user"]
            ));
        }
    }

    /**
     * Como para crear esta view no necesitamos datos (estan en session), comprobamos que la session exita y lanzamos la view.
     */
    public function crearRegisterView(){
        if(!isset($_SESSION["user"])){
            echo $this->twig->render("registerView.html",array(
            ));
        }
    }

    /**
     * Como para crear esta view no necesitamos datos (estan en session), comprobamos que la session exita y lanzamos la view.
     */
    public function crearCustomView(){
        if(isset($_SESSION["user"])){
            echo $this->twig->render("personalizarView.html",array(
                "user"=>$_SESSION["user"],
                "titulo"=>"Custom - Nonecollab"
            ));
        }
    }

    public function update(){
        if(isset($_POST["idUser"])){
            //borramos un usuario
            $usuario = new Usuario($this->conexion);
            $usuario->setIdUser($_POST["idUser"]);
            $usuario->setUsername($_POST["username"]);
            $usuario->setPassword($_POST["password"]);
            $usuario->setEmail($_POST["email"]);
            $resultado=$usuario->update();
        }
        header('Location: index.php');
    }

    public function abrirQuienesSomos(){

        if(isset($_SESSION["user"])){
            echo $this->twig->render("quienesSomosView.html",array(
                "user"=>$_SESSION["user"],
                "titulo"=>"Login - Nonecollab"
            ));
        }else{
            echo $this->twig->render("quienesSomosView.html",array(
                "titulo"=>"Quienes somos - Nonecollab"
            ));
        }
    }
}
?>
