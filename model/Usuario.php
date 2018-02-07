<?php
class Usuario {
    private $table = "usuario";
    private $conexion;

    private $idUser;
    private $username;
    private $password;
    private $email;

    public function __construct($conexion) {
		$this->conexion = $conexion;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function save(){

        $consulta = $this->conexion->prepare("INSERT INTO " . $this->table . " (username,password,email)
                                        VALUES (:username,:password,:email)");
        $resultado = $consulta->execute(array(
            "username" => $this->username,
            "password" => $this->password,
            "email" => $this->email
        ));
        $resultado = $consulta->rowCount();
        $this->conexion = null;

        return $resultado;
    }

    /**
     * Funcion para comprobar si existe un correo en la base de datos
     */
    public function correo()
    {


        $consulta= $this->conexion->prepare('
            SELECT email
            FROM usuario
            WHERE email = :email');

        $consulta->execute(array(
            'email' => $this->email
        ));

        if($consulta->fetchColumn())
        {
            //echo 'Ya existe un usuario registrado con este Email.';

            die(false);
        }
        else
        {
            /*echo 'NO SE HA ENCONTRADO ESTE EMAIL EN LA BD<br>
          rteetr  (AQUI HABRIA QUE MANDAR A LA VISTA DEL LOGIN DE NUEVO (DND ESTABA)).';
            */

            die(true);
        }
        $this->conexion = null;
    }

    /**
     * Funcion para comprobar si existe un alias en la base de datos
     */
    public function username()
    {
        $consulta= $this->conexion->prepare('
            SELECT username
            FROM usuario
            WHERE username = :username');

        $consulta->execute(array(
            'username' => $this->username
        ));

        if($consulta->fetchColumn())
        {
            //echo 'Ya existe un usuario registrado con este alias.';

            die(false);
        }
        else
        {
            /*echo 'NO SE HA ENCONTRADO ESTE EMAIL EN LA BD<br>
          rteetr  (AQUI HABRIA QUE MANDAR A LA VISTA DEL LOGIN DE NUEVO (DND ESTABA)).';
            */

            die(true);
        }
        $this->conexion = null;
    }
    
    public function getAll(){

        $consulta = $this->conexion->prepare("SELECT idUser,username,password,email FROM " . $this->table);
        $consulta->execute();
        /* Fetch all of the remaining rows in the result set */
        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;
    }

    public function getUsuarioById(){

        $consulta = $this->conexion->prepare("SELECT idUser,username,password,email FROM " . $this->table . " WHERE idUser = :idUser");
        $consulta->execute(array(
                "idUser" => $this->idUser)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado=$consulta->fetchObject();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }

    public function getUsuarioByUsername(){

        $consulta = $this->conexion->prepare("SELECT idUser,username,password,email FROM " . $this->table . " WHERE username = :username");
        $consulta->execute(array(
                "username" => $this->username)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado=$consulta->fetchObject();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }

    public function getNoInvitados($idProyecto) {
        //die(var_dump($idUs));
        $consulta = $this->conexion->prepare("SELECT idUser,username FROM " . $this->table . " WHERE idUser not in (SELECT usuario FROM participante WHERE proyecto = :proyecto)");
        $consulta->execute(array(
            "proyecto" => $idProyecto
        ));
        $resultados = $consulta->fetchAll();
        //die(var_dump($consulta));
        $this->conexion = null; //cierre de conexión
        return $resultados;

    }

    public function getUsuarioLogin(){

        $consulta = $this->conexion->prepare("SELECT idUser,username,password,email FROM " . $this->table . " WHERE (username = :username OR email = :username ) AND password = :password ");
        $consulta->execute(array(
                "username" => $this->username,
                "password" => $this->password
            ));
        /* Fetch all of the remaining rows in the result set */
        $resultado=$consulta->fetchObject();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }

    public function update(){
        $consulta = $this->conexion->prepare("UPDATE " . $this->table . " SET username = :username, password = :password, email = :email WHERE idUser = :idUser");
        $update = $consulta->execute(array(
            "username" => $this->username,
            "password" => $this->password,
            "email" => $this->email,
            "idUser" => $this->idUser
        ));
        $resultado = $consulta->rowCount();
        $this->conexion = null;
        return $resultado;
    }

    public function remove(){

        $consulta = $this->conexion->prepare("DELETE FROM " . $this->table . " WHERE idUser = :idUser" );
        $consulta->execute(array(
            "idUser" => $this->idUser)
        );
        /* Fetch all of the remaining rows in the result set */
        $resultado = $consulta->rowCount();
        $this->conexion = null; //cierre de conexión
        return $resultado;
    }
    public function getUsuarioByParticipante($participante){

        $consulta = $this->conexion->prepare("SELECT idUser,username,email FROM " . $this->table . " WHERE idUser IN (Select distinct usuario from participante where idParticipante = :idParticipante)" );
        $consulta->execute(array(
            "idParticipante" => $participante
        ));
        /* Fetch all of the remaining rows in the result set */
        $resultados = $consulta->fetchAll();
        $this->conexion = null; //cierre de conexión
        return $resultados;
    }
}
?>
