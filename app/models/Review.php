<?php

namespace App\Models;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'rating',
        'name_user',
        'email_user',
        'message',
        'pending',
        'daty',
    ];

    protected $timestamps = false;

    public static function getPending()
    {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} WHERE `pending` = 1 ORDER BY `daty` DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    public static function getApproved()
    {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} WHERE `pending` = 0 ORDER BY `daty` DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    public static function updateById($id, array $data)
    {
        if (empty($data)) return false;

        $instance = new static();
        $sets = [];
        $values = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $instance->fillable)) {
                $sets[] = "`$key` = ?";
                $values[] = $value;
            }
        }
        if (empty($sets)) return false;
        $values[] = $id;
        $sql = "UPDATE {$instance->table} SET " . implode(', ', $sets) . " WHERE `id` = ?";
        $stmt = $instance->getConnection()->prepare($sql);
        return $stmt->execute($values);
    }

    public static function setPending($id, $pending = 0)
    {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "UPDATE {$instance->table} SET `pending` = ? WHERE `id` = ?"
        );
        return $stmt->execute([(int)$pending, (int)$id]);
    }

    public static function deleteById($id)
    {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "DELETE FROM {$instance->table} WHERE `id` = ?"
        );
        return $stmt->execute([(int)$id]);
    }
}
