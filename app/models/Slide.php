<?php

namespace App\Models;

class Slide extends Model
{
    protected $table = 'slides';

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'link_url',
        'button_text',
        'sort_order',
        'status',
    ];

    protected $timestamps = true;

    /**
     * Get all active slides ordered for display.
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
