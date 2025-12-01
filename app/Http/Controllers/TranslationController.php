<?php

namespace App\Http\Controllers;

use App\Models\Translation;

class TranslationController extends Controller {
    public function index() {
        $q = $_GET['q'] ?? '';
        $locale = $_GET['locale'] ?? '';

        $instance = new Translation();
        $sql = "SELECT * FROM translations WHERE 1=1";
        $params = [];
        if ($q !== '') {
            $sql .= " AND `key` LIKE ?";
            $params[] = '%' . $q . '%';
        }
        if ($locale !== '') {
            $sql .= " AND `locale` = ?";
            $params[] = $locale;
        }
        $sql .= " ORDER BY `key` ASC, `locale` ASC";
        $stmt = $instance->getConnection()->prepare($sql);
        $stmt->execute($params);
        $translations = $stmt->fetchAll(\PDO::FETCH_CLASS, Translation::class);

        return view('admin.translations.index', [
            'title' => 'Translations',
            'translations' => $translations,
            'q' => $q,
            'filter_locale' => $locale
        ]);
    }

    public function create() {
        return view('admin.translations.edit', [
            'title' => 'Create Translation'
        ]);
    }

    public function store() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('translations/create'));
        }

        $key = trim($_POST['key'] ?? '');
        $locale = trim($_POST['locale'] ?? '');
        $value = $_POST['value'] ?? '';

        if ($key === '' || $locale === '') {
            $_SESSION['error'] = 'Key and Locale are required';
            return redirect(admin_url('translations/create'));
        }

        try {
            Translation::setValue($key, $locale, $value);
            $_SESSION['success'] = 'Translation saved';
            return redirect(admin_url('translations'));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error saving translation: ' . $e->getMessage();
            return redirect(admin_url('translations/create'));
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Translation ID is required';
            return redirect(admin_url('translations'));
        }
        $translation = Translation::find($id);
        if (!$translation) {
            $_SESSION['error'] = 'Translation not found';
            return redirect(admin_url('translations'));
        }
        return view('admin.translations.edit', [
            'title' => 'Edit Translation',
            'translation' => $translation
        ]);
    }

    public function update() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('translations'));
        }
        $id = $_POST['id'] ?? null;
        $key = trim($_POST['key'] ?? '');
        $locale = trim($_POST['locale'] ?? '');
        $value = $_POST['value'] ?? '';
        if (!$id || $key === '' || $locale === '') {
            $_SESSION['error'] = 'ID, Key and Locale are required';
            return redirect(admin_url('translations'));
        }
        $instance = new Translation();
        try {
            $stmt = $instance->getConnection()->prepare("UPDATE translations SET `key` = ?, `locale` = ?, `value` = ?, `updated_at` = NOW() WHERE id = ?");
            $stmt->execute([$key, $locale, $value, $id]);
            $_SESSION['success'] = 'Translation updated';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating translation: ' . $e->getMessage();
        }
        return redirect(admin_url('translations/edit?id=' . $id));
    }

    public function destroy() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('translations'));
        }
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Translation ID is required';
            return redirect(admin_url('translations'));
        }
        $instance = new Translation();
        try {
            $stmt = $instance->getConnection()->prepare("DELETE FROM translations WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['success'] = 'Translation deleted';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error deleting translation: ' . $e->getMessage();
        }
        return redirect(admin_url('translations'));
    }
}
