<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\HelpController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Profile route
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');

// Dashboard route with authentication
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update-email', [ProfileController::class, 'updateEmail'])->name('profile.updateEmail');
    Route::patch('/profile/update-name', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Add route for fetching all users
    Route::get('/api/users', [ProfileController::class, 'getAllUsers']); // New route for users

    // Post routes
    Route::resource('posts', PostController::class)->except(['create', 'show']);
    Route::post('/posts/{post}/like', [PostController::class, 'likePost']);
    Route::post('/posts/{post}/comment', [PostController::class, 'addComment']);
    
    // Comment routes
    Route::patch('/posts/{postId}/comments/{commentId}', [PostController::class, 'editComment']);
    Route::delete('/posts/{postId}/comments/{commentId}', [PostController::class, 'deleteComment']);
    Route::delete('/posts/{post}/comments/{comment}', [PostController::class, 'destroyComment']);

    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications');
        Route::post('/mark-as-read/{notificationId}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::delete('/delete/{notificationId}', [NotificationController::class, 'destroy'])->name('notifications.delete');
        Route::delete('/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.deleteAllRead');
    });

    // Friends routes
    Route::prefix('friends')->group(function () {
        Route::get('/', [FriendController::class, 'index'])->name('friends');
        Route::post('/add/{friendId}', [FriendController::class, 'addFriend'])->name('friends.add');
        Route::post('/cancel/{friendId}', [FriendController::class, 'cancelFriendRequest'])->name('friends.cancel');
        Route::delete('/unfriend/{friendId}', [FriendController::class, 'unfriend'])->name('friends.unfriend');
        Route::post('/accept/{id}', [FriendController::class, 'acceptFriend'])->name('friends.accept');
        Route::delete('/reject/{id}', [FriendController::class, 'rejectFriend'])->name('friends.reject');
    });

    Route::middleware('auth')->prefix('messages')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('messages.index'); // View all messages
        Route::post('/send', [MessageController::class, 'send'])->name('messages.send'); // Send a message
        Route::get('/chat/{userId}', [MessageController::class, 'chat'])->name('messages.chat');

        Route::get('/retrieve/{userId}', [MessageController::class, 'retrieve'])->name('messages.retrieve'); // Retrieve messages
        Route::post('/read/{messageId}', [MessageController::class, 'markAsRead'])->name('messages.markAsRead'); // Mark message as read
        Route::get('/recent/{userId}', [MessageController::class, 'getRecentMessages'])->name('messages.recent'); // Get recent messages
        Route::get('/search', [MessageController::class, 'search'])->name('messages.search'); // Search for users
        Route::get('/messages/chat/{userId}', [MessageController::class, 'chat'])->name('messages.chat');

    });
    
    
    });
    


// Include authentication routes
require __DIR__.'/auth.php';
