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

    protected function sendMail(string $subject, string $htmlBody, string $altBody): void {
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
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->AltBody = $altBody;
        $mail->Body = $htmlBody;
        $mail->send();
    }

    public function sendConfirmation(): void {
        $html = "<html><body style='font-family: sans-serif; color: #333;'>";
        $html .= "<p>Hola <strong>{$this->name}</strong>, has creado tu cuenta en UpTask. Confírmala en el siguiente enlace:</p>";
        $html .= "<p><a href='http://localhost:3000/confirm?token={$this->token}' style='background-color: #0891B2; color: #FFFFFF; padding: 10px 15px; text-decoration: none;'>Confirmar Cuenta</a></p>";
        $html .= "<p>Si tú no creaste esta cuenta, puedes ignorar este mensaje.</p></body></html>";

        $alt = "Hola {$this->name}, confirma tu cuenta en UpTask: http://localhost:3000/confirm?token={$this->token}";

        $this->sendMail("Confirma tu Cuenta", $html, $alt);
    }


    public function sendInstructions(): void {
        $html = "<html><body style='font-family: sans-serif; color: #333;'>";
        $html .= "<p>Hola <strong>{$this->name}</strong>, parece que has olvidado tu password, sigue el siguiente enlace para recuperarlo:</p>";
        $html .= "<p><a href='http://localhost:3000/restore?token={$this->token}' style='background-color: #0891B2; color: #FFFFFF; padding: 10px 15px; text-decoration: none;'>Restablecer Password</a></p>";
        $html .= "<p>Si tú no creaste esta cuenta, puedes ignorar este mensaje.</p></body></html>";

        $alt = "Hola {$this->name}, restablece tu password en UpTask: http://localhost:3000/restore?token={$this->token}";

        $this->sendMail("Restablece tu Password", $html, $alt);
    }
}