<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Comment;
use App\Models\Cooker;
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
    // afficher view index
    public function index(): void
    {
        // afficher tous les posts sur index
        $posts = Post::orderBy('id', 'desc')->get();
        
        View::render('index', [
            'posts' => $posts,
        ]);
    }
    
    // accéder à un post
    public function show(string $slug): void
    {
        // select post selon slug
        try {
            $post = Post::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException) {
            HttpException::render();
        }
        
        // afficher tous les cookers sur show
        $cookers = Cooker::inRandomOrder()->limit(10)->get();

        View::render('posts.showPost', [
            'post' => $post,
            'cookers' => $cookers,
        ]);
    }

    // publier comment sur un post 
    public function comment(string $slug): void
    {
        if(!Auth::check()) {
            $this->redirection('login.form');
        }

        $post = Post::where('slug', $slug)->firstOrFail();

        $validator = Validator::get($_POST);
        $validator->mapFieldsRules([
            'comment' => ['required', ['lengthMin', 3]],
        ]);

        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('posts.show', ['slug' => $slug]);
        }
        
        Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'body' => $_POST['comment'],
        ]);
        
        Session::addFlash(Session::STATUS, 'Votre commentaire à été publié !');
        $this->redirection('posts.show', ['slug' => $slug]);
    }
    
    // supprimer comment sur un post 
    public function deleteComment(string $slug, int $id): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }
        
        $post = Post::where('slug', $slug)->firstOrFail();
        $comment = Comment::where('id', $id)->firstOrFail();
        
        $comment->delete();
        
        Session::addFlash(Session::STATUS, 'Le commentaire à été supprimé !');
        $this->redirection('posts.show', ['slug' => $post->slug]);

    }

    // supprimer post
    public function delete(string $slug): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }

        $post = Post::where('slug', $slug)->firstOrFail();
        
        // suppression de l'image dans le dossier puis du post en BDD
        unlink(sprintf('%s/public/img/posts/%s', ROOT, $post->img));
        $post->delete();
        
        $this->redirection('index');
        
    }
    
    // afficher view post
    public function create(): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }
        
        $cookers = Cooker::orderBy('id', 'desc')->get();

        View::render('posts.createPost', [
            'cookers' => $cookers,
        ]);
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
            'cooker_id' => ['required', 'integer', ['exist', 'id', 'cookers']],
            'visibility' => ['required', 'integer', ['min', 0], ['max', 1]],
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
            'time' => $_POST['time']*60,
            'ingredient' => $_POST['ingredient'],
            'step' => $_POST['step'],
            'person' => $_POST['person'],
            'cooker_id' => $_POST['cooker_id'],
            'img' => $filename,
            'slug' => $slug,
            'visibility' => $_POST['visibility'],
        ]);

        // status MAJ
        Session::addFlash(Session::STATUS, 'Votre post a été publié !');
        // redirection vers posts.show
        $this->redirection('posts.show', ['slug' => $post->slug]);
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

        $cookers = Cooker::orderBy('id', 'desc')->get();

        View::render('posts.editPost', [
            'post' => $post,
            'cookers' => $cookers,
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
            'cooker_id' => ['required', 'integer', ['exist', 'id', 'cookers']],
            'visibility' => ['required', 'integer', ['min', 0], ['max', 1]],
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
            'time' => $_POST['time']*60,
            'ingredient' => $_POST['ingredient'],
            'step' => $_POST['step'],
            'person' => $_POST['person'],
            'cooker_id' => $_POST['cooker_id'],
            'visibility' => $_POST['visibility'],
        ]);
        $post->save();

        Session::addFlash(Session::STATUS, 'Votre post a bien été mis à jour !');
        // redirection vers posts.show
        $this->redirection('posts.show', ['slug' => $post->slug]);
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