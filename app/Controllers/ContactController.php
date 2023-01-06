<?php declare(strict_types=1);

namespace App\Controllers;

use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\View;

class ContactController extends AbstractController
{
    // afficher view contact
    public function contact(): void
    {
        View::render('contact');
    }
}