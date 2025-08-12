<?php

namespace Controllers;

use Model\User;
use MVC\Router;
use Model\Project;

class DashboardController {
    public static function index(Router $router) {
        session_start();
        isAuth();

        $id = $_SESSION["id"];
        $projects = Project::whereAll("ownerId", $id);

        $router->render("dashboard/index", [
            "tittle" => "Proyectos",
            "projects" => $projects
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

    public static function project(Router $router) {
        session_start();
        isAuth();
        $url = $_GET["url"];
        
        if(!$url) header("Location: /dashboard");

        $project = Project::where("url", $url);

        if($project->ownerId !== $_SESSION["id"]) {
            header("Location: /dashboard");
        }

        $router->render("dashboard/project", [
            "tittle" => $project->project
        ]);
    }

    public static function profile(Router $router) {
        session_start();
        isAuth();
        $alerts = [];
        $user = User::find($_SESSION["id"]);

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $user->sincronize($_POST);
            $alerts = $user->validateEditAccount();

            if(empty($alerts)) {
                $userExists = User::where("email", $user->email);

                if($userExists && $userExists->id !== $user->id) {
                    User::setAlert("error", "El Email ya pertenece a otra cuenta");
                    $alerts = $user->getAlerts();
                } else {
                    $user->save();
                    User::setAlert("exito", "Guardado correctamente");
                    $alerts = $user->getAlerts();
                    $_SESSION["name"] = $user->name; // Asignar el nombre nuevo a la barra
                }
            }
        }
        
        $router->render("dashboard/profile", [
            "tittle" => "Perfil",
            "user" => $user,
            "alerts" => $alerts
        ]);
    }

    public static function change_password(Router $router) {
        session_start();
        isAuth();
        $alerts = [];

        $router->render("dashboard/change-password", [
            "tittle" => "Cambiar Password",
            "alerts" => $alerts
        ]);
    }
}