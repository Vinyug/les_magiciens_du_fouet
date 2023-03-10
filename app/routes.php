<?php declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\ContactController;
use App\Controllers\CookerController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use VGuyomarch\Foundation\Router\Route;

// routage avec symphony rooting
// Extraire l'URL et regarder l'URI
// On pourra demander au rooter quelle action effectuer

return [
    // Authentification
    'register.form' => Route::get('/inscription', [AuthController::class, 'registerForm']),
    'register.request' => Route::post('/inscription', [AuthController::class, 'register']),
    'login.form' => Route::get('/connexion', [AuthController::class, 'loginForm']),
    'login.request' => Route::post('/connexion', [AuthController::class, 'login']),
    'logout' => Route::post('/deconnexion', [AuthController::class, 'logout']),
    
    // Espace membre
    'home' => Route::get('/compte', [HomeController::class, 'index']),
    'home.updateName' => Route::patch('/compte', [HomeController::class, 'updateName']),
    'home.updateEmail' => Route::patch('/compte/email', [HomeController::class, 'updateEmail']),
    'home.updatePassword' => Route::patch('/compte/password', [HomeController::class, 'updatePassword']),
    'home.users' => Route::get('/compte/utilisateurs', [HomeController::class, 'users']),
    'home.userUpdate' => Route::patch('/compte/utilisateurs/{id}', [HomeController::class, 'userUpdate']),
    'home.userDelete' => Route::delete('/compte/utilisateurs/{id}', [HomeController::class, 'userDelete']),
    
    // Post
    'index' => Route::get('/', [PostController::class, 'index']),
    'posts.create' => Route::get('/posts/creer', [PostController::class, 'create']),
    'posts.store' => Route::post('/posts/creer', [PostController::class, 'store']),
    'posts.show' => Route::get('/posts/{slug}', [PostController::class, 'show']),
    'posts.comment' => Route::post('/posts/{slug}', [PostController::class, 'comment']),
    'posts.delete' => Route::delete('/posts/{slug}', [PostController::class, 'delete']),
    'posts.edit' => Route::get('/posts/{slug}/modifier', [PostController::class, 'edit']),
    'posts.update' => Route::patch('/posts/{slug}/modifier', [PostController::class, 'update']),
    'posts.showComment' => Route::patch('/posts/{slug}/{id}', [PostController::class, 'showComment']),
    'posts.deleteComment' => Route::delete('/posts/{slug}/{id}', [PostController::class, 'deleteComment']),

    //Cooker
    'cookers' => Route::get('/cookers', [CookerController::class, 'cookers']),
    'cookers.create' => Route::get('/cookers/creer', [CookerController::class, 'create']),
    'cookers.store' => Route::post('/cookers/creer', [CookerController::class, 'store']),
    'cookers.show' => Route::get('/cookers/{slug}', [CookerController::class, 'show']),
    'cookers.delete' => Route::delete('/cookers/{slug}', [CookerController::class, 'delete']),
    'cookers.edit' => Route::get('/cookers/{slug}/modifier', [CookerController::class, 'edit']),
    'cookers.update' => Route::patch('/cookers/{slug}/modifier', [CookerController::class, 'update']),
    
    //Contact
    'contact' => Route::get('/contact', [ContactController::class, 'contact']),
    'contact.mail' => Route::post('/contact', [ContactController::class, 'mail']),

];