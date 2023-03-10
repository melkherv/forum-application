<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Threads/Threads', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return redirect()->route('threads.index');
});

Route::get('/', [ThreadController::class, 'index'])->name('threads.index');
Route::get('threads/{thread}', [ThreadController::class, 'show'])->name('threads.show');

// Route::get('/threads', function () {
//     return Inertia::render('Threads/Threads');
// })->middleware(['auth', 'verified'])->name('threads.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('/threads', [ThreadController::class, 'index'])->name('threads.index');
    // Route::post('/threads', [ThreadController::class, 'create'])->name('threads.create');
    // Route::delete('/threads', [ThreadController::class, 'delete'])->name('thread-delete');

    Route::group(['prefix' => 'threads'], function () {
        Route::get('/create', [ThreadController::class, 'create'])->name('threads.create');
        Route::post('/', [ThreadController::class, 'store'])->name('threads.store');
        Route::get('/{thread}/edit', [ThreadController::class, 'edit'])->name('threads.edit');
        Route::put('/{thread}', [ThreadController::class, 'update'])->name('threads.update');
        Route::delete('/{thread}', [ThreadController::class, 'destroy'])->name('threads.destroy');
        Route::post('/search', [ThreadController::class, 'search'])->name('threads.search');
    });

    Route::group(['prefix' => 'comments'], function () {
        Route::get('/{commentableId}', [CommentController::class, 'comments'])->name('comments.comments');
        Route::post('/{commentableModel}/{commentableId}', [CommentController::class, 'store'])->name('comments.store');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    });

    Route::group(['prefix' => 'votes'], function () {
        Route::post('/{voteableModel}/{voteableType}/{voteableId}', [VoteController::class, 'store'])->name('votes.store');
        Route::get('/{voteableId}', [VoteController::class, 'votes'])->name('get.votes');
        Route::get('/{voteableId}/user', [VoteController::class, 'voteStatus'])->name('vote.status');
    });
});

require __DIR__ . '/auth.php';
