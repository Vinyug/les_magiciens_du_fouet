<?php declare(strict_types=1);

namespace App\Controllers;

use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Session;
use VGuyomarch\Foundation\Validator;
use VGuyomarch\Foundation\View;

class ContactController extends AbstractController
{
    // afficher view contact
    public function contact(): void
    {
        
        View::render('contact');
    }
    
    
    public function mail(): void
    {
        // règle Validator
        $validator = Validator::get($_POST);
        $validator->mapFieldsRules([
            'firstname' => ['required', ['lengthMin', 3], ['lengthMax', 20]],
            'lastname' => ['required', ['lengthMin', 3], ['lengthMax', 20]],
            'email' => ['required', 'email', ['lengthMin', 6], ['lengthMax', 80]],
            'phone' => ['integer', ['length', 10]],
            'message' => ['required', ['lengthMin', 3], ['lengthMax', 600]],
        ]);

        // action si invalide
        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            Session::addFlash(Session::OLD, $_POST);
            Session::addFlash(Session::STATUS, 'Vous devez compléter les champs requis !');
            $this->redirection('contact');
        }

        // action si valide
        // Envoyer un mail

        // status MAJ
        Session::addFlash(Session::STATUS, 'Votre message a bien été envoyé !');
        // redirection vers contact
        $this->redirection('contact');
    }
}