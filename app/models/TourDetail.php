<?php

namespace App\Models;

use PDO;
use PDOException;

class TourDetail
{
    private $db;
    
    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }
    
    /**
     * Get all tour details for a specific tour
     */
    public function getByTourId($tourId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tour_details WHERE tour_id = ? ORDER BY day ASC, sort_order ASC");
            $stmt->execute([$tourId]);
            $details = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Parse JSON fields
            foreach ($details as &$detail) {
                $detail['activities'] = json_decode($detail['activities'] ?? '[]', true);
            }
            
            return $details;
        } catch (PDOException $e) {
            error_log("Error getting tour details: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get tour detail by ID
     */
    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tour_details WHERE id = ?");
            $stmt->execute([$id]);
            $detail = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($detail) {
                $detail['activities'] = json_decode($detail['activities'] ?? '[]', true);
            }
            
            return $detail;
        } catch (PDOException $e) {
            error_log("Error getting tour detail by ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get tour detail by tour ID and day
     */
    public function getByTourAndDay($tourId, $day)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tour_details WHERE tour_id = ? AND day = ?");
            $stmt->execute([$tourId, $day]);
            $detail = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($detail) {
                $detail['activities'] = json_decode($detail['activities'] ?? '[]', true);
            }
            
            return $detail;
        } catch (PDOException $e) {
            error_log("Error getting tour detail by day: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new tour detail
     */
    public function create($data)
    {
        try {
            // Encode activities array to JSON
            if (isset($data['activities']) && is_array($data['activities'])) {
                $data['activities'] = json_encode($data['activities']);
            }
            
            $sql = "INSERT INTO tour_details (tour_id, day, title, description, activities, meals, accommodation, transport, notes, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['tour_id'],
                $data['day'],
                $data['title'],
                $data['description'] ?? null,
                $data['activities'] ?? null,
                $data['meals'] ?? null,
                $data['accommodation'] ?? null,
                $data['transport'] ?? null,
                $data['notes'] ?? null,
                $data['sort_order'] ?? 0
            ]);
            
            return $result ? $this->db->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Error creating tour detail: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update tour detail
     */
    public function update($id, $data)
    {
        try {
            // Encode activities array to JSON
            if (isset($data['activities']) && is_array($data['activities'])) {
                $data['activities'] = json_encode($data['activities']);
            }
            
            $fields = [];
            $values = [];
            
            $allowedFields = ['tour_id', 'day', 'title', 'description', 'activities', 'meals', 'accommodation', 'transport', 'notes', 'sort_order'];
            
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
            $sql = "UPDATE tour_details SET " . implode(', ', $fields) . " WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Error updating tour detail: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete tour detail
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM tour_details WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting tour detail: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete all tour details for a specific tour
     */
    public function deleteByTourId($tourId)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM tour_details WHERE tour_id = ?");
            return $stmt->execute([$tourId]);
        } catch (PDOException $e) {
            error_log("Error deleting tour details by tour ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get maximum day number for a tour
     */
    public function getMaxDay($tourId)
    {
        try {
            $stmt = $this->db->prepare("SELECT MAX(day) FROM tour_details WHERE tour_id = ?");
            $stmt->execute([$tourId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting max day: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get tour details count for a tour
     */
    public function getCountByTourId($tourId)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM tour_details WHERE tour_id = ?");
            $stmt->execute([$tourId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting tour details count: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Reorder tour details
     */
    public function reorder($tourId, $dayOrders)
    {
        try {
            $this->db->beginTransaction();
            
            foreach ($dayOrders as $day => $sortOrder) {
                $stmt = $this->db->prepare("UPDATE tour_details SET sort_order = ? WHERE tour_id = ? AND day = ?");
                $stmt->execute([$sortOrder, $tourId, $day]);
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error reordering tour details: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Duplicate tour details for another tour
     */
    public function duplicateForTour($sourceTourId, $targetTourId)
    {
        try {
            $sourceDetails = $this->getByTourId($sourceTourId);
            
            foreach ($sourceDetails as $detail) {
                unset($detail['id']);
                unset($detail['created_at']);
                unset($detail['updated_at']);
                $detail['tour_id'] = $targetTourId;
                
                $this->create($detail);
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Error duplicating tour details: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get tour details with photos for a specific day
     */
    public function getDayWithPhotos($tourId, $day)
    {
        try {
            $detail = $this->getByTourAndDay($tourId, $day);
            
            if ($detail) {
                // Get photos for this specific day
                $stmt = $this->db->prepare("SELECT * FROM tour_photos WHERE tour_id = ? AND day = ? ORDER BY sort_order ASC");
                $stmt->execute([$tourId, $day]);
                $detail['photos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $detail;
        } catch (PDOException $e) {
            error_log("Error getting day with photos: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get tour summary (all days with basic info)
     */
    public function getTourSummary($tourId)
    {
        try {
            $stmt = $this->db->prepare("SELECT day, title, accommodation, meals FROM tour_details WHERE tour_id = ? ORDER BY day ASC");
            $stmt->execute([$tourId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting tour summary: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search tour details
     */
    public function search($tourId, $query)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tour_details WHERE tour_id = ? AND (title LIKE ? OR description LIKE ? OR activities LIKE ?) ORDER BY day ASC");
            $searchTerm = '%' . $query . '%';
            $stmt->execute([$tourId, $searchTerm, $searchTerm, $searchTerm]);
            
            $details = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Parse JSON fields
            foreach ($details as &$detail) {
                $detail['activities'] = json_decode($detail['activities'] ?? '[]', true);
            }
            
            return $details;
        } catch (PDOException $e) {
            error_log("Error searching tour details: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get meals summary for a tour
     */
    public function getMealsSummary($tourId)
    {
        try {
            $stmt = $this->db->prepare("SELECT day, meals FROM tour_details WHERE tour_id = ? AND meals IS NOT NULL ORDER BY day ASC");
            $stmt->execute([$tourId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting meals summary: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get accommodations summary for a tour
     */
    public function getAccommodationsSummary($tourId)
    {
        try {
            $stmt = $this->db->prepare("SELECT day, accommodation FROM tour_details WHERE tour_id = ? AND accommodation IS NOT NULL ORDER BY day ASC");
            $stmt->execute([$tourId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting accommodations summary: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Validate tour detail data
     */
    public function validate($data)
    {
        $errors = [];
        
        if (empty($data['tour_id'])) {
            $errors[] = 'Tour ID is required';
        }
        
        if (empty($data['day']) || !is_numeric($data['day']) || $data['day'] < 1) {
            $errors[] = 'Valid day number is required';
        }
        
        if (empty($data['title'])) {
            $errors[] = 'Title is required';
        }
        
        // Check if day already exists for this tour (for new records)
        if (!isset($data['id']) && !empty($data['tour_id']) && !empty($data['day'])) {
            $existing = $this->getByTourAndDay($data['tour_id'], $data['day']);
            if ($existing) {
                $errors[] = 'Day ' . $data['day'] . ' already exists for this tour';
            }
        }
        
        return $errors;
    }
}
