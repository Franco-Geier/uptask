<?php
    namespace Model;
    use PDO;

    /**
     * Clase base ActiveRecord
     * 
     * @property int|null $id
     * @property string|null $imagen
     * @property string|null $creado
     * @property int $estado
    */

    interface ActiveRecordInterface {
        public function validar(): array;
        public function save(): bool;
        public function actualizar(): bool;
        public function crear(): bool;
        public function eliminar(): bool;
        public function sincronize(array $args): void;
        public function cleanAtributes(): array;
    }

    abstract class ActiveRecord implements ActiveRecordInterface {
    
        // Propiedad para la conexión
        protected static $db;
        
        // Mapeo de columnas
        protected static $columnsDB = [];
        protected static $table = "";

        // Alertas y Mensajes
        protected static $alerts = [];

        // Método estático para asignar la conexión 
        public static function setDB($database): void {
            self::$db = $database;
        }

        public static function setAlert(string $type, string $message): void {
            static::$alerts[$type][] = $message;
        }

        // Obtener las alertas
        public static function getAlerts(): array {
            return static::$alerts;
        }

        // Stub vacío de validar()
        public function validar(): array {
            return [];
        }


        /**
         * Devuelve los campos que se deben seleccionar en la consulta.
         * Si hay relaciones definidas, incluye los alias (AS). Si no, solo '*'.
         */
        protected static function relationsFields(): string {
            $extra = static::$relationsFields ?? [];
            return empty($extra)
                ? static::$table . ".*"
                : static::$table . ".*, " . implode(", ", $extra);
        }


        /**
         * Devuelve los JOINs definidos por el modelo hijo (si existen).
         */
        protected static function buildRelations(): string {
            $joins = static::$relations ?? [];
            return implode(" ", $joins);
        }


        /**
         * Trae todos los registros con o sin relaciones.
         * Puede ordenar por una columna y limitar la cantidad de resultados.
         *
         * @param string $orderBy - Columna por la que ordenar (por defecto 'id')
         * @param int|null $limit - Límite de resultados
         * @param bool $desc - Si se debe ordenar en descendente (true) o ascendente (false)
         */
        public static function allWithJoins($orderBy = "id", $limit = null, $desc = true): array {
            $fields = static::relationsFields();
            $joins = static::buildRelations();

            $query = "
                SELECT $fields
                FROM " . static::$table . "
                $joins
                ORDER BY " . static::$table . ".$orderBy " . ($desc ? "DESC" : "ASC");

            if($limit) {
                $query .= " LIMIT " . intval($limit);
            }
            return self::consultarSQL($query);
        }


        /**
         * Trae un sólo registro que coincida con el id que se pasa.
         * @param int $id - El id que se pasa para la búsqueda
         */
        public static function find(int $id): static|null {
            $fields = static::relationsFields();
            $joins = static::buildRelations();

            $query = "
                SELECT $fields
                FROM " . static::$table . "
                $joins
                WHERE " . static::$table . ".id = :id
                LIMIT 1
            ";

            $stmt = self::$db->prepare($query);
            $stmt->execute(['id' => $id]);

            $registro = $stmt->fetch(PDO::FETCH_ASSOC);
            return $registro ? static::crearObjeto($registro) : null;
        }


        /**
         * 
         */
        public static function where(string $column, mixed $value): static|null {
            $fields = static::relationsFields();
            $joins = static::buildRelations();

            $query = "
                SELECT $fields
                FROM " . static::$table . "
                $joins
                WHERE $column = :value
                LIMIT 1
            ";

            $stmt = self::$db->prepare($query);
            $stmt->execute(['value' => $value]);

            $registro = $stmt->fetch(PDO::FETCH_ASSOC);
            return $registro ? static::crearObjeto($registro) : null;
        }


        public static function whereAll(string $columna, mixed $valor): array {
            $fields = static::relationsFields();
            $joins = static::buildRelations();

            $query = "
                SELECT $fields
                FROM " . static::$table . "
                $joins
                WHERE $columna = :valor
            ";

            $stmt = self::$db->prepare($query);
            $stmt->execute(['valor' => $valor]);

            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $objetos = [];

            foreach ($registros as $registro) {
                $objetos[] = static::crearObjeto($registro);
            }

            return $objetos;
        }


        public function save(): bool {
            if(!is_null($this->id)) { // Si ya hay un Id
                return $this->actualizar(); // Actualizar
            } else {
                return $this->crear(); // Crear nuevo registro
            }
        }


        public function actualizar(): bool {
            $atributos = $this->cleanAtributes();
        
            $valores = [];
            foreach (array_keys($atributos) as $key) {
                $valores[] = "$key = :$key";
            }
        
            $query = "UPDATE " . static::$table . " SET ";
            $query .= implode(', ', $valores);
            $query .= " WHERE id = :id LIMIT 1";
        
            $atributos['id'] = $this->id;
            $stmt = self::$db->prepare($query);
            $resultado = $stmt->execute($atributos);
        
            if (!$resultado) {
                error_log("ERROR EN ACTUALIZAR: " . print_r($stmt->errorInfo(), true));
            }
            return $resultado;
        }


        public function crear(): bool {
            $atributos = $this->cleanAtributes();
            $columnas = array_keys($atributos);
            $placeholders = array_map(fn($col) => ":$col", $columnas); // Los : se llaman placeholders

            // Armamos la consulta
            $query = "INSERT INTO " . static::$table . " (" . implode(', ', $columnas) . ")";
            $query .= " VALUES (" . implode(', ', $placeholders) . ")";
        
            $stmt = self::$db->prepare($query); // Preparamos la consulta
            $resultado = $stmt->execute($atributos); // Ejecutamos

            if (!$resultado) {
                error_log("ERROR EN CREAR: " . print_r($stmt->errorInfo(), true));
                return false;
            }

            $this->id = self::$db->lastInsertId(); // Actualiza la propiedad `id` del objeto
            return true;
        }

        
        // Sincroniza el objeto en memoria con los cambios realizados por el usuario
        public function sincronize($args = []): void {
            foreach($args as $key => $value) {
                if(property_exists($this, $key) && !is_null($value)) {
                    $this->$key = $value;
                }
            }
        }


        // Eliminar un registro
        public function eliminar(): bool {
            $query = "DELETE FROM " . static::$table . " WHERE id = :id LIMIT 1";
            $stmt = self::$db->prepare($query);
            $resultado = $stmt->execute(['id' => $this->id]);
            return $resultado;
        }


        // Limpia los atributos
        public function cleanAtributes(): array {
            $atributos = [];
            foreach (static::$columnsDB as $columna) {
                if (in_array($columna, ['id', 'creado', 'fecha_registro'])) continue;
                $atributos[$columna] = $this->$columna;
            }
            return $atributos;
        }

        
        public static function consultarSQL($query): array {
            // Consultar la base de datos
            $stmt = self::$db->query($query);

            // Iterar los resultados
            $array = [];
            while($registro = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $array[] = static::crearObjeto($registro);
            }
            // Retornar los resultados
            return $array;
        }


        protected static function crearObjeto($registro): static {
            $objeto = new static;
            foreach ($registro as $key => $value) {
                if(property_exists($objeto, $key)) {
                    $objeto->$key = $value;
                } else {
                    $objeto->$key = $value; // Asignar dinámicamente propiedades no definidas (relaciones)
                }
            }
            return $objeto;
        }
    }