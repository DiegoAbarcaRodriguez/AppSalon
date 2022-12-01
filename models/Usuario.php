<?php
namespace Model;
use Model\ActiveRecord;
class Usuario extends ActiveRecord{
    protected static $tabla='usuarios';
    protected static $columnasDB=['id','nombre','apellido','email','password','telefono','admin','confirmado','token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args=[])
    {
        $this->id=$args['id']??null;
        $this->nombre=$args['nombre']??'';
        $this->apellido=$args['apellido']??'';
        $this->email=$args['email']??'';
        $this->password=$args['password']??'';
        $this->telefono=$args['telefono']??'';
        $this->admin=$args['admin']??'0';
        $this->confirmado=$args['confirmado']??'0';
        $this->token=$args['token']??'';


    }

//-----------Crear Cuenta----------------------------------
    //Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][]="El Nombre es Obligatorio";
        }

        if(!$this->apellido){
            self::$alertas['error'][]="El Apellido es Obligatorio";
        }

        if(!$this->email){
            self::$alertas['error'][]="El Email es Obligatorio";
        }

        if(!$this->password){
            self::$alertas['error'][]="El Password es Obligatorio";
        }

        if(strlen($this->password)<6){
            self::$alertas['error'][]="El Password debe contener al menos 6 carácteres";
        }

        return self::$alertas;

    }

    //Revisa si el usuario ya existe
    public function existeUsuario(){
        $query="SELECT * FROM ".self::$tabla." WHERE email='".$this->email."' LIMIT 1";
        
        $resultado=self::$db->query($query);
        if($resultado->num_rows){
            self::$alertas['error'][]="El usuario ya está registrado";
        }

        return $resultado;
    }


    public function hashearPassword(){
        $this->password=password_hash($this->password,PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token=uniqid();
    }



    //----------Login---------------
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][]='El email es obligatorio';
        }

        if(!$this->password){
            self::$alertas['error'][]='El password es obligatorio';
        }

        return self::$alertas;
    }

    public function comprobarPasswordAndVerificado($obj){
        
        $resultado=password_verify($this->password,$obj->password);

        
        if(!$obj->confirmado || !$resultado ){
           self::$alertas['error'][]='Password incorrecto o tu cuenta no está confirmada';
        }else{
            return true;
        }
    }

  //------------Olvide password----------------------
  public function validarEmail(){
    if(!$this->email){
        self::$alertas['error'][]="El Email es Obligatorio";
    }

    return self::$alertas;
  }  

  public function validarPassword(){
    if(!$this->password){
        self::$alertas['error'][]="El Password es Obligatorio";
    }

    if(strlen($this->password)<6){
        self::$alertas['error'][]="El Password debe contener al menos 6 carácteres";
    }

    return self::$alertas;

  }

}
