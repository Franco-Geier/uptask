<?php
namespace Controllers;

use Model\User;
use MVC\Router;
use Classes\Email;

class LoginController {
    public static function login(Router $router) {
        $alerts = [];
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $user = new User($_POST);
            $alerts = $user->validateLogin();

            if(empty($alerts)) {
                $foundUser = User::where("email", $user->email);
                
                if(!$foundUser || !$foundUser->confirmed) {
                    User::setAlert("error", "El usuario no existe o no está confirmado");
                } else {
                    if(password_verify($_POST["password"], $foundUser->password)) {
                        session_start();
                        $_SESSION["id"] = $foundUser->id;
                        $_SESSION["name"] = $foundUser->name;
                        $_SESSION["email"] = $foundUser->email;
                        $_SESSION["login"] = TRUE;
                        header("Location: /dashboard");
                    } else {
                        User::setAlert("error", "Password incorrecto");
                    }
                }
            }
        }

        $alerts = User::getAlerts();

        $router->render("auth/login", [
            "tittle" => "Iniciar Sesión",
            "alerts" => $alerts
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        session_unset();
        session_destroy();
        setcookie(session_name(), "", time() - 3600, "/");
        header("Location: /");
    }

    public static function create(Router $router) {
        $alerts = [];
        $user = new User;

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $user->sincronize($_POST);
            $alerts = $user->validateNewAccount();
            
            if(empty($alerts)) {
                $foundUser = User::where("email", $user->email);
                
                if($foundUser) {
                    User::setAlert("error", "El usuario ya está registrado");
                    $alerts = User::getAlerts();
                } else {
                    $user->hashPassword(); // Hashear password
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
                $foundUser = User::where("email", $user->email); // Buscar al usuario
            
                if($foundUser && $foundUser->confirmed) {
                    $foundUser->createToken();
                    $foundUser->save();
                    $email = new Email($foundUser->email, $foundUser->name, $foundUser->token);
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
        $token = s($_GET["token"]);
        $show = TRUE;
        if(!$token) header("Location: /");
        $user = User::where("token", $token);

        if(!$user) {
            user::setAlert("error", "Token no válido");
            $show = FALSE;
        }

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $user->sincronize($_POST);
            $alerts = $user->validatePassword(); // Validar password
        
            if(empty($alerts)) {
                $user->hashPassword();
                $user->token = "";
                $result = $user->save();

                if($result) {
                    header("Location: /");
                }
            }
        }

        $alerts = User::getAlerts();

        $router->render("auth/restore", [
            "tittle" => "Restablecer Password",
            "alerts" => $alerts,
            "show" => $show
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
            $user->save(); // Guardar en la BD
            user::setAlert("exito", "Cuenta confirmada correctamente");
        }

        $alerts = User::getAlerts();

        $router->render("auth/confirm", [
            "tittle" => "Confirma tu Cuenta Uptask",
            "alerts" => $alerts
        ]);
    }
}