<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected string $email;
    protected string $name;
    protected string $token;

    public function __construct(string $email, string $name, string $token) {
        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }

    public function sendConfirmation() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'b0ba581ebf57f6';
        $mail->Password = '189d63aa39f836';
    
        $mail->setFrom("accounts@uptask.com");
        $mail->addAddress("accounts@uptask.com", "uptask.com");
        // $mail->addAddress($this->email, $this->name);
        $mail->Subject = "Confirma tu Cuenta";

        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $content = "<html><body style='font-family: sans-serif; color: #333;'>";
        $content .= "<p>Hola <strong>{$this->name}</strong>, has creado tu cuenta en UpTask. Solo debes confirmarla en el siguiente enlace:</p>";
        $content .= "<p><a href='http://localhost:3000/confirm?token={$this->token}' style='background-color: #0891B2; color: #FFFFFF; padding: 10px 15px; text-decoration: none;'>Confirmar Cuenta</a></p>";
        $content .= "<p>Si tú no creaste esta cuenta, puedes ignorar este mensaje.</p>";
        $content .= "</body></html>";

        $mail->AltBody = "Hola {$this->name}, confirma tu cuenta en UpTask: http://localhost:3000/confirm?token={$this->token}";

        $mail->Body = $content;
        $mail->send();
    }
}