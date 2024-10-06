<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User; // Import the User model

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Show the specified user's profile.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id): View
    {
        $user = User::findOrFail($id); // Retrieve the user or fail

        return view('profile.show', compact('user'));
    }

    /**
     * Update the user's email address.
     */
    public function updateEmail(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->email = $request->email;

        // If email changes, set email_verified_at to null
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'email-updated');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->name = $request->name;
        $user->birthday = $request->birthday; // Make sure the birthday is included
        $user->gender = $request->gender;
        $user->occupation = $request->occupation;
        $user->address = $request->address;
        $user->nationality = $request->nationality;
    
        $user->save();
    
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
        /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // Add this method in your ProfileController
    public function getAllUsers()
    {
        return User::all(); // Return all users as a JSON response
    }
}
