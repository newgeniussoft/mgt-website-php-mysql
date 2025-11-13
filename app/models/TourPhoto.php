<?php

namespace App\Models;

use PDO;
use PDOException;

class TourPhoto
{
    private $db;
    
    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }
    
    /**
     * Get all photos for a specific tour
     */
    public function getByTourId($tourId, $type = null)
    {
        try {
            $sql = "SELECT * FROM tour_photos WHERE tour_id = ?";
            $params = [$tourId];
            
            if ($type) {
                $sql .= " AND type = ?";
                $params[] = $type;
            }
            
            $sql .= " ORDER BY sort_order ASC, created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting tour photos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get photo by ID
     */
    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tour_photos WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting tour photo by ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get photos by tour and day
     */
    public function getByTourAndDay($tourId, $day)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tour_photos WHERE tour_id = ? AND day = ? ORDER BY sort_order ASC");
            $stmt->execute([$tourId, $day]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting photos by day: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get featured photos for a tour
     */
    public function getFeatured($tourId, $limit = null)
    {
        try {
            $sql = "SELECT * FROM tour_photos WHERE tour_id = ? AND is_featured = 1 ORDER BY sort_order ASC";
            $params = [$tourId];
            
            if ($limit) {
                $sql .= " LIMIT ?";
                $params[] = $limit;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting featured photos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get gallery photos for a tour
     */
    public function getGallery($tourId, $limit = null)
    {
        try {
            $sql = "SELECT * FROM tour_photos WHERE tour_id = ? AND type = 'gallery' ORDER BY sort_order ASC, created_at DESC";
            $params = [$tourId];
            
            if ($limit) {
                $sql .= " LIMIT ?";
                $params[] = $limit;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting gallery photos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create new tour photo
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO tour_photos (tour_id, image, title, description, alt_text, type, day, sort_order, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['tour_id'],
                $data['image'],
                $data['title'] ?? null,
                $data['description'] ?? null,
                $data['alt_text'] ?? null,
                $data['type'] ?? 'gallery',
                $data['day'] ?? null,
                $data['sort_order'] ?? 0,
                $data['is_featured'] ?? 0
            ]);
            
            return $result ? $this->db->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Error creating tour photo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update tour photo
     */
    public function update($id, $data)
    {
        try {
            $fields = [];
            $values = [];
            
            $allowedFields = ['tour_id', 'image', 'title', 'description', 'alt_text', 'type', 'day', 'sort_order', 'is_featured'];
            
            foreach ($data as $field => $value) {
                if (in_array($field, $allowedFields)) {
                    $fields[] = "$field = ?";
                    $values[] = $value;
                }
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $values[] = $id;
            $sql = "UPDATE tour_photos SET " . implode(', ', $fields) . " WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Error updating tour photo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete tour photo
     */
    public function delete($id)
    {
        try {
            // Get photo info before deletion for file cleanup
            $photo = $this->getById($id);
            
            $stmt = $this->db->prepare("DELETE FROM tour_photos WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            // Clean up physical file if deletion was successful
            if ($result && $photo && !empty($photo['image'])) {
                $filePath = $_SERVER['DOCUMENT_ROOT'] . '/storage/uploads/' . $photo['image'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error deleting tour photo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete all photos for a specific tour
     */
    public function deleteByTourId($tourId)
    {
        try {
            // Get all photos for cleanup
            $photos = $this->getByTourId($tourId);
            
            $stmt = $this->db->prepare("DELETE FROM tour_photos WHERE tour_id = ?");
            $result = $stmt->execute([$tourId]);
            
            // Clean up physical files
            if ($result) {
                foreach ($photos as $photo) {
                    if (!empty($photo['image'])) {
                        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/storage/uploads/' . $photo['image'];
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error deleting tour photos by tour ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Reorder photos
     */
    public function reorder($photoOrders)
    {
        try {
            $this->db->beginTransaction();
            
            foreach ($photoOrders as $photoId => $sortOrder) {
                $stmt = $this->db->prepare("UPDATE tour_photos SET sort_order = ? WHERE id = ?");
                $stmt->execute([$sortOrder, $photoId]);
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error reordering tour photos: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Set featured photo
     */
    public function setFeatured($id, $tourId = null)
    {
        try {
            $this->db->beginTransaction();
            
            // If tourId is provided, unset all featured photos for that tour first
            if ($tourId) {
                $stmt = $this->db->prepare("UPDATE tour_photos SET is_featured = 0 WHERE tour_id = ?");
                $stmt->execute([$tourId]);
            }
            
            // Set the new featured photo
            $stmt = $this->db->prepare("UPDATE tour_photos SET is_featured = 1 WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error setting featured photo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Unset featured photo
     */
    public function unsetFeatured($id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE tour_photos SET is_featured = 0 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error unsetting featured photo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get photos count by tour
     */
    public function getCountByTourId($tourId, $type = null)
    {
        try {
            $sql = "SELECT COUNT(*) FROM tour_photos WHERE tour_id = ?";
            $params = [$tourId];
            
            if ($type) {
                $sql .= " AND type = ?";
                $params[] = $type;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting photos count: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get photos by type
     */
    public function getByType($type, $tourId = null, $limit = null)
    {
        try {
            $sql = "SELECT * FROM tour_photos WHERE type = ?";
            $params = [$type];
            
            if ($tourId) {
                $sql .= " AND tour_id = ?";
                $params[] = $tourId;
            }
            
            $sql .= " ORDER BY sort_order ASC, created_at DESC";
            
            if ($limit) {
                $sql .= " LIMIT ?";
                $params[] = $limit;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting photos by type: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Duplicate photos for another tour
     */
    public function duplicateForTour($sourceTourId, $targetTourId)
    {
        try {
            $sourcePhotos = $this->getByTourId($sourceTourId);
            
            foreach ($sourcePhotos as $photo) {
                unset($photo['id']);
                unset($photo['created_at']);
                unset($photo['updated_at']);
                $photo['tour_id'] = $targetTourId;
                
                $this->create($photo);
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Error duplicating tour photos: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Search photos
     */
    public function search($tourId, $query)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tour_photos WHERE tour_id = ? AND (title LIKE ? OR description LIKE ? OR alt_text LIKE ?) ORDER BY sort_order ASC");
            $searchTerm = '%' . $query . '%';
            $stmt->execute([$tourId, $searchTerm, $searchTerm, $searchTerm]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching tour photos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get available photo types
     */
    public function getAvailableTypes()
    {
        return [
            'gallery' => 'Gallery',
            'itinerary' => 'Itinerary',
            'accommodation' => 'Accommodation',
            'activity' => 'Activity'
        ];
    }
    
    /**
     * Upload and create photo
     */
    public function uploadAndCreate($tourId, $file, $data = [])
    {
        try {
            // Validate file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.');
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'tour_' . $tourId . '_' . uniqid() . '.' . $extension;
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/uploads/tours/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $uploadPath . $filename)) {
                // Create database record
                $photoData = array_merge($data, [
                    'tour_id' => $tourId,
                    'image' => 'tours/' . $filename,
                    'title' => $data['title'] ?? pathinfo($file['name'], PATHINFO_FILENAME),
                    'alt_text' => $data['alt_text'] ?? $data['title'] ?? pathinfo($file['name'], PATHINFO_FILENAME)
                ]);
                
                return $this->create($photoData);
            } else {
                throw new Exception('Failed to upload file.');
            }
        } catch (Exception $e) {
            error_log("Error uploading tour photo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Validate photo data
     */
    public function validate($data)
    {
        $errors = [];
        
        if (empty($data['tour_id'])) {
            $errors[] = 'Tour ID is required';
        }
        
        if (empty($data['image'])) {
            $errors[] = 'Image is required';
        }
        
        if (!empty($data['type']) && !in_array($data['type'], array_keys($this->getAvailableTypes()))) {
            $errors[] = 'Invalid photo type';
        }
        
        if (!empty($data['day']) && (!is_numeric($data['day']) || $data['day'] < 1)) {
            $errors[] = 'Day must be a positive number';
        }
        
        return $errors;
    }
    
    /**
     * Get photo statistics
     */
    public function getStats($tourId = null)
    {
        try {
            $stats = [];
            
            $whereClause = $tourId ? "WHERE tour_id = $tourId" : "";
            
            // Total photos
            $stmt = $this->db->query("SELECT COUNT(*) FROM tour_photos $whereClause");
            $stats['total_photos'] = $stmt->fetchColumn();
            
            // Photos by type
            $stmt = $this->db->query("SELECT type, COUNT(*) as count FROM tour_photos $whereClause GROUP BY type");
            $stats['by_type'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Featured photos
            $stmt = $this->db->query("SELECT COUNT(*) FROM tour_photos $whereClause AND is_featured = 1");
            $stats['featured_photos'] = $stmt->fetchColumn();
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting photo stats: " . $e->getMessage());
            return [];
        }
    }
}
