<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Cooker;
use Cocur\Slugify\Slugify;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication as Auth;
use VGuyomarch\Foundation\Exceptions\HttpException;
use VGuyomarch\Foundation\Session;
use VGuyomarch\Foundation\Validator;
use VGuyomarch\Foundation\View;

class CookerController extends AbstractController
{
    // accéder à un cooker
    public function show(string $slug): void
    {
        // select cooker selon slug
        try {
            $cooker = Cooker::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException) {
            HttpException::render();
        }

        View::render('cookers.showCooker', [
            'cooker' => $cooker,
        ]);
    }

    // supprimer post
    public function delete(string $slug): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }

        $cooker = Cooker::where('slug', $slug)->firstOrFail();

        // suppression de l'image dans le dossier puis du post en BDD
        unlink(sprintf('%s/public/img/cookers/%s', ROOT, $cooker->img));
        $cooker->delete();

        $this->redirection('index');

    }

    // afficher view cooker
    public function create(): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }

        View::render('cookers.createCooker');
    }

    
    public function store(): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }

        // règle Validator
        // le + permet de fusionner les arrays des superglobals
        $validator = Validator::get($_POST + $_FILES);
        $validator->mapFieldsRules([
            'firstname' => ['required', ['lengthMin', 3]],
            'lastname' => ['required', ['lengthMin', 3]],
            'file' => ['required_file', 'image', 'square'],
        ]);

        // action si invalide
        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('cookers.create');
        }

        // action si validé
        $name = $_POST['firstname'] . '-' . $_POST['lastname'];
        $slug = $this->slugify($name);
        // récupère l'extension de l'image
        $ext = pathinfo(
            $_FILES['file']['name'],
            PATHINFO_EXTENSION
        );
        // utilisation du slug pour renommer l'image
        $filename = sprintf('%s.%s', $slug, $ext);

        // enregistrer le fichier en BDD
        if(!move_uploaded_file(
            $_FILES['file']['tmp_name'],
            sprintf('%s/public/img/cookers/%s', ROOT, $filename)
        )) {
            Session::addFlash(Session::ERRORS, ['file' => [
                'Il y a eu un problème lors de l\'envoi. Retentez votre chance !',
            ]]);
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('cookers.create');
        }

        // Insert un post
        $cooker = Cooker::create([
            'user_id' => Auth::id(),
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'img' => $filename,
            'slug' => $slug,
        ]);

        // status MAJ
        Session::addFlash(Session::STATUS, 'Votre cuisinier a été publié !');
        // redirection vers cookers.show
        $this->redirection('cookers.show', ['slug' => $cooker->slug]);

    }

    // modifier cooker
    public function edit(string $slug): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }

        try {
            $cooker = Cooker::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException) {
            HttpException::render();
        }

        View::render('cookers.editCooker', [
            'cooker' => $cooker,
        ]);
    }
    
    // update post
    public function update(string $slug): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }
        
        $cooker = Cooker::where('slug', $slug)->firstOrFail();

        // règle Validator
        $validator = Validator::get($_POST);
        $validator->mapFieldsRules([
            'firstname' => ['required', ['lengthMin', 3]],
            'lastname' => ['required', ['lengthMin', 3]],
        ]);

        // action si invalide
        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('cookers.edit', ['slug' => $cooker->slug]);
        }

        // action si valide
        $cooker->fill([
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
        ]);
        $cooker->save();

        Session::addFlash(Session::STATUS, 'Votre cuisinier a bien été mis à jour !');
        // redirection vers cookers.show
        $this->redirection('cookers.show', ['slug' => $cooker->slug]);
    }

    // creation slug cooker avec cocur/slugify
    public function slugify(string $name): string 
    {
        $slugify = new Slugify();
        $slug = $slugify->slugify($name);
        // permettre d'avoir slug unique, si plusieurs titre en BDD sont identiques
        $i = 1;
        $unique_slug = $slug;
        while(Cooker::where('slug', $unique_slug)->exists()) {
            $unique_slug = sprintf('%s-%s', $slug, $i++);
        }
        return $unique_slug;
    }
}