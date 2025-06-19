<?php
// app/models/Gallery.php
require_once __DIR__ . '/Model.php';

class Gallery extends Model {
    protected $table = 'galleries';
    protected $primaryKey = 'id';

    public $id;
    public $image;

    public function __construct($data = []) {
        parent::__construct($this->table);
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function all() {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM galleries ORDER BY id DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM galleries WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('INSERT INTO galleries (image) VALUES (:image)');
        return $stmt->execute([
            ':image' => $data['image']
        ]);
    }

    public function update($id, $data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('UPDATE galleries SET image = :image WHERE id = :id');
        return $stmt->execute([
            ':image' => $data['image'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM galleries WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
