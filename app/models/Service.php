<?php
class Service extends Model {
    protected $table = 'services';
    protected $primaryKey = 'id';

    public $id;
    public $image;
    public $title;
    public $title_es;
    public $subtitle;
    public $subtitle_es;

    public function __construct($data = []) {
        parent::__construct($this->table);
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function all() {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM services ORDER BY id DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM services WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('INSERT INTO services (image, title, title_es, subtitle, subtitle_es) VALUES (:image, :title, :title_es, :subtitle, :subtitle_es)');
        return $stmt->execute([
            ':image' => $data['image'],
            ':title' => $data['title'],
            ':title_es' => $data['title_es'],
            ':subtitle' => $data['subtitle'],
            ':subtitle_es' => $data['subtitle_es'],
        ]);
    }

    public function update($id, $data) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('UPDATE services SET image = :image, title = :title, title_es = :title_es, subtitle = :subtitle, subtitle_es = :subtitle_es WHERE id = :id');
        return $stmt->execute([
            ':image' => $data['image'],
            ':title' => $data['title'],
            ':title_es' => $data['title_es'],
            ':subtitle' => $data['subtitle'],
            ':subtitle_es' => $data['subtitle_es'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM services WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
