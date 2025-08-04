<?php

namespace Controllers;

use Model\Project;
use Model\Task;

class TaskController {
    public static function index() {
        $projectUrl = $_GET["url"];
        if(!$projectUrl) {
            header("Location: /dashboard");
        }
        $project = Project::where("url", $projectUrl);
        session_start();

        if(!$project || $project->ownerId !== $_SESSION["id"]) {
            header("Location: /404");
        }

        $tasks = Task::whereAll("projectId", $project->id);
        
        echo json_encode(
            ["tasks" => $tasks]
        );
    }

    public static function create() {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            session_start();
            $projectUrl = $_POST["projectId"];
            $project = Project::where("url", $projectUrl);

            if(!$project || $project->ownerId !== $_SESSION["id"]) {
                $response = [
                    "type" => "error",
                    "message" => "Hubo un error al agregar la tarea"
                ];
                echo json_encode($response);
                return;
            }

            // OK bien, instanciar y crear tarea
            $task = new Task($_POST);
            $task->projectId = $project->id;
            $result = $task->save();
            
            if($result) {
                $response = [
                    "type" => "exito",
                    "id" => $task->id,
                    "message" => "Tarea creada correctamente"
                ];
            } else {
                $response = [
                    "type" => "error",
                    "message" => "No se pudo guardar la tarea"
                ];
            }
            echo json_encode($response);
        }
    }

    public static function update() {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }
    }

    public static function delete() {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }
    }

}