<?php

class Info extends Model
{
    protected $table = 'info';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getInfo()
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM info LIMIT 1');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateInfo($data)
    {
        $db = $this->db->getConnection();   
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        $sql = 'UPDATE info SET ' . implode(', ', $fields) . ' WHERE id = 1';
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }
}
