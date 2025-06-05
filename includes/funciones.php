<?php
declare(strict_types=1);

function debuguear(mixed $variable): void {
    echo "<pre>";
        var_dump($variable);
    echo "</pre>";
    exit;
}

// Función que revisa que el usuario este autenticado
function isAuth() : void {
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

// Escapar/Sanitizar HTML
function s(?string $html): string {
    return htmlspecialchars($html ?? "");
}

// Muestra los mensajes
function mostrarNotificacion(int $codigo): string|false {
    return match($codigo) {
        1 => "Creado Correctamente",
        2 => "Actualizado Correctamente",
        3 => "Eliminado Correctamente",
        default => false,
    };
}
    
function validarORedireccionar(string $url): int {
    // Validar la URL por ID válido
    $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
    if(!$id) {
        header("location: $url");
        exit;
    }
    return $id;
}