<?php
namespace Model;

class AdminCita extends ActiveRecord{ //Es un modelo que directamente no representa un tabla de nuestra BD, si no un conjunto de JOINS que constituyen una nueva tabla virtual con elementos de todas las demás.
    protected static $tabla='citasServicios'; //Se le define el nombre de la tabla en donde encontrará mayor información(Tabla Pivote)

    protected static $columnasDB=['id','hora','cliente','email','telefono','servicio','precio']; //Nombre de las columnas y alias dfinidos tras efectuar los JOINS

    public $id;
    public $hora;
    public $cliente;
    public $email;
    public $telefono;
    public $servicio;
    public $precio;

    public function __construct($args=[])
    {
        $this->id=$args['id']??null;
        $this->hora=$args['hora']??'';
        $this->cliente=$args['cliente']??'';
        $this->email=$args['email']??'';
        $this->telefono=$args['telefono']??'';
        $this->servicio=$args['servicio']??'';
        $this->precio=$args['precio']??'';
        
    }
}