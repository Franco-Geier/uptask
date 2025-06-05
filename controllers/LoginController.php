<?php
namespace Controllers;

class LoginController {
    public static function login() {
        echo "Desde Login";

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }
    }

    public static function logout() {
        echo "Desde Logout";
    }

    public static function create() {
        echo "Desde Create";

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }
    }

    public static function forgot() {
        echo "Desde Forgot";

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }
    }

    public static function restore() {
        echo "Desde restore";

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }
    }

    public static function message() {
        echo "Desde message";
    }

    public static function confirm() {
        echo "Desde confirm";
    }
}