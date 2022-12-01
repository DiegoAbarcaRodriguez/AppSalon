<?php 
namespace Controllers;
use MVC\Router;

class CitaController{
    public static function index(Router $router){
        //Trae los datos de la sesión para imprimirlos en pantalla en cita/index
        session_start();

        isAuth();// Corrobora la existencia de la variable $_SESSION['login'], si no está definida redirecciona al login.

        $router->render('cita/index',[
            "nombre"=>$_SESSION['nombre'],
            "id"=>$_SESSION['id']

        ]);
    }

}