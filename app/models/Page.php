<?php
// Page.php - Model for the 'pages' table
require_once __DIR__ . '/../config/database.php';

class Page {
    private $db;
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM pages ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByPath($path) {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE path = ?");
        $stmt->execute([$path]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO pages (path, menu_title, menu_title_es, title, title_es, meta_title, meta_title_es, meta_description, meta_description_es, meta_keywords, meta_keywords_es, meta_image, title_h1, title_h1_es, title_h2, title_h2_es, content, content_es, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        return $stmt->execute([
            $data['path'], $data['menu_title'], $data['menu_title_es'], $data['title'], $data['title_es'], $data['meta_title'], $data['meta_title_es'],
            $data['meta_description'], $data['meta_description_es'], $data['meta_keywords'], $data['meta_keywords_es'],
            $data['meta_image'], $data['title_h1'], $data['title_h1_es'], $data['title_h2'], $data['title_h2_es'],
            $data['content'], $data['content_es']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE pages SET path=?, menu_title=?, menu_title_es=?, title=?, title_es=?, meta_title=?, meta_title_es=?, meta_description=?, meta_description_es=?, meta_keywords=?, meta_keywords_es=?, meta_image=?, title_h1=?, title_h1_es=?, title_h2=?, title_h2_es=?, content=?, content_es=?, updated_at=NOW() WHERE id=?");
        return $stmt->execute([
            $data['path'], $data['menu_title'], $data['menu_title_es'], $data['title'], $data['title_es'], $data['meta_title'], $data['meta_title_es'],
            $data['meta_description'], $data['meta_description_es'], $data['meta_keywords'], $data['meta_keywords_es'],
            $data['meta_image'], $data['title_h1'], $data['title_h1_es'], $data['title_h2'], $data['title_h2_es'],
            $data['content'], $data['content_es'], $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM pages WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
