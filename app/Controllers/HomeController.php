<?php declare(strict_types=1);

namespace App\Controllers;

use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication as Auth;
use VGuyomarch\Foundation\View;

class HomeController extends AbstractController
{
    public function index(): void
    {
        // si user authenticate redirection vers home
        if(Auth::check()) {
            $this->redirection('home');
        }
        
        View::render('auth.register');
    }
}