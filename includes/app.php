<?php

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
define("PEPPER", $_ENV["APP_PEPPER"]); // Pepper para passwords
require 'functions.php';
require 'database.php';

// Conectarnos a la base de datos
$db = conectarBD();
use Model\ActiveRecord;
ActiveRecord::setDB($db);