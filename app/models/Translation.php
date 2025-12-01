<?php

namespace App\Models;

class Translation extends Model {
    protected $table = 'translations';
    protected $fillable = ['key', 'locale', 'value'];

    public static function findByKeyAndLocale($key, $locale) {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare("SELECT * FROM {$instance->table} WHERE `key` = ? AND `locale` = ? LIMIT 1");
        $stmt->execute([$key, $locale]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }

    public static function setValue($key, $locale, $value) {
        $instance = new static();
        $existing = self::findByKeyAndLocale($key, $locale);
        if ($existing && isset($existing->id)) {
            $stmt = $instance->getConnection()->prepare("UPDATE {$instance->table} SET `value` = ? WHERE `id` = ?");
            return $stmt->execute([$value, $existing->id]);
        }
        return (bool) self::create([
            'key' => $key,
            'locale' => $locale,
            'value' => $value
        ]);
    }
}
