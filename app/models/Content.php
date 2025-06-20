<?php
// app/models/Content.php
require_once __DIR__ . '/../config/database.php';
class Content {
    private $db;
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    public function allByPage($page) {
        $stmt = $this->db->prepare('SELECT * FROM contents WHERE page = ?');
        $stmt->execute([$page]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($data) {
        $stmt = $this->db->prepare('INSERT INTO contents (page, type, val) VALUES (?, ?, ?)');
        return $stmt->execute([$data['page'], $data['type'], $data['val']]);
    }
    public function update($id, $data) {
        $stmt = $this->db->prepare('UPDATE contents SET type = ?, val = ? WHERE id = ?');
        return $stmt->execute([$data['type'], $data['val'], $id]);
    }
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM contents WHERE id = ?');
        return $stmt->execute([$id]);
    }
    public function find($id) {
        $stmt = $this->db->prepare('SELECT * FROM contents WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
