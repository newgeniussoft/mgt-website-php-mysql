<?php
// app/models/SocialMedia.php
require_once __DIR__ . '/Model.php';

class SocialMedia extends Model {
    protected $table = 'social_media';
    protected $primaryKey = 'id';

    public $id;
    public $name;
    public $link;
    public $image;

    public function __construct($data = []) {
        parent::__construct($this->table);
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function all() {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM social_media ORDER BY id DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM social_media WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('INSERT INTO social_media (name, link, image) VALUES (:name, :link, :image)');
        return $stmt->execute([
            ':name' => $data['name'],
            ':link' => $data['link'],
            ':image' => $data['image'],
        ]);
    }

    public function update($id, $data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('UPDATE social_media SET name = :name, link = :link, image = :image WHERE id = :id');
        return $stmt->execute([
            ':name' => $data['name'],
            ':link' => $data['link'],
            ':image' => $data['image'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM social_media WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
