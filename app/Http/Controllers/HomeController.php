<?php
namespace App\Http\Controllers;

use App\View\View;
use App\Models\User;
use App\Http\Controllers\Controller;

class HomeController extends Controller {
    public function index() {
        return View::make('welcome', [
            'title' => 'Welcome',
            'users' => User::all()
        ]);
    }
    
    public function show($id) {
        $user = User::find($id);
        return View::make('users.show', compact('user'));
    }
}