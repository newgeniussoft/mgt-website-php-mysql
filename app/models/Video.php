<?php
// app/models/Video.php
require_once __DIR__ . '/Model.php';

class Video extends Model {
    protected $table = 'videos';
    protected $primaryKey = 'id';

    public $id;
    public $title;
    public $subtitle;
    public $title_es;
    public $subtitle_es;
    public $link;

    public function __construct($data = []) {
        parent::__construct($this->table);
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function all() {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM videos ORDER BY id DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM videos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('INSERT INTO videos (title, subtitle, title_es, subtitle_es, link) VALUES (:title, :subtitle, :title_es, :subtitle_es, :link)');
        return $stmt->execute([
            ':title' => $data['title'],
            ':subtitle' => $data['subtitle'],
            ':title_es' => $data['title_es'],
            ':subtitle_es' => $data['subtitle_es'],
            ':link' => $data['link'],
        ]);
    }

    public function update($id, $data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('UPDATE videos SET title = :title, subtitle = :subtitle, title_es = :title_es, subtitle_es = :subtitle_es, link = :link WHERE id = :id');
        return $stmt->execute([
            ':title' => $data['title'],
            ':subtitle' => $data['subtitle'],
            ':title_es' => $data['title_es'],
            ':subtitle_es' => $data['subtitle_es'],
            ':link' => $data['link'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM videos WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
