<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;

class TourPhoto extends Model
{
    protected $table = 'tour_photos';
    
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
            
            $stmt = $this->getConnection()->prepare($sql);
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
            $stmt = $this->getConnection()->prepare("SELECT * FROM tour_photos WHERE id = ?");
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
            $stmt = $this->getConnection()->prepare("SELECT * FROM tour_photos WHERE tour_id = ? AND day = ? ORDER BY sort_order ASC");
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
            
            $stmt = $this->getConnection()->prepare($sql);
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
            
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting gallery photos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create new tour photo (instance helper)
     */
    public function createPhoto($data)
    {
        try {
            $sql = "INSERT INTO tour_photos (tour_id, image, title, description, alt_text, type, day, sort_order, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->getConnection()->prepare($sql);
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
            
            return $result ? $this->getConnection()->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Error creating tour photo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update tour photo (instance helper)
     */
    public function updatePhoto($id, $data)
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
            
            $stmt = $this->getConnection()->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Error updating tour photo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete tour photo (instance helper)
     */
    public function deletePhoto($id)
    {
        try {
            // Get photo info before deletion for file cleanup
            $photo = $this->getById($id);
            
            $stmt = $this->getConnection()->prepare("DELETE FROM tour_photos WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            // Clean up physical file only if no other rows reference it
            if ($result && $photo && !empty($photo['image'])) {
                $check = $this->getConnection()->prepare("SELECT COUNT(*) FROM tour_photos WHERE image = ?");
                $check->execute([$photo['image']]);
                $remaining = (int)$check->fetchColumn();
                if ($remaining === 0) {
                    $filePath = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/uploads/' . $photo['image'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error deleting tour photo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete all photos for a specific tour (instance helper)
     */
    public function deletePhotosByTourId($tourId)
    {
        try {
            // Get all photos for cleanup
            $photos = $this->getByTourId($tourId);
            
            $stmt = $this->getConnection()->prepare("DELETE FROM tour_photos WHERE tour_id = ?");
            $result = $stmt->execute([$tourId]);
            
            // Clean up physical files
            if ($result) {
                foreach ($photos as $photo) {
                    if (!empty($photo['image'])) {
                        $check = $this->getConnection()->prepare("SELECT COUNT(*) FROM tour_photos WHERE image = ?");
                        $check->execute([$photo['image']]);
                        $remaining = (int)$check->fetchColumn();
                        if ($remaining === 0) {
                            $filePath = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/uploads/' . $photo['image'];
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
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
            $conn = $this->getConnection();
            $conn->beginTransaction();
            
            foreach ($photoOrders as $photoId => $sortOrder) {
                $stmt = $conn->prepare("UPDATE tour_photos SET sort_order = ? WHERE id = ?");
                $stmt->execute([$sortOrder, $photoId]);
            }
            
            $conn->commit();
            return true;
        } catch (PDOException $e) {
            $conn->rollBack();
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
            $conn = $this->getConnection();
            $conn->beginTransaction();
            
            // If tourId is provided, unset all featured photos for that tour first
            if ($tourId) {
                $stmt = $conn->prepare("UPDATE tour_photos SET is_featured = 0 WHERE tour_id = ?");
                $stmt->execute([$tourId]);
            }
            
            // Set the new featured photo
            $stmt = $conn->prepare("UPDATE tour_photos SET is_featured = 1 WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            $conn->commit();
            return $result;
        } catch (PDOException $e) {
            if (isset($conn)) {
                $conn->rollBack();
            }
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
            $stmt = $this->getConnection()->prepare("UPDATE tour_photos SET is_featured = 0 WHERE id = ?");
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
            
            $stmt = $this->getConnection()->prepare($sql);
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
            
            $stmt = $this->getConnection()->prepare($sql);
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
                
                $this->createPhoto($photo);
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
            $stmt = $this->getConnection()->prepare("SELECT * FROM tour_photos WHERE tour_id = ? AND (title LIKE ? OR description LIKE ? OR alt_text LIKE ?) ORDER BY sort_order ASC");
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
            
            // Resolve destination folder by tour name (prefer English within the group)
            $tour = \App\Models\Tour::getById($tourId);
            if (!$tour) {
                throw new Exception('Invalid tour');
            }
            $group = $tour['translation_group'] ?? '';
            $toursInGroup = $group ? \App\Models\Tour::getTranslations($group) : [];
            $canonical = $tour;
            if (is_array($toursInGroup) && count($toursInGroup) > 0) {
                foreach ($toursInGroup as $t) {
                    $lang = is_array($t) ? ($t['language'] ?? null) : ($t->language ?? null);
                    if ($lang === 'en') { $canonical = $t; break; }
                }
            }
            $nameVal = is_array($canonical) ? ($canonical['name'] ?? 'tour') : ($canonical->name ?? 'tour');
            $folderSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', $nameVal)));
            
            // Build upload directory: /uploads/tours/{name}/
            $baseDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/uploads/tours/' . $folderSlug . '/';
            if (!is_dir($baseDir)) {
                mkdir($baseDir, 0755, true);    
            }
            
            // Keep original base filename, ensure collision-safe
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $base = pathinfo($file['name'], PATHINFO_FILENAME);
            $candidate = $base . '.' . $ext;
            $filename = $candidate;
            $i = 1;
            while (file_exists($baseDir . $filename)) {
                $filename = $base . '-' . $i . '.' . $ext;
                $i++;
            }
            
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $baseDir . $filename)) {
                throw new Exception('Failed to upload file.');
            }
            
            // File path stored in DB (relative to /uploads)
            $imagePath = 'tours/' . $folderSlug . '/' . $filename;
            
            // Create a record for each tour in the same translation group
            $createdId = false;
            $toursToRecord = is_array($toursInGroup) && count($toursInGroup) > 0 ? $toursInGroup : [ $tour ];
            
            foreach ($toursToRecord as $t) {
                $recData = array_merge($data, [
                    'tour_id' => is_array($t) ? ($t['id'] ?? null) : ($t->id ?? null),
                    'image' => $imagePath,
                    'title' => $data['title'] ?? pathinfo($file['name'], PATHINFO_FILENAME),
                    'alt_text' => $data['alt_text'] ?? ($data['title'] ?? pathinfo($file['name'], PATHINFO_FILENAME))
                ]);
                if (!empty($recData['tour_id'])) {
                    $newId = $this->createPhoto($recData);
                    $tId = is_array($t) ? ($t['id'] ?? null) : ($t->id ?? null);
                    if ($tId == $tourId) {
                        $createdId = $newId;
                    }
                }
            }
            
            return $createdId ?: true;
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
            $conn = $this->getConnection();

            $where = '';
            $params = [];
            if ($tourId !== null) {
                $where = 'WHERE tour_id = ?';
                $params[] = $tourId;
            }

            // Total photos
            $stmt = $conn->prepare("SELECT COUNT(*) FROM tour_photos $where");
            $stmt->execute($params);
            $stats['total_photos'] = $stmt->fetchColumn();

            // Photos by type
            $sqlByType = "SELECT type, COUNT(*) as count FROM tour_photos $where GROUP BY type";
            $stmt = $conn->prepare($sqlByType);
            $stmt->execute($params);
            $stats['by_type'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            // Featured photos
            if ($tourId !== null) {
                $stmt = $conn->prepare("SELECT COUNT(*) FROM tour_photos WHERE tour_id = ? AND is_featured = 1");
                $stmt->execute([$tourId]);
            } else {
                $stmt = $conn->query("SELECT COUNT(*) FROM tour_photos WHERE is_featured = 1");
            }
            $stats['featured_photos'] = $stmt->fetchColumn();

            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting photo stats: " . $e->getMessage());
            return [];
        }
    }
}
