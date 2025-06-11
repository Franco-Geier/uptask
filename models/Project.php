<?php

namespace Model;

use Model\ActiveRecord;

class Project extends ActiveRecord {
    protected static $table = "projects";
    protected static $columnsDB = ["id", "project", "url", "ownerId"];

    public ?int $id;
    public string $project;
    public string $url;
    public ?int $ownerId;
    
    public function __construct($args = []) {
        $this->id = $args["id"] ?? null;
        $this->project = $args["project"] ?? "";
        $this->url = $args["id"] ?? "";
        $this->ownerId = $args["ownerId"] ?? null;
    }

    public function validateProject() {
        if(!$this->project) {
            self::$alerts["error"][] = "El nombre del proyecto es obligatorio";
        } elseif(!preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s'-]+$/", $this->project)) {
            self::$alerts["error"][] = "El proyecto solo puede contener letras, números, espacios, apóstrofes y guiones.";
        } elseif(mb_strlen($this->project) > 60) {
            self::$alerts["error"][] = "El nombre del proyecto debe tener hasta 60 caracteres";
        }
        return self::$alerts;
    }
}