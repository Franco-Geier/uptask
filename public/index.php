<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\DashboardController;
use Controllers\LoginController;

$router = new Router();

// Login
$router->get("/", [LoginController::class, "login"]);
$router->post("/", [LoginController::class, "login"]);
$router->get("/logout", [LoginController::class, "logout"]);

// Crear Cuenta
$router->get("/create", [LoginController::class, "create"]);
$router->post("/create", [LoginController::class, "create"]);

// Formulario de olvide mi password
$router->get("/forgot", [LoginController::class, "forgot"]);
$router->post("/forgot", [LoginController::class, "forgot"]);

// Colocar el nuevo password
$router->get("/restore", [LoginController::class, "restore"]);
$router->post("/restore", [LoginController::class, "restore"]);

// Confirmación de cuenta
$router->get("/message", [LoginController::class, "message"]);
$router->get("/confirm", [LoginController::class, "confirm"]);

// Zona de proyectos
$router->get("/dashboard", [DashboardController::class, "index"]);
$router->get("/create-project", [DashboardController::class, "create_project"]);
$router->post("/create-project", [DashboardController::class, "create_project"]);
$router->get("/profile", [DashboardController::class, "profile"]);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();