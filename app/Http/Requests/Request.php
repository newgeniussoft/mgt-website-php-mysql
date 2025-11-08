<?php

namespace App\Http\Requests;

abstract class Request {
    protected $data = [];
    protected $errors = [];
    
    abstract public function rules();
    
    public function __construct(array $data = []) {
        $this->data = $data ?: array_merge($_GET, $_POST, json_decode(file_get_contents('php://input'), true) ?: []);
    }
    
    public function validate() {
        $rules = $this->rules();
        
        foreach ($rules as $field => $ruleSet) {
            $ruleArray = explode('|', $ruleSet);
            
            foreach ($ruleArray as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        
        return empty($this->errors);
    }
    
    protected function applyRule($field, $rule) {
        $value = $this->data[$field] ?? null;
        
        if (strpos($rule, ':') !== false) {
            list($ruleName, $parameter) = explode(':', $rule, 2);
        } else {
            $ruleName = $rule;
            $parameter = null;
        }
        
        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->errors[$field][] = "The $field field is required.";
                }
                break;
            
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "The $field must be a valid email address.";
                }
                break;
            
            case 'min':
                if (!empty($value) && strlen($value) < $parameter) {
                    $this->errors[$field][] = "The $field must be at least $parameter characters.";
                }
                break;
            
            case 'max':
                if (!empty($value) && strlen($value) > $parameter) {
                    $this->errors[$field][] = "The $field may not be greater than $parameter characters.";
                }
                break;
            
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->errors[$field][] = "The $field must be a number.";
                }
                break;
            
            case 'string':
                if (!empty($value) && !is_string($value)) {
                    $this->errors[$field][] = "The $field must be a string.";
                }
                break;
                
            case 'unique':
                if (!empty($value)) {
                    list($table, $column) = explode(',', $parameter);
                    $exists = \App\Models\Model::where($column, '=', $value);
                    if (!empty($exists)) {
                        $this->errors[$field][] = "The $field has already been taken.";
                    }
                }
                break;
        }
    }
    
    public function validated() {
        if (!$this->validate()) {
            throw new \Exception(json_encode($this->errors));
        }
        return array_intersect_key($this->data, $this->rules());
    }
    
    public function errors() {
        return $this->errors;
    }
    
    public function get($key, $default = null) {
        return $this->data[$key] ?? $default;
    }
    
    public function all() {
        return $this->data;
    }
}