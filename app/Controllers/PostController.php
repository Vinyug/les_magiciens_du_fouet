<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Post;
use Cocur\Slugify\Slugify;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication as Auth;
use VGuyomarch\Foundation\Exceptions\HttpException;
use VGuyomarch\Foundation\Session;
use VGuyomarch\Foundation\Validator;
use VGuyomarch\Foundation\View;

class PostController extends AbstractController
{
    // afficher view post
    public function create(): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }

        View::render('posts.createPost');
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
            'title' => ['required', ['lengthMin', 3]],
            'description' => ['required', ['lengthMin', 3]],
            'difficulty' => ['required', 'integer', 'intOneToFour'],
            'time' => ['required', 'integer', ['lengthMin', 1], ['lengthMax', 3]],
            'price' => ['required', 'integer', 'intOneToFour'],
            'ingredient' => ['required', ['lengthMin', 3]],
            'step' => ['required', ['lengthMin', 3]],
            'person' => ['required', 'integer', 'intOneToTwenty'],
            'cooker_id' => ['required', 'integer'],
            'file' => ['required_file', 'image', 'square'],
        ]);

        // action si invalide
        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('posts.create');
        }

        // action si validé
        $slug = $this->slugify($_POST['title']);
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
            sprintf('%s/public/img/posts/%s', ROOT, $filename)
        )) {
            Session::addFlash(Session::ERRORS, ['file' => [
                'Il y a eu un problème lors de l\'envoi. Retentez votre chance !',
            ]]);
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('posts.create');
        }

        // Insert un post
        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'difficulty' => $_POST['difficulty'],
            'price' => $_POST['price'],
            'time' => $_POST['time'],
            'ingredient' => $_POST['ingredient'],
            'step' => $_POST['step'],
            'person' => $_POST['person'],
            'cooker_id' => $_POST['cooker_id'],
            'img' => $filename,
            'slug' => $slug,
        ]);

        // status MAJ
        Session::addFlash(Session::STATUS, 'Votre post a été publié !');
        // redirection vers posts.show
        // $this->redirection('posts.showPost', ['slug' => $post->slug]);
    }

    // modifier post
    public function edit(string $slug): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }

        try {
            $post = Post::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException) {
            HttpException::render();
        }

        View::render('posts.editPost', [
            'post' => $post,
        ]);
    }
    
    // update post
    public function update(string $slug): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }
        
        $post = Post::where('slug', $slug)->firstOrFail();

        // règle Validator
        $validator = Validator::get($_POST);
        $validator->mapFieldsRules([
            'title' => ['required', ['lengthMin', 3]],
            'description' => ['required', ['lengthMin', 3]],
            'difficulty' => ['required', 'integer', 'intOneToFour'],
            'time' => ['required', 'integer', ['lengthMin', 1], ['lengthMax', 3]],
            'price' => ['required', 'integer', 'intOneToFour'],
            'ingredient' => ['required', ['lengthMin', 3]],
            'step' => ['required', ['lengthMin', 3]],
            'person' => ['required', 'integer', 'intOneToTwenty'],
            'cooker_id' => ['required', 'integer'],
        ]);

        // action si invalide
        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('posts.edit', ['slug' => $post->slug]);
        }

        // action si valide
        $post->fill([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'difficulty' => $_POST['difficulty'],
            'price' => $_POST['price'],
            'time' => $_POST['time'],
            'ingredient' => $_POST['ingredient'],
            'step' => $_POST['step'],
            'person' => $_POST['person'],
            'cooker_id' => $_POST['cooker_id'],
        ]);
        $post->save();

        Session::addFlash(Session::STATUS, 'Votre post a bien été mis à jour !');
        // redirection vers posts.show
        // $this->redirection('posts.show', ['slug' => $post->slug]);
    }

    // creation slug article avec cocur/slugify
    public function slugify(string $title): string 
    {
        $slugify = new Slugify();
        $slug = $slugify->slugify($title);
        // permettre d'avoir slug unique, si plusieurs titre en BDD sont identiques
        $i = 1;
        $unique_slug = $slug;
        while(Post::where('slug', $unique_slug)->exists()) {
            $unique_slug = sprintf('%s-%s', $slug, $i++);
        }
        return $unique_slug;
    }
}