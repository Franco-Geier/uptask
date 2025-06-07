<?php
namespace Controllers;

use Model\User;
use MVC\Router;
use Classes\Email;

class LoginController {
    public static function login(Router $router) {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }

        $router->render("auth/login", [
            "tittle" => "Iniciar Sesión"
        ]);
    }

    public static function logout() {
        echo "Desde Logout";
    }

    public static function create(Router $router) {
        $alerts = [];
        $user = new User;

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $user->sincronize($_POST);
            $alerts = $user->validateNewAccount();
            
            if(empty($alerts)) {
                $userExist = User::where("email", $user->email);
                
                if($userExist) {
                    User::setAlert("error", "El usuario ya está registrado");
                    $alerts = User::getAlerts();
                } else {
                    $user->hashPassword(); // Hashear password
                    unset($user->password2); // Eliminar password2
                    $user->createToken(); // Generar el token
                    $result = $user->save(); // Guardar el nuevo usuario
                    $email = new Email($user->email, $user->name, $user->token); // Crear el objeto email
                    $email->sendConfirmation(); // Enviar email de confirmación
                    if($result) {
                        header("Location: /message");
                    }
                }
            }
        }

        $router->render("auth/create", [
            "tittle" => "Crea tu cuenta en UpTask",
            "user" => $user,
            "alerts" => $alerts
        ]);
    }

    public static function forgot(Router $router) {
        $alerts = [];
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $user = new User($_POST);
            $alerts = $user->validateEmail();

            if(empty($alerts)) {
                $user = User::where("email", $user->email); // Buscar al usuario
            
                if($user && $user->confirmed) {
                    $user->createToken();
                    unset($user->password2);
                    $user->save();
                    $email = new Email($user->email, $user->name, $user->token);
                    $email->sendInstructions();
                    User::setAlert("exito", "Hemos enviado las instrucciones a tu email");
                } else {
                    User::setAlert("error", "El usuario no existe o no está confirmado");
                }
            }
        }

        $alerts = User::getAlerts();

        $router->render("auth/forgot", [
            "tittle" => "Olvide mi Password",
            "alerts" => $alerts
        ]);
    }

    public static function restore(Router $router) {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }

        $router->render("auth/restore", [
            "tittle" => "Restablecer Password"
        ]);
    }

    public static function message(Router $router) {
        $router->render("auth/message", [
            "tittle" => "Cuenta Creada Exitosamente" 
        ]);
    }

    public static function confirm(Router $router) {
        $token = s($_GET["token"]); // Leer el token de la URL
        if(!$token) header("Location: /"); // Si no hay token redirecciona
        $user = User::where("token", $token);

        if(!$user) {
            user::setAlert("error", "Token no válido"); // No se encontró un usuario con ese token
        } else {
            $user->confirmed = 1; // Confirmar la cuenta
            $user->token = "";
            unset($user->password2);
            $user->save(); // Guardar en la BD
            user::setAlert("exito", "Cueta confirmada correctamente");
        }

        $alerts = User::getAlerts();

        $router->render("auth/confirm", [
            "tittle" => "Confirma tu Cuenta Uptask",
            "alerts" => $alerts
        ]);
    }
}