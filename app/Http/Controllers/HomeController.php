<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\View\View;
use App\Http\Controllers\Controller;

/**
 * HomeController serves the main pages of the application.
 */
class HomeController extends Controller {
    /**
     * Display the main page with a list of users.
     *
     * @return View The main page view.
     */
    public function index() {
        return View::make('client.pages.index', [
            'title' => __('messages.welcome'),
            'greeting' => __('messages.hello', ['name' => 'John']),
            'users' => User::all(),
        ]);
    }

    /**
     * Display a specific user's page.
     *
     * @param int $id The ID of the user.
     * @return View The user's page view.
     */
    public function show(int $id) {
        $user = User::find($id);
        return View::make('users.show', compact('user'));
    }

    /**
     * Display the about page.
     *
     * @return View The about page view.
     */
    public function about() {
        return View::make('client.pages.about', [
            'title' => __('messages.menu.about'),
        ]);
    }

    /**
     * Display the contact page.
     *
     * @return View The contact page view.
     */
    public function contact() {
        return View::make('client.pages.contact', [
            'title' => __('messages.menu.contact'),
        ]);
    }
}