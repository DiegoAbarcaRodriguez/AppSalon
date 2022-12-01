<?php
namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email{
    public $email;
    public $nombre;
    public $token;
    
    public function __construct($email,$nombre,$token)
    {
        $this->email=$email;
        $this->nombre=$nombre;
        $this->token=$token;

    }

    public function enviarConfirmacion(){
        //Crear el objeto email
        $mail=new PHPMailer(true);
        $mail->isSMTP();  
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->SMTPSecure = 'tls';
        $mail->Username = '5eddd4ca20d39e';
        $mail->Password = '3fd4f80c786ab2';   

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com','AppSalon.com');     
        $mail->Subject='Confirma tu cuenta';     

        //Poner Html al cuerpo del mail.
        $mail->isHTML(TRUE);
        $mail->CharSet='UTF-8';
        
        $contenido="<html>";
        $contenido.="<p><strong>Hola ".$this->nombre."</strong> Has creado tu cuenta en App Salon,
         solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido.="<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=".$this->token."'>Confirmar Cuenta </a></p>";
        $contenido.="<p>Si tu no solicitaste la cuenta, puedes ignorar el mensaje.</p>";
        $contenido.="</html>";

        $mail->Body=$contenido;
        $mail->AltBody="Hola";

        //Enviar el mail
        $mail->send();

       
    }

    public function enviarInstrucciones(){
          //Crear el objeto email
          $mail=new PHPMailer(true);
          $mail->isSMTP();  
          $mail->Host = 'smtp.mailtrap.io';
          $mail->SMTPAuth = true;
          $mail->Port = 2525;
          $mail->SMTPSecure = 'tls';
          $mail->Username = '5eddd4ca20d39e';
          $mail->Password = '3fd4f80c786ab2';   
  
          $mail->setFrom('cuentas@appsalon.com');
          $mail->addAddress('cuentas@appsalon.com','AppSalon.com');     
          $mail->Subject='Reestablece tu cuenta';     
  
          //Poner Html al cuerpo del mail.
          $mail->isHTML(TRUE);
          $mail->CharSet='UTF-8';
          
          $contenido="<html>";
          $contenido.="<p><strong>Hola ".$this->nombre."</strong> Has solicitado reestablecer tu cuenta en App Salon,
           sigue el siguiente enlace para hacerlo</p>";
          $contenido.="<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token=".$this->token."'>Confirmar Cuenta </a></p>";
          $contenido.="<p>Si tu no solicitaste la cuenta, puedes ignorar el mensaje.</p>";
          $contenido.="</html>";
  
          $mail->Body=$contenido;
          $mail->AltBody="Hola";
  
          //Enviar el mail
          $mail->send();

    }

}