<?php

namespace App\Models;

class Blog extends Model
{
    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'title_es',
        'short_texte',
        'short_texte_es',
        'description',
        'description_es',
        'image',
        'slug',
    ];

    // Schema only has created_at, no updated_at
    protected $timestamps = false;

    /**
     * Get all blogs ordered by newest first
     */
    public static function getAll()
    {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare("SELECT * FROM {$instance->table} ORDER BY created_at DESC, id DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    public static function getBySlug($slug)
    {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare("SELECT * FROM {$instance->table} WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }
}
