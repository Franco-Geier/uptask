<?php

namespace Controllers;

use MVC\Router;

class DashboardController {
    public static function index(Router $router) {
        session_start();
        isAuth();

        $router->render("dashboard/index", [
            "tittle" => "Proyectos"
        ]);
    }

    public static function create_project(Router $router) {
        session_start();
        $router->render("dashboard/create-project", [
            "tittle" => "Crear Proyecto"
        ]);
    }

    public static function profile(Router $router) {
        session_start();
        $router->render("dashboard/profile", [
            "tittle" => "Perfil"
        ]);
    }
}