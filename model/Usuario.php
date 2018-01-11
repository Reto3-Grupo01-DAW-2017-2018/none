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

        $consulta = $this->conexion->prepare("INSERT INTO " . $this->table . " (idUser,username,password,email)
                                        VALUES (:idUser,:username,:password,:email)");
        $save = $consulta->execute(array(
            "idUser" => $this->idUser,
            "username" => $this->username,
            "password" => $this->password,
            "email" => $this->email
        ));
        $this->conexion = null;

        return $save;
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

    public function update(){
        $consulta = $this->conexion->prepare("UPDATE " . $this->table . " SET username = :username, password = :password, email = :email WHERE idUser = :idUser");
        $update = $consulta->execute(array(
            "username" => $this->username,
            "password" => $this->password,
            "email" => $this->email,
            "idUser" => $this->idUser
        ));
        $this->conexion = null;
        return $update;
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
}
?>
