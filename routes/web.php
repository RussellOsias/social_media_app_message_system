<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\HelpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;

// Home route
Route::get('/', function () {
    return view('welcome'); // Ensure you have welcome.blade.php
})->name('home');

// Dashboard route with authentication
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update-email', [ProfileController::class, 'updateEmail'])->name('profile.updateEmail');
    Route::patch('/profile/update-name', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-bio', [ProfileController::class, 'updateBio'])->name('profile.updateBio');
    Route::post('/profile/update-details', [ProfileController::class, 'updateDetails'])->name('profile.updateDetails');
    Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.updateProfilePicture');
    Route::get('profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    // Route for fetching all users (API call)
    Route::get('/api/users', [ProfileController::class, 'getAllUsers']);

    // Post routes
    Route::resource('posts', PostController::class)->except(['create', 'show']);
    Route::post('/posts/{post}/like', [PostController::class, 'likePost'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'addComment'])->name('posts.comment');

    // Comment routes
    Route::patch('/posts/{postId}/comments/{commentId}', [PostController::class, 'editComment']);
    Route::delete('/posts/{postId}/comments/{commentId}', [PostController::class, 'deleteComment']);
    Route::delete('/posts/{post}/comments/{comment}', [PostController::class, 'destroyComment']);
    Route::patch('/posts/{id}', [PostController::class, 'update']);
    Route::patch('/posts/{id}/media', [PostController::class, 'updateMedia']);

    // Group routes
    Route::resource('groups', GroupController::class);
    Route::get('groups/{group}/add-user', [GroupController::class, 'addUserForm'])->name('groups.addUserForm');
    Route::post('groups/{group}/add-user', [GroupController::class, 'addUser'])->name('groups.addUser');
    Route::delete('/groups/{group}/remove-user/{user}', [GroupController::class, 'removeUser'])->name('groups.removeUser');
    Route::post('/groups/{group}/join-request', [GroupController::class, 'joinRequest'])->name('groups.joinRequest');
    Route::patch('/groups/{group}/approve-request/{user}', [GroupController::class, 'approveRequest'])->name('groups.approveRequest');
    Route::delete('/groups/{group}/deny-request/{user}', [GroupController::class, 'denyRequest'])->name('groups.denyRequest');
    Route::post('/groups/{group}/posts', [GroupController::class, 'storePost'])->name('groups.storePost');
    Route::post('/groups/posts/{post}/comments', [GroupController::class, 'storeComment'])->name('groups.comments.store');
    Route::post('/groups/posts/{post}/like', [GroupController::class, 'likePost'])->name('groups.posts.like');

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
        Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
        Route::get('/', [FriendController::class, 'index'])->name('friends');
        Route::post('/add/{friendId}', [FriendController::class, 'addFriend'])->name('friends.add');
        Route::post('/cancel/{friendId}', [FriendController::class, 'cancelFriendRequest'])->name('friends.cancel');
        Route::delete('/unfriend/{friendId}', [FriendController::class, 'unfriend'])->name('friends.unfriend');
        Route::post('/accept/{id}', [FriendController::class, 'acceptFriend'])->name('friends.accept');
        Route::delete('/reject/{id}', [FriendController::class, 'rejectFriend'])->name('friends.reject');
    });

    // Message routes
    Route::prefix('messages')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('messages.index');
        Route::post('/send', [MessageController::class, 'send'])->name('messages.send');
        Route::get('/chat/{userId}', [MessageController::class, 'chat'])->name('messages.chat');
        Route::get('/retrieve/{userId}', [MessageController::class, 'retrieve'])->name('messages.retrieve');
        Route::post('/read/{messageId}', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');
        Route::get('/recent/{userId}', [MessageController::class, 'getRecentMessages'])->name('messages.recent');
        Route::put('/{id}/edit', [MessageController::class, 'edit'])->name('messages.edit');
        Route::delete('/{id}/delete', [MessageController::class, 'destroy'])->name('messages.delete');
        Route::delete('/conversation/{id}', [MessageController::class, 'deleteConversation'])->name('messages.delete.conversation');
        Route::delete('/messages/delete-all/{userId}', [MessageController::class, 'deleteAllMessagesWithUser'])->name('messages.delete.all');
      
        Route::get('/search', [MessageController::class, 'search'])->name('messages.search');
    });
    
    //Story
    Route::middleware('auth')->group(function () {
        Route::post('/stories', [StoryController::class, 'store']);
        Route::get('/story/{id}', [StoryController::class, 'show'])->name('story.show');
        Route::get('/stories/{id}', [StoryController::class, 'show'])->name('story.show');
        Route::delete('/stories/{id}', [StoryController::class, 'delete'])->name('story.delete');
        Route::delete('/story/{id}', [StoryController::class, 'delete'])->name('story.delete');

        // In routes/web.php
Route::get('/stories/{id}', [StoryController::class, 'show'])->name('stories.show');

Route::get('/story/{id}', [StoryController::class, 'show'])->name('story.show');
        Route::get('/stories', [StoryController::class, 'index']);
    });
    // Footer routes
    Route::get('/privacy', function () {
        return view('footers.privacy');
    })->name('privacy');

    Route::get('/terms', function () {
        return view('footers.terms');
    })->name('terms');

    Route::get('/about', function () {
        return view('footers.about');
    })->name('about');
});

// Include authentication routes
require __DIR__.'/auth.php';
