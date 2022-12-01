<?php 
namespace Controllers;
use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;

class APIController{

    //Función que obtiene el listado de servicios de la base de datos.
    public static function index(){
        $servicios=Servicio::all();
        echo json_encode($servicios); //El archivo que se codifica a JSON desde el PHP debe ser un arreglo de objetos o bien un arreglo de arreglos asociativos, para que pueda haber una correspondencia con el JSON
    }


    //Función que realiza una inserción en la tabla cita y eventualmente en la tabla citaServicios, a partir de la información provista mediante los eventos de JavaScript destinado para este control.
    public static function guardar(){
        //Almacena la cita y devuelve el Id
        $cita=new Cita($_POST);
       $resultado=$cita->guardar();

       $id=$resultado['id'];

       //Almacena los servicios con el ID de la cita
       $idServicios=explode(',',$_POST['servicios']); //Dado a que $_POST['servicios] almacena los id a manera de string separados por ( , ) 

      foreach($idServicios as $idServicio){
            $args=[
                'citaId'=>$id,
                'servicioId'=>$idServicio,
            ];
            $citaServicio=new CitaServicio($args);
            $citaServicio->guardar();
       }

       //Retonarmos una respuesta
        echo json_encode(['resultado'=>$resultado]);
    }

    //Elimina el servicio seleccionado
    public static function eliminar(){
        
        if($_SERVER['REQUEST_METHOD']==='POST'){  
           
            $id=$_POST['id'];
            $cita=Cita::find($id);
            $cita->eliminar();
            header('Location:'.$_SERVER['HTTP_REFERER']); //Retorna a la ruta previamente ingresada.
        }
    }
}