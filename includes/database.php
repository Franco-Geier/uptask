<?php

function conectarBD(): PDO {
    try {
        $host = $_ENV["DB_HOST"];
        $dbname = $_ENV["DB_NAME"];
        $user = $_ENV["DB_USER"];
        $pass = $_ENV["DB_PASS"];

        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Errores mas claros
        $db->exec("SET NAMES utf8"); // Codificacion
        return $db;
    } catch(PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
        exit;
    }
}