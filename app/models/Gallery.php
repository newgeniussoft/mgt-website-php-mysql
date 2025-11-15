<?php

namespace App\Models;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected $fillable = [
        'title',
        'description',
        'image',
        'sort_order',
        'status',
    ];

    protected $timestamps = true;

    /**
     * Get all active gallery items ordered for display.
     */
    public static function getActive()
    {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
}
