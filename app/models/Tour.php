<?php

namespace App\Models;

use PDO;
use PDOException;

class Tour
{
    private $db;
    
    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }
    
    /**
     * Get all tours with optional filters
     */
    public function getAll($filters = [])
    {
        try {
            $sql = "SELECT * FROM tours WHERE 1=1";
            $params = [];
            
            if (isset($filters['language'])) {
                $sql .= " AND language = ?";
                $params[] = $filters['language'];
            }
            
            if (isset($filters['status'])) {
                $sql .= " AND status = ?";
                $params[] = $filters['status'];
            }
            
            if (isset($filters['category'])) {
                $sql .= " AND category = ?";
                $params[] = $filters['category'];
            }
            
            if (isset($filters['featured'])) {
                $sql .= " AND featured = ?";
                $params[] = $filters['featured'];
            }
            
            if (isset($filters['search'])) {
                $sql .= " AND (title LIKE ? OR description LIKE ? OR location LIKE ?)";
                $searchTerm = '%' . $filters['search'] . '%';
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $sql .= " ORDER BY sort_order ASC, created_at DESC";
            
            if (isset($filters['limit'])) {
                $sql .= " LIMIT " . (int)$filters['limit'];
                if (isset($filters['offset'])) {
                    $sql .= " OFFSET " . (int)$filters['offset'];
                }
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting tours: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get tour by ID
     */
    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tours WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting tour by ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get tour by slug and language
     */
    public function getBySlug($slug, $language = 'en')
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tours WHERE slug = ? AND language = ? AND status = 'active'");
            $stmt->execute([$slug, $language]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting tour by slug: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get featured tours
     */
    public function getFeatured($language = 'en', $limit = 6)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tours WHERE featured = 1 AND status = 'active' AND language = ? ORDER BY sort_order ASC, created_at DESC LIMIT ?");
            $stmt->execute([$language, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting featured tours: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get tours by category
     */
    public function getByCategory($category, $language = 'en', $limit = null)
    {
        try {
            $sql = "SELECT * FROM tours WHERE category = ? AND status = 'active' AND language = ? ORDER BY sort_order ASC, created_at DESC";
            $params = [$category, $language];
            
            if ($limit) {
                $sql .= " LIMIT ?";
                $params[] = $limit;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting tours by category: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get tour translations
     */
    public function getTranslations($translationGroup)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tours WHERE translation_group = ? ORDER BY language");
            $stmt->execute([$translationGroup]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting tour translations: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get tour details (daily itinerary)
     */
    public function getDetails($tourId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tour_details WHERE tour_id = ? ORDER BY day ASC");
            $stmt->execute([$tourId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting tour details: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get tour photos
     */
    public function getPhotos($tourId, $type = null)
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
     * Get tour with full details (tour + details + photos)
     */
    public function getFullTour($id)
    {
        $tour = $this->getById($id);
        if ($tour) {
            $tour['details'] = $this->getDetails($id);
            $tour['photos'] = $this->getPhotos($id);
            $tour['gallery_photos'] = $this->getPhotos($id, 'gallery');
            
            // Parse JSON fields
            $tour['highlights'] = json_decode($tour['highlights'] ?? '[]', true);
            $tour['price_includes'] = json_decode($tour['price_includes'] ?? '[]', true);
            $tour['price_excludes'] = json_decode($tour['price_excludes'] ?? '[]', true);
        }
        return $tour;
    }
    
    /**
     * Create new tour
     */
    public function create($data)
    {
        try {
            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = $this->generateSlug($data['title'], $data['language'] ?? 'en');
            }
            
            // Generate translation group if not provided
            if (empty($data['translation_group'])) {
                $data['translation_group'] = 'tour_' . uniqid();
            }
            
            // Encode JSON fields
            if (isset($data['highlights']) && is_array($data['highlights'])) {
                $data['highlights'] = json_encode($data['highlights']);
            }
            if (isset($data['price_includes']) && is_array($data['price_includes'])) {
                $data['price_includes'] = json_encode($data['price_includes']);
            }
            if (isset($data['price_excludes']) && is_array($data['price_excludes'])) {
                $data['price_excludes'] = json_encode($data['price_excludes']);
            }
            
            $sql = "INSERT INTO tours (name, slug, language, translation_group, title, subtitle, short_description, description, itinerary, image, cover_image, highlights, price, price_includes, price_excludes, duration_days, max_participants, difficulty_level, category, location, status, featured, sort_order, meta_title, meta_description, meta_keywords) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['name'],
                $data['slug'],
                $data['language'] ?? 'en',
                $data['translation_group'],
                $data['title'],
                $data['subtitle'] ?? null,
                $data['short_description'] ?? null,
                $data['description'] ?? null,
                $data['itinerary'] ?? null,
                $data['image'] ?? null,
                $data['cover_image'] ?? null,
                $data['highlights'] ?? null,
                $data['price'] ?? null,
                $data['price_includes'] ?? null,
                $data['price_excludes'] ?? null,
                $data['duration_days'] ?? null,
                $data['max_participants'] ?? null,
                $data['difficulty_level'] ?? 'moderate',
                $data['category'] ?? null,
                $data['location'] ?? null,
                $data['status'] ?? 'active',
                $data['featured'] ?? 0,
                $data['sort_order'] ?? 0,
                $data['meta_title'] ?? null,
                $data['meta_description'] ?? null,
                $data['meta_keywords'] ?? null
            ]);
            
            return $result ? $this->db->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Error creating tour: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update tour
     */
    public function update($id, $data)
    {
        try {
            // Encode JSON fields
            if (isset($data['highlights']) && is_array($data['highlights'])) {
                $data['highlights'] = json_encode($data['highlights']);
            }
            if (isset($data['price_includes']) && is_array($data['price_includes'])) {
                $data['price_includes'] = json_encode($data['price_includes']);
            }
            if (isset($data['price_excludes']) && is_array($data['price_excludes'])) {
                $data['price_excludes'] = json_encode($data['price_excludes']);
            }
            
            $fields = [];
            $values = [];
            
            $allowedFields = ['name', 'slug', 'language', 'translation_group', 'title', 'subtitle', 'short_description', 'description', 'itinerary', 'image', 'cover_image', 'highlights', 'price', 'price_includes', 'price_excludes', 'duration_days', 'max_participants', 'difficulty_level', 'category', 'location', 'status', 'featured', 'sort_order', 'meta_title', 'meta_description', 'meta_keywords'];
            
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
            $sql = "UPDATE tours SET " . implode(', ', $fields) . " WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Error updating tour: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete tour
     */
    public function delete($id)
    {
        try {
            // Delete related records first (cascade should handle this, but being explicit)
            $this->db->prepare("DELETE FROM tour_photos WHERE tour_id = ?")->execute([$id]);
            $this->db->prepare("DELETE FROM tour_details WHERE tour_id = ?")->execute([$id]);
            
            $stmt = $this->db->prepare("DELETE FROM tours WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting tour: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate unique slug
     */
    public function generateSlug($title, $language = 'en')
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug, $language)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if slug exists
     */
    private function slugExists($slug, $language)
    {
        try {
            $stmt = $this->db->prepare("SELECT id FROM tours WHERE slug = ? AND language = ?");
            $stmt->execute([$slug, $language]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Get available categories
     */
    public function getCategories($language = 'en')
    {
        try {
            $stmt = $this->db->prepare("SELECT DISTINCT category FROM tours WHERE category IS NOT NULL AND category != '' AND language = ? ORDER BY category");
            $stmt->execute([$language]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get available locations
     */
    public function getLocations($language = 'en')
    {
        try {
            $stmt = $this->db->prepare("SELECT DISTINCT location FROM tours WHERE location IS NOT NULL AND location != '' AND language = ? ORDER BY location");
            $stmt->execute([$language]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error getting locations: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get available languages
     */
    public function getAvailableLanguages()
    {
        return [
            'en' => 'English',
            'es' => 'Español'
        ];
    }
    
    /**
     * Search tours
     */
    public function search($query, $language = 'en', $filters = [])
    {
        try {
            $sql = "SELECT * FROM tours WHERE language = ? AND status = 'active' AND (title LIKE ? OR description LIKE ? OR short_description LIKE ? OR location LIKE ? OR category LIKE ?)";
            $params = [$language];
            
            $searchTerm = '%' . $query . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            
            // Add additional filters
            if (isset($filters['category'])) {
                $sql .= " AND category = ?";
                $params[] = $filters['category'];
            }
            
            if (isset($filters['min_price'])) {
                $sql .= " AND price >= ?";
                $params[] = $filters['min_price'];
            }
            
            if (isset($filters['max_price'])) {
                $sql .= " AND price <= ?";
                $params[] = $filters['max_price'];
            }
            
            if (isset($filters['difficulty_level'])) {
                $sql .= " AND difficulty_level = ?";
                $params[] = $filters['difficulty_level'];
            }
            
            $sql .= " ORDER BY featured DESC, sort_order ASC, created_at DESC";
            
            if (isset($filters['limit'])) {
                $sql .= " LIMIT " . (int)$filters['limit'];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching tours: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get tour statistics
     */
    public function getStats()
    {
        try {
            $stats = [];
            
            // Total tours
            $stmt = $this->db->query("SELECT COUNT(*) FROM tours");
            $stats['total_tours'] = $stmt->fetchColumn();
            
            // Active tours
            $stmt = $this->db->query("SELECT COUNT(*) FROM tours WHERE status = 'active'");
            $stats['active_tours'] = $stmt->fetchColumn();
            
            // Featured tours
            $stmt = $this->db->query("SELECT COUNT(*) FROM tours WHERE featured = 1");
            $stats['featured_tours'] = $stmt->fetchColumn();
            
            // Tours by language
            $stmt = $this->db->query("SELECT language, COUNT(*) as count FROM tours GROUP BY language");
            $stats['by_language'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Tours by category
            $stmt = $this->db->query("SELECT category, COUNT(*) as count FROM tours WHERE category IS NOT NULL GROUP BY category ORDER BY count DESC");
            $stats['by_category'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting tour stats: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Duplicate tour
     */
    public function duplicate($id, $newLanguage = null)
    {
        try {
            $tour = $this->getById($id);
            if (!$tour) {
                return false;
            }
            
            // Remove ID and update fields for duplication
            unset($tour['id']);
            unset($tour['created_at']);
            unset($tour['updated_at']);
            
            if ($newLanguage) {
                $tour['language'] = $newLanguage;
                $tour['slug'] = $this->generateSlug($tour['title'], $newLanguage);
            } else {
                $tour['name'] = $tour['name'] . ' (Copy)';
                $tour['title'] = $tour['title'] . ' (Copy)';
                $tour['slug'] = $this->generateSlug($tour['title'], $tour['language']);
                $tour['translation_group'] = 'tour_' . uniqid();
            }
            
            return $this->create($tour);
        } catch (PDOException $e) {
            error_log("Error duplicating tour: " . $e->getMessage());
            return false;
        }
    }
}
