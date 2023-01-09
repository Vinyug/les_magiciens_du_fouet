<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication as Auth;
use VGuyomarch\Foundation\Exceptions\HttpException;
use VGuyomarch\Foundation\Session;
use VGuyomarch\Foundation\Validator;
use VGuyomarch\Foundation\View;

class HomeController extends AbstractController
{
    public function index(): void
    {
        // si user non authenticate, redirect login form
        if(!Auth::check()) {
            $this->redirection('login.form');
        }

        // récupérer les datas de User pour compléter la view home (compte)
        $user = Auth::get();

        // View home
        View::render('home', [
            'user' => $user,
        ]);
    }

    // update name
    public function updateName(): void
    {
        // si user non authenticate, redirect login form
        if(!Auth::check()) {
            $this->redirection('login.form');
        }

        // règle Validator
        $validator = Validator::get($_POST);
        $validator->mapFieldsRules([
            'name' => ['required', ['lengthMin', 5]],
        ]);

        // action si invalide
        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('home');
        }

        // si validé
        $user = Auth::get();
        $user->name = $_POST['name'];
        $user->save();

        // status MAJ
        Session::addFlash(Session::STATUS, 'Votre nom a été mis à jour !');
        $this->redirection('home');
    }

    // update email
    public function updateEmail(): void
    {
        // si user non authenticate, redirect login form
        if(!Auth::check()) {
            $this->redirection('login.form');
        }

        // règle Validator
        $validator = Validator::get($_POST);
        $validator->mapFieldsRules([
            'email' => ['required', 'email', ['unique', 'email', 'users']],
        ]);

        // action si invalide
        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('home');
        }

        // si validé
        $user = Auth::get();
        $user->email = $_POST['email'];
        $user->save();

        // status MAJ
        Session::addFlash(Session::STATUS, 'Votre adresse email a été mise à jour !');
        $this->redirection('home');
    }

    // update password
    public function updatePassword(): void
    {
        // si user non authenticate, redirect login form
        if(!Auth::check()) {
            $this->redirection('login.form');
        }

        // règle Validator
        $validator = Validator::get($_POST);
        $validator->mapFieldsRules([
            'password_old' => ['required', 'password'],
            'password' => ['required', ['lengthMin', 8], ['equals', 'confirm-password']],
        ]);

        // action si invalide
        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            $this->redirection('home');
        }

        // si validé
        $user = Auth::get();
        $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user->save();

        // status MAJ
        Session::addFlash(Session::STATUS, 'Votre mot de passe a été mis à jour !');
        $this->redirection('home');
    }

    // view listUsers
    public function users(): void
    {
        // si user non admin, redirect login form
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }

        // afficher tous les users 
        $users = User::orderBy('id', 'desc')->get();

        // View listUsers
        View::render('auth.listUsers', [
            'users' => $users,
        ]);
    }

    // update user right
    public function userUpdate(int $id): void
    {
        if(!Auth::checkIsSuperAdmin()) {
            $this->redirection('login.form');
        }

        $user = User::where('id', $id)->firstOrFail();

        $validator = Validator::get($_POST);
        $validator->mapFieldsRules([
            'role' => ['userRight'],
        ]);

        if(!$validator->validate()) {
            Session::addFlash(Session::STATUS, 'Le droit de l\'utilisateur est invalide !');
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('home.users');
        }
        
        $user->fill([
            'role' => $_POST['role'],
        ]);
        $user->save();
        
        Session::addFlash(Session::STATUS, 'Le droit de l\'utilisateur a bien été mis à jour !');
        $this->redirection('home.users');

    }

    // delete user
    public function userDelete(int $id): void
    {
        if(!Auth::checkIsSuperAdmin()) {
            $this->redirection('login.form');
        }
        
        $user = User::where('id', $id)->firstOrFail();
        
        $user->delete();
        
        Session::addFlash(Session::STATUS, 'L\'utilisateur à été supprimé !');
        $this->redirection('home.users');

    }
}