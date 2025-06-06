<?php
namespace Controllers;
use MVC\Router;

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
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }

        $router->render("auth/create", [
            "tittle" => "Crea tu cuenta en UpTask"
        ]);
    }

    public static function forgot(Router $router) {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }

        $router->render("auth/forgot", [
            "tittle" => "Olvide mi Password"
        ]);
    }

    public static function restore() {
        echo "Desde restore";

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }
    }

    public static function message() {
        echo "Desde message";
    }

    public static function confirm() {
        echo "Desde confirm";
    }
}