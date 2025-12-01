<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Page;
use App\Models\Content;

class SectionController extends Controller {
    
    /**
     * Display sections for a page
     */
    public function index() {
        $pageId = $_GET['page_id'] ?? null;
        
        if (!$pageId) {
            $_SESSION['error'] = 'Page ID is required';
            return redirect(admin_url('pages'));
        }
        
        $page = Page::find($pageId);
        
        if (!$page) {
            $_SESSION['error'] = 'Page not found';
            return redirect(admin_url('pages'));
        }
        
        $sections = Section::getByPage($pageId, false);
        
        return view('admin.sections.index', [
            'title' => 'Sections - ' . $page->title,
            'page' => $page,
            'sections' => $sections
        ]);
    }
    
    /**
     * Show create section form
     */
    public function create() {
        $pageId = $_GET['page_id'] ?? null;
        
        if (!$pageId) {
            $_SESSION['error'] = 'Page ID is required';
            return redirect(admin_url('pages'));
        }
        
        $page = Page::find($pageId);
        
        if (!$page) {
            $_SESSION['error'] = 'Page not found';
            return redirect(admin_url('pages'));
        }
        
        return view('admin.sections.create', [
            'title' => 'Create Section',
            'page' => $page
        ]);
    }
    
    /**
     * Store new section
     */
    public function store() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('pages'));
        }
        
        $pageId = $_POST['page_id'] ?? null;
        
        if (!$pageId) {
            $_SESSION['error'] = 'Page ID is required';
            return redirect(admin_url('pages'));
        }
        
        // Validate required fields
        if (empty($_POST['name'])) {
            $_SESSION['error'] = 'Section name is required';
            return redirect(admin_url('sections/create?page_id=' . $pageId));
        }
        
        // Generate slug
        $slug = !empty($_POST['slug']) ? $_POST['slug'] : Section::generateSlug($_POST['name'], $pageId);
        
        // Get max order index
        $sections = Section::getByPage($pageId, false);
        $maxOrder = 0;
        foreach ($sections as $section) {
            if ($section->order_index > $maxOrder) {
                $maxOrder = $section->order_index;
            }
        }
        
        // Prepare data
        $data = [
            'page_id' => $pageId,
            'name' => $_POST['name'],
            'slug' => $slug,
            'type' => $_POST['type'] ?? 'content',
            'html_template' => $_POST['html_template'] ?? '',
            'css_styles' => $_POST['css_styles'] ?? '',
            'js_scripts' => $_POST['js_scripts'] ?? '',
            'settings' => !empty($_POST['settings']) ? $_POST['settings'] : '{}',
            'order_index' => $maxOrder + 1,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        try {
            $section = Section::create($data);
            $_SESSION['success'] = 'Section created successfully';
            return redirect(admin_url('sections/edit?id=' . $section->id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error creating section: ' . $e->getMessage();
            return redirect(admin_url('sections/create?page_id=' . $pageId));
        }
    }
    
    /**
     * Show edit section form
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Section ID is required';
            return redirect(admin_url('pages'));
        }
        
        $section = Section::find($id);
        
        if (!$section) {
            $_SESSION['error'] = 'Section not found';
            return redirect(admin_url('pages'));
        }
        
        $page = $section->getPage();
        $contents = $section->getContents();
        
        return view('admin.sections.edit', [
            'title' => 'Edit Section',
            'section' => $section,
            'page' => $page,
            'contents' => $contents
        ]);
    }
    
    /**
     * Update section
     */
    public function update() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('pages'));
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Section ID is required';
            return redirect(admin_url('pages'));
        }
        
        $section = Section::find($id);
        
        if (!$section) {
            $_SESSION['error'] = 'Section not found';
            return redirect(admin_url('pages'));
        }
        
        // Validate required fields
        if (empty($_POST['name'])) {
            $_SESSION['error'] = 'Section name is required';
            return redirect(admin_url('sections/edit?id=' . $id));
        }
        
        // Generate slug if changed
        $slug = !empty($_POST['slug']) ? $_POST['slug'] : Section::generateSlug($_POST['name'], $section->page_id, $id);
        
        // Update data
        $section->name = $_POST['name'];
        $section->slug = $slug;
        $section->type = $_POST['type'] ?? 'content';
        $section->html_template = $_POST['html_template'] ?? '';
        $section->css_styles = $_POST['css_styles'] ?? '';
        $section->js_scripts = $_POST['js_scripts'] ?? '';
        $section->settings = !empty($_POST['settings']) ? $_POST['settings'] : '{}';
        $section->is_active = isset($_POST['is_active']) ? 1 : 0;
        
        try {
            $section->save();
            $_SESSION['success'] = 'Section updated successfully';
            return redirect(admin_url('sections/edit?id=' . $id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating section: ' . $e->getMessage();
            return redirect(admin_url('sections/edit?id=' . $id));
        }
    }
    
    /**
     * Delete section
     */
    public function destroy() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('pages'));
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Section ID is required';
            return redirect(admin_url('pages'));
        }
        
        $section = Section::find($id);
        
        if (!$section) {
            $_SESSION['error'] = 'Section not found';
            return redirect(admin_url('pages'));
        }
        
        $pageId = $section->page_id;
        
        try {
            $section->delete();
            $_SESSION['success'] = 'Section deleted successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error deleting section: ' . $e->getMessage();
        }
        
        return redirect(admin_url('sections?page_id=' . $pageId));
    }
    
    /**
     * Reorder sections (AJAX)
     */
    public function reorder() {
        header('Content-Type: application/json');
        
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
            exit;
        }
        
        $pageId = $_POST['page_id'] ?? null;
        $sectionIds = $_POST['section_ids'] ?? [];
        
        if (!$pageId || empty($sectionIds)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }
        
        $result = Section::reorder($pageId, $sectionIds);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Sections reordered successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error reordering sections']);
        }
        exit;
    }
    
    /**
     * Add content to section
     */
    public function addContent() {
        $sectionId = $_GET['section_id'] ?? null;
        
        if (!$sectionId) {
            $_SESSION['error'] = 'Section ID is required';
            return redirect(admin_url('pages'));
        }
        
        $section = Section::find($sectionId);
        
        if (!$section) {
            $_SESSION['error'] = 'Section not found';
            return redirect(admin_url('pages'));
        }
        
        return view('admin.sections.add-content', [
            'title' => 'Add Content',
            'section' => $section
        ]);
    }
    
    /**
     * Store content
     */
    public function storeContent() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('pages'));
        }
        
        $sectionId = $_POST['section_id'] ?? null;
        
        if (!$sectionId) {
            $_SESSION['error'] = 'Section ID is required';
            return redirect(admin_url('pages'));
        }
        
        // Get max order index
        $contents = Content::getBySection($sectionId, false);
        $maxOrder = 0;
        foreach ($contents as $content) {
            if ($content->order_index > $maxOrder) {
                $maxOrder = $content->order_index;
            }
        }
        
        // Prepare data
        $data = [
            'section_id' => $sectionId,
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'content_type' => $_POST['content_type'] ?? 'html',
            'language' => $_POST['language'] ?? 'en',
            'order_index' => $maxOrder + 1,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        try {
            $content = Content::create($data);
            $_SESSION['success'] = 'Content added successfully';
            return redirect(admin_url('sections/edit?id=' . $sectionId));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error adding content: ' . $e->getMessage();
            return redirect(admin_url('sections/add-content?section_id=' . $sectionId));
        }
    }
    
    /**
     * Edit content
     */
    public function editContent() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Content ID is required';
            return redirect(admin_url('pages'));
        }
        
        $content = Content::find($id);
        
        if (!$content) {
            $_SESSION['error'] = 'Content not found';
            return redirect(admin_url('pages'));
        }
        
        $section = $content->getSection();
        
        return view('admin.sections.add-content', [
            'title' => 'Edit Content',
            'content' => $content,
            'section' => $section
        ]);
    }
    
    /**
     * Update content
     */
    public function updateContent() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('pages'));
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Content ID is required';
            return redirect(admin_url('pages'));
        }
        
        $content = Content::find($id);
        
        if (!$content) {
            $_SESSION['error'] = 'Content not found';
            return redirect(admin_url('pages'));
        }
        
        // Update data
        $content->title = $_POST['title'] ?? '';
        $content->content = $_POST['content'] ?? '';
        $content->content_type = $_POST['content_type'] ?? 'html';
        $content->language = $_POST['language'] ?? 'en';
        $content->is_active = isset($_POST['is_active']) ? 1 : 0;
        
        try {
            $content->save();
            $_SESSION['success'] = 'Content updated successfully';
            return redirect(admin_url('sections/edit-content?id=' . $id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating content: ' . $e->getMessage();
            return redirect(admin_url('sections/edit-content?id=' . $id));
        }
    }
    
    /**
     * Delete content
     */
    public function destroyContent() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('pages'));
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Content ID is required';
            return redirect(admin_url('pages'));
        }
        
        $content = Content::find($id);
        
        if (!$content) {
            $_SESSION['error'] = 'Content not found';
            return redirect(admin_url('pages'));
        }
        
        $sectionId = $content->section_id;
        
        try {
            $content->delete();
            $_SESSION['success'] = 'Content deleted successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error deleting content: ' . $e->getMessage();
        }
        
        return redirect(admin_url('sections/edit?id=' . $sectionId));
    }
}
