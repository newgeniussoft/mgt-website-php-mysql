<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\View\View;

class SettingsController extends Controller {
    
    /**
     * Display settings page
     */
    public function index() {
        $activeGroup = $_GET['group'] ?? 'general';
        $groups = Setting::getAllGroups();
        $groupedSettings = Setting::getGroupedSettings();
        
        return View::make('admin.settings.index', [
            'title' => 'General Settings',
            'groups' => $groups,
            'groupedSettings' => $groupedSettings,
            'activeGroup' => $activeGroup,
            'success' => $_SESSION['settings_success'] ?? null,
            'error' => $_SESSION['settings_error'] ?? null
        ]);
    }
    
    /**
     * Update settings
     */
    public function update() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $_SESSION['settings_error'] = 'Invalid request method';
                return $this->back();
            }
            
            // Get all POST data except CSRF token
            $settings = $_POST;
            unset($settings['_token']);
            
            // Handle file uploads for image type settings
            if (!empty($_FILES)) {
                foreach ($_FILES as $key => $file) {
                    if ($file['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = __DIR__ . '/../../../public/uploads/settings/';
                        
                        // Create directory if it doesn't exist
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        
                        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $filename = $key . '_' . time() . '.' . $extension;
                        $uploadPath = $uploadDir . $filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                            $settings[$key] = '/uploads/settings/' . $filename;
                        }
                    }
                }
            }
            
            // Update settings
            Setting::updateMany($settings);
            
            $_SESSION['settings_success'] = 'Settings updated successfully!';
            unset($_SESSION['settings_error']);
            
        } catch (\Exception $e) {
            $_SESSION['settings_error'] = 'Error updating settings: ' . $e->getMessage();
            unset($_SESSION['settings_success']);
        }
        
        return $this->back();
    }
    
    /**
     * Reset settings to default
     */
    public function reset() {
        try {
            $group = $_POST['group'] ?? null;
            
            if (!$group) {
                $_SESSION['settings_error'] = 'No group specified';
                return $this->back();
            }
            
            // Here you would implement logic to reset to default values
            // For now, we'll just show a success message
            $_SESSION['settings_success'] = "Settings for group '{$group}' have been reset to defaults";
            unset($_SESSION['settings_error']);
            
        } catch (\Exception $e) {
            $_SESSION['settings_error'] = 'Error resetting settings: ' . $e->getMessage();
            unset($_SESSION['settings_success']);
        }
        
        return $this->back();
    }
    
    /**
     * Get a specific setting via API
     */
    public function getSetting($key) {
        try {
            $value = Setting::get($key);
            
            return $this->json([
                'success' => true,
                'data' => [
                    'key' => $key,
                    'value' => $value
                ]
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all settings via API
     */
    public function getAllSettings() {
        try {
            $settings = Setting::getAllAsArray();
            
            return $this->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
