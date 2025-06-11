<?php

namespace Model;

class User extends ActiveRecord {
    protected static $table = "users";
    protected static $columnsDB = ["id", "name", "email", "password", "token", "confirmed"];

    public ?int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $password2;
    public string $token;
    public int $confirmed;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? null;
        $this->name = $args["name"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->password2 = $args["password2"] ?? "";
        $this->token = $args["token"] ?? "";
        $this->confirmed = isset($args["confirmed"]) ? (int)$args["confirmed"] : 0;
    }

    protected function validatePasswordRequired(): void {
        if(!$this->password) {
            self::$alerts["error"][] = "El password no puede ir vacío";
        }
    }

    protected function validatePasswordSecure(): void {
        if(mb_strlen($this->password) < 8 || mb_strlen($this->password) > 128) {
            self::$alerts["error"][] = "El password debe contener entre 8 y 128 caracteres";
        } elseif($this->password !== $this->password2) {
            self::$alerts["error"][] = "Los passwords no coinciden";
        } else {
            if(!preg_match('/[A-Z]/', $this->password)) {
                self::$alerts["error"][] = "El password debe incluir al menos una letra mayúscula";
            }
            if(!preg_match('/[a-z]/', $this->password)) {
                self::$alerts["error"][] = "El password debe incluir al menos una letra minúscula";
            }
            if(!preg_match('/[0-9]/', $this->password)) {
                self::$alerts["error"][] = "El password debe incluir al menos un número";
            }
            if(!preg_match('/[^a-zA-Z0-9]/', $this->password)) {
                self::$alerts["error"][] = "El password debe incluir al menos un carácter especial";
            }
        }
    }

    protected function validateEmailLogic(): void {
        if(!$this->email) {
            self::$alerts["error"][] = "El email del usuario es obligatorio";
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alerts["error"][] = "El email no es válido.";
        }
    }

    protected function validateLoginLogic(): void {
        $this->validateEmailLogic();
        $this->validatePasswordRequired();
    }

    // Validación para cuentas nuevas
    public function validateNewAccount() {
        if(!$this->name) {
            self::$alerts["error"][] = "El nombre del usuario es obligatorio";
        } elseif(!preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s'-]+$/", $this->name)) {
            self::$alerts["error"][] = "El nombre solo puede contener letras, números, espacios, apóstrofes y guiones.";
        } elseif(mb_strlen($this->name) > 30) {
            self::$alerts["error"][] = "El nombre debe tener hasta 30 caracteres.";
        }

        $this->validateEmailLogic();
        $this->validatePasswordRequired();
        $this->validatePasswordSecure();
        
        return self::$alerts;
    }

    // Valida el login del usuario y retorna un array de alertas
    public function validateLogin() {
        $this->validateLoginLogic();
        return self::$alerts;
    }

    // Valida un email y retorna un array de alertas
    public function validateEmail(): array {
        $this->validateEmailLogic();
        return self::$alerts;
    }

    // Valida un password y retorna un array de alertas
    public function validatePassword(): array {
        $this->validatePasswordRequired();
        $this->validatePasswordSecure();
        return self::$alerts;
    }

    public function hashPassword() {
        $pepper = PEPPER;
        $peppered = hash_hmac("sha256", $this->password, $pepper);
            
        $options = [
            "memory_cost" => 1 << 17, // 128 MB
            "time_cost" => 4,
            "threads" => 2
        ];

        $this->password = password_hash($peppered, PASSWORD_ARGON2ID, $options);
    }

    public static function verifyPassword(string $input, string $hash): bool {
        $pepper = PEPPER;
        $peppered = hash_hmac("sha256", $input, $pepper);
        return password_verify($peppered, $hash);
    }
}