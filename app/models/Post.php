<?php

namespace App\Models;

class Post extends Model {
    protected $table = 'posts';
    protected $fillable = ['title', 'slug', 'content', 'user_id', 'status'];
    protected $timestamps = true;

    /**
     * Find a published post by slug
     */
    public static function getBySlug($slug)
    {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare("SELECT * FROM posts WHERE slug = ? AND status = 'published' LIMIT 1");
        $stmt->execute([$slug]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }

    /**
     * Auto-generate slug on create if missing
     */
    public static function create(array $data)
    {
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = static::generateSlug($data['title']);
        }
        return parent::create($data);
    }

    /**
     * Generate unique slug for posts
     */
    public static function generateSlug($title)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $original = $slug;
        $counter = 1;

        while (static::slugExists($slug)) {
            $slug = $original . '-' . $counter;
            $counter++;
        }
        return $slug;
    }

    protected static function slugExists($slug)
    {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare("SELECT id FROM posts WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        return $stmt->fetch() !== false;
    }
}