<?php
class Review extends Model {
    protected $table = 'reviews';
    protected $primaryKey = 'id';

    public $id;
    public $rating;
    public $name_user;
    public $email_user;
    public $message;
    public $pending;
    public $daty;

    public function __construct($data = []) {
        parent::__construct($this->table);
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
    
    public function fetchLimit($limit)
    {
        $query = "SELECT * FROM " . $this->table_name." WHERE pending = 0 ORDER BY id DESC LIMIT ".$limit."";
        $this->execute($query);
        $data = array();
        while($item = $this->stmt->fetch(PDO::FETCH_OBJ)) {
            $data[] = $item;
        }
        return $data;
    }

    public function all() {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM reviews ORDER BY id DESC');
        if (!$stmt->execute()) {
            print_r($stmt->errorInfo());
        }
        $reviews = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $review) {
            $reviews[] = $review;
        }
        return $reviews;
    }

    public function find($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM reviews WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('INSERT INTO reviews (rating, name_user, email_user, message, pending, daty) VALUES (:rating, :name_user, :email_user, :message, :pending, NOW())');
        return $stmt->execute([
            ':rating' => $data['rating'],
            ':name_user' => $data['name_user'],
            ':email_user' => $data['email_user'],
            ':message' => $data['message'],
            ':pending' => $data['pending'],
        ]);
    }

    public function update($id, $data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('UPDATE reviews SET rating = :rating, name_user = :name_user, email_user = :email_user, message = :message, pending = :pending, daty = NOW() WHERE id = :id');
        return $stmt->execute([
            ':rating' => $data['rating'],
            ':name_user' => $data['name_user'],
            ':email_user' => $data['email_user'],
            ':message' => $data['message'],
            ':pending' => $data['pending'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM reviews WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
