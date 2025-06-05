<?php

function conectarBD(): PDO {
    try {
        $db = new PDO("mysql:host=localhost;dbname=uptask_mvc", "root", "root");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Errores mas claros
        $db->exec("SET NAMES utf8"); // Codificacion
        return $db;
    } catch(PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
        exit;
    }
}