<?php
// app/models/Slide.php
require_once __DIR__ . '/Model.php';

class Slide extends Model {
    protected $table = 'slides';
    protected $primaryKey = 'id';

    public $id;
    public $caption;
    public $caption_es;
    public $image;

    public function __construct($data = []) {
        parent::__construct($this->table);
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function all() {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM slides ORDER BY id DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM slides WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('INSERT INTO slides (caption, caption_es, image) VALUES (:caption, :caption_es, :image)');
        return $stmt->execute([
            ':caption' => $data['caption'],
            ':caption_es' => $data['caption_es'],
            ':image' => $data['image'],
        ]);
    }

    public function update($id, $data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('UPDATE slides SET caption = :caption, caption_es = :caption_es, image = :image WHERE id = :id');
        return $stmt->execute([
            ':caption' => $data['caption'],
            ':caption_es' => $data['caption_es'],
            ':image' => $data['image'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM slides WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

