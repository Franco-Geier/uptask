<?php

namespace Model;

class Task extends ActiveRecord {
    protected static $table = "tasks";
    protected static $columnsDB = ["id", "name", "state", "projectId"];

    public ?int $id;
    public string $name;
    public int $state;
    public ?int $projectId;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? null;
        $this->name = $args["name"] ?? "";
        $this->state = isset($args["state"]) ? (int)$args["state"] : 0;
        $this->projectId = is_numeric($args["projectId"] ?? null) ? (int)$args["projectId"] : null;
    }
}