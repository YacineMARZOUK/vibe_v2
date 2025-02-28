<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {   
        $user = Auth::user();
        return view('profile.edit',compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'location' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'cover_photo' => ['nullable', 'image', 'max:5120'],
        ]);

        $user = $request->user();
        
        // Update basic info
        $user->name = $request->name;
        $user->location = $request->location;
        $user->bio = $request->bio;
        
        if ($request->email !== $user->email) {
            $user->email = $request->email;
            $user->email_verified_at = null;
        }
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::delete('public/' . $user->profile_photo_path);
            }
            
            // Store new photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }
        
        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            // Delete old cover photo if exists
            if ($user->cover_photo_path) {
                Storage::delete('public/' . $user->cover_photo_path);
            }
            
            // Store new cover photo
            $path = $request->file('cover_photo')->store('cover-photos', 'public');
            $user->cover_photo_path = $path;
        }
        
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        // Delete profile photo if exists
        if ($user->profile_photo_path) {
            Storage::delete('public/' . $user->profile_photo_path);
        }
        
        // Delete cover photo if exists
        if ($user->cover_photo_path) {
            Storage::delete('public/' . $user->cover_photo_path);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    /**
     * Display the user's public profile.
     */
    public function show($id): View
    {
        $user = \App\Models\User::findOrFail($id);
        $posts = $user->posts()->with('user')->latest()->paginate(10);
        
        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }
}