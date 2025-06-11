<?php

namespace Controllers;

use Model\Project;
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
        isAuth();
        $alerts = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $project = new Project($_POST);
            $alerts = $project->validateProject();

            if(empty($alerts)) {
                $project->url = generateToken();
                $project->ownerId = $_SESSION["id"]; // Almacenar al creador del proyecto
                $project->save();
                header("Location: /project?url={$project->url}");
            }
        }
    
        $router->render("dashboard/create-project", [
            "tittle" => "Crear Proyecto",
            "alerts" => $alerts
        ]);
    }

    public static function profile(Router $router) {
        session_start();
        $router->render("dashboard/profile", [
            "tittle" => "Perfil"
        ]);
    }
}