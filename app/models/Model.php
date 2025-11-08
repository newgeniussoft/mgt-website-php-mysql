<?php

namespace App\Models;

abstract class Model {
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $casts = [];
    protected $attributes = [];
    protected $timestamps = true;
    
    protected static $connection;
    
    public static function setConnection($pdo) {
        self::$connection = $pdo;
    }
    
    protected function getConnection() {
        return self::$connection;
    }
    
    public static function all() {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare("SELECT * FROM {$instance->table}");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    public static function find($id) {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare("SELECT * FROM {$instance->table} WHERE `{$instance->primaryKey}` = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }
    
    public static function where($column, $operator, $value = null) {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $instance = new static();
        $stmt = $instance->getConnection()->prepare("SELECT * FROM {$instance->table} WHERE `$column` $operator ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    public static function create(array $data) {
        $instance = new static();
        $instance->fill($data);
        $instance->save();
        return $instance;
    }
    
    public function fill(array $data) {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable) && !in_array($key, $this->guarded)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }
    
    public function save() {
        if ($this->timestamps) {
            if (!isset($this->attributes[$this->primaryKey])) {
                $this->attributes['created_at'] = date('Y-m-d H:i:s');
            }
            $this->attributes['updated_at'] = date('Y-m-d H:i:s');
        }
        
        if (isset($this->attributes[$this->primaryKey])) {
            return $this->update();
        }
        return $this->insert();
    }
    
    protected function insert() {
        // Escape column names with backticks
        $columns = implode(', ', array_map(function($col) { return "`$col`"; }, array_keys($this->attributes)));
        $placeholders = implode(', ', array_fill(0, count($this->attributes), '?'));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute(array_values($this->attributes));
        
        $this->attributes[$this->primaryKey] = $this->getConnection()->lastInsertId();
        return true;
    }
    
    protected function update() {
        $sets = [];
        $values = [];
        
        foreach ($this->attributes as $key => $value) {
            if ($key !== $this->primaryKey) {
                // Escape column names with backticks
                $sets[] = "`$key` = ?";
                $values[] = $value;
            }
        }
        
        $values[] = $this->attributes[$this->primaryKey];
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE `{$this->primaryKey}` = ?";
        $stmt = $this->getConnection()->prepare($sql);
        return $stmt->execute($values);
    }
    
    public function delete() {
        if (!isset($this->attributes[$this->primaryKey])) {
            return false;
        }
        
        $sql = "DELETE FROM {$this->table} WHERE `{$this->primaryKey}` = ?";
        $stmt = $this->getConnection()->prepare($sql);
        return $stmt->execute([$this->attributes[$this->primaryKey]]);
    }
    
    public function __get($key) {
        return $this->attributes[$key] ?? null;
    }
    
    public function __set($key, $value) {
        $this->attributes[$key] = $value;
    }
    
    public function toArray() {
        $array = $this->attributes;
        foreach ($this->hidden as $key) {
            unset($array[$key]);
        }
        return $array;
    }
}