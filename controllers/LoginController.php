<?php
namespace Controllers;

use Classes\email;
use MVC\Router;
use Model\Usuario;

class LoginController{

    public static function login(Router $router){
        $alertas=[];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario=new Usuario($_POST);

            $alertas= $usuario->validarLogin();

            if(empty($alertas)){
                //Comprobar que exista el usuario
              $resultado= Usuario::where('email',$usuario->email);

            
                if($resultado){
                    //Verificar Password
                    if( $usuario->comprobarPasswordAndVerificado($resultado)){
                        //Autenticar al usuario

                        session_start();

                        $_SESSION['id']=$resultado->id;
                        $_SESSION['nombre']=$resultado->nombre.' '.$resultado->apellido;
                        $_SESSION['email']=$resultado->email;
                        $_SESSION['login']=true;

                        if($resultado->admin==='1'){
                            $_SESSION['admin']=$resultado->admin??null;
                            header('Location:/admin');
                        }else{
                            header('Location:/cita');
                        }
                    }

                
                   
                }else{
                    Usuario::setAlerta('error','usuario no encontrado');
                }
            }

        }
        $alertas=Usuario::getAlertas();

        $router->render('auth/login',[
            'alertas'=>$alertas
        ]);
    }

    public static function logout(Router $router){
        session_start();

        $_SESSION=[];

        header('Location: /');
    }

    public static function olvide(Router $router){
        $alertas=[];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario=new Usuario($_POST);
           $alertas= $usuario->validarEmail();

           if(empty($alertas)){
            //Validar que el usuario exista
            $resultado=Usuario::where('email',$usuario->email);

        
            if(!is_null($resultado) && $resultado->confirmado==='1'){
                //Generar token de un solo uso y enviar instrucciones al email.
                $resultado->crearToken();

                $resultado->guardar();

                Usuario::setAlerta('exito','Revisa tu email');

                $email=new Email($resultado->email,$resultado->nombre,$resultado->token);
                $email->enviarInstrucciones();
               
            }else{
                Usuario::setAlerta('error','El usuario no existe o no está confirmado');
            }

           }

        }

        $alertas=Usuario::getAlertas();
        $router->render('auth/olvide-password',[
            'alertas'=>$alertas]);
    }



    public static function recuperar(Router $router){

        $alertas=[];
        $error=false;

        $token=s($_GET['token']);

        //Buscar Usuario por su token
        $usuario=Usuario::where('token',$token);

        if(empty($usuario)){
            $usuario->setAlerta('error','Token no Vàlido');
            $error=true;
        }

        if($_SERVER['REQUEST_METHOD']==='POST'){
            //Leer password y guardarlo
            $password=new Usuario($_POST);

           $alertas= $password->validarPassword();

            if(empty($alertas)){
                $usuario->password=$password->password;
                $usuario->hashearPassword();
                $usuario->token=null;

                $resultado=$usuario->guardar();

                if($resultado){
                    header('Location: /');
                }
            }

        }


        $alertas=$usuario->getAlertas();

        $router->render('auth/recuperar-password',[
            'alertas'=>$alertas,
            'error'=>$error
        ]);
    }

    public static function crear(Router $router){
        

        $usuario=new Usuario();
        $alertas=[]; //Es vacio puesto que en este caso no se está empleando el método de la clase padre validadr(), el cual resetea al arreglo alerta, y por consiguiente en una primer instancia este estaría vacio.

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario=new Usuario($_POST);
            $alertas=$usuario->validarNuevaCuenta();

            //Verifica si el usuario ya existe
            if(empty($alertas)){
               $resultado= $usuario->existeUsuario();

               if($resultado->num_rows){
                $alertas=Usuario::getAlertas(); //Se obtiene la alerta definida en memoria dentro de la instancia del objeto creada al mandar la petición POST.
               }else{
                //--Se almacena la cuenta en la BD.

                //Hashear password.
                $usuario->hashearPassword();

                //Generar Token único.
                $usuario->crearToken();
                
               
                //Enviar email con él token
                $email=new Email($usuario->email,$usuario->nombre,$usuario->token); //La lógica de phpmailer se define dentro de la clase email.

                $email->enviarConfirmacion();

                //Crear cuenta
                 $resultado= $usuario->guardar();

                 if($resultado){
                    header('Location:/mensaje');
                 }
               }
            }

        }

        $router->render('auth/crear-cuenta',[
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);

    }

    //Obtiene vista con mensaje de que la cuenta debe ser confirmada.
    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas=[];

        $token=s($_GET['token']);

        $usuario= Usuario::where('token',$token);

        if(empty($usuario)){
            //Mostrar mensaje de error.
            Usuario::setAlerta('error','Token no válido');
        }else{
            //Cambiar columna de confirmado por 1.
            $usuario->confirmado=1;
            //Eliminar el token.
            $usuario->token='';
            //Guardar Usuario.
            $usuario->guardar();
            Usuario::setAlerta('exito','Cuenta Comprobada Correctamente');
        }

        //Obtener alertas.
        $alertas=Usuario::getAlertas();

        //Renderizar vista.
        $router->render('auth/confirmar-cuenta',[
            'alertas'=>$alertas
        ]);
    }
}