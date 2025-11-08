<?php

namespace App\Models;

class Setting extends Model {
    protected $table = 'settings';
    protected $fillable = ['key', 'value', 'type', 'group', 'label', 'description', 'order'];
    
    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null) {
        $setting = static::where('key', '=', $key);
        
        if (empty($setting)) {
            return $default;
        }
        
        $setting = $setting[0];
        
        // Cast value based on type
        return static::castValue($setting->value, $setting->type);
    }
    
    /**
     * Set a setting value by key
     */
    public static function set($key, $value) {
        $setting = static::where('key', '=', $key);
        
        if (empty($setting)) {
            return static::create([
                'key' => $key,
                'value' => $value
            ]);
        }
        
        $setting = $setting[0];
        $setting->value = $value;
        return $setting->save();
    }
    
    /**
     * Get all settings as key-value array
     */
    public static function getAllAsArray() {
        $settings = static::all();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = static::castValue($setting->value, $setting->type);
        }
        
        return $result;
    }
    
    /**
     * Get settings grouped by group
     */
    public static function getByGroup($group) {
        return static::where('group', '=', $group);
    }
    
    /**
     * Get all groups
     */
    public static function getAllGroups() {
        
        $stmt = self::getConnection()->prepare("SELECT DISTINCT `group` FROM settings ORDER BY `group`");
        $stmt->execute();
        $groups = [];
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $groups[] = $row['group'];
        }
        
        return $groups;
    }
    
    /**
     * Cast value based on type
     */
    protected static function castValue($value, $type) {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'number':
                return is_numeric($value) ? (strpos($value, '.') !== false ? (float) $value : (int) $value) : $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
    
    /**
     * Update multiple settings at once
     */
    public static function updateMany($settings) {
        foreach ($settings as $key => $value) {
            static::set($key, $value);
        }
        return true;
    }
    
    /**
     * Check if a setting exists
     */
    public static function has($key) {
        $setting = static::where('key', '=', $key);
        return !empty($setting);
    }
    
    /**
     * Delete a setting by key
     */
    public static function remove($key) {
        $setting = static::where('key', '=', $key);
        
        if (!empty($setting)) {
            return $setting[0]->delete();
        }
        
        return false;
    }
    
    /**
     * Get settings for a specific group with ordering
     */
    public static function getGroupedSettings() {
       
        $stmt = self::getConnection()->prepare("SELECT * FROM settings ORDER BY `group`, `order`");
        $stmt->execute();
        
        $settings = [];
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $group = $row['group'];
            if (!isset($settings[$group])) {
                $settings[$group] = [];
            }
            $settings[$group][] = (object) $row;
        }
        
        return $settings;
    }
}
