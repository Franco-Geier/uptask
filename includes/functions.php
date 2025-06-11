<?php
declare(strict_types=1);

function debug(mixed $variable): void {
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
function showNotification(int $code): string|false {
    return match($code) {
        1 => "Creado Correctamente",
        2 => "Actualizado Correctamente",
        3 => "Eliminado Correctamente",
        default => false,
    };
}
    
function validateOrRedirection(string $url): int {
    // Validar la URL por ID válido
    $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
    if(!$id) {
        header("location: $url");
        exit;
    }
    return $id;
}

// Genera un token de 32 caracteres
function generateToken(): string {
    return md5(uniqid((string)rand(), true));
}