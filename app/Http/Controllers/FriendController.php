<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    // Envoyer une demande d'ami
    public function sendFriendRequest($friendId)
    {
        $sender = auth()->user();
        $receiver = User::findOrFail($friendId);
        
        if ($sender->id === $receiver->id) {
            return back()->with('error', 'Tu ne peux pas être ami avec toi-même.');
        }
        
        // Use the Friendship model to query the database
        $existingRequest = Friendship::where(function ($query) use ($sender, $receiver) {
            $query->where('user_id', $sender->id)
                  ->where('friend_id', $receiver->id)
                  ->whereIn('status', ['pending', 'accepted']);
        })->orWhere(function ($query) use ($sender, $receiver) {
            $query->where('user_id', $receiver->id)
                  ->where('friend_id', $sender->id)
                  ->whereIn('status', ['pending', 'accepted']);
        })->first();
        
        if ($existingRequest) {
            return back()->with('error', 'Demande déjà envoyée ou déjà amis.');
        }
        
        Friendship::create([
            'user_id' => $sender->id,
            'friend_id' => $receiver->id,
            'status' => 'pending',
        ]);
        
        return back()->with('success', 'Demande d\'ami envoyée.');
    }
    

    // Répondre à une demande d'ami (Accepter ou Rejeter)
    public function respondToFriendRequest($requestId, $action)
    {
        $friendRequest = Friendship::findOrFail($requestId);
        
        // Check if the current user is the receiver of the request
        if ($friendRequest->friend_id !== auth()->id()) {
            return back()->with('error', 'Tu ne peux pas répondre à cette demande.');
        }
        
        if ($action === 'accept') {
            $friendRequest->status = 'accepted';
            $friendRequest->save();
            return back()->with('success', 'Demande d\'ami acceptée.');
        } elseif ($action === 'reject') {
            $friendRequest->status = 'declined';  // Make sure this is a string
            $friendRequest->save();
            return back()->with('error', 'Demande d\'ami rejetée.');
        }
        
        return back()->with('error', 'Action non valide.');
    }

    // Lister les amis de l'utilisateur
    // Lister les amis de l'utilisateur
    public function listFriends()
    {
        $user = auth()->user();
        
        // Récupérer toutes les amitiés acceptées où l'utilisateur est impliqué
        $friends = Friendship::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('friend_id', $user->id);
        })
        ->where('status', 'accepted')
        ->with(['sender', 'receiver']) // Charger les relations
        ->get();
        
        return view('friends.index', compact('friends'));
    }
    
    // Afficher tous les utilisateurs pour envoyer des demandes d'ami
public function showUsers()
{
    // Get current user
    $currentUser = auth()->user();
    
    // Get all users except the current user
    $users = User::where('id', '!=', $currentUser->id)->get();
    
    // Get existing friendship relationships
    $friendships = Friendship::where(function ($query) use ($currentUser) {
        $query->where('user_id', $currentUser->id)
              ->orWhere('friend_id', $currentUser->id);
    })->get();
    
    // Format friendship status for easy access in the view
    $friendshipStatus = [];
    foreach ($friendships as $friendship) {
        if ($friendship->user_id === $currentUser->id) {
            $friendshipStatus[$friendship->friend_id] = [
                'status' => $friendship->status,
                'id' => $friendship->id,
                'sender' => true
            ];
        } else {
            $friendshipStatus[$friendship->user_id] = [
                'status' => $friendship->status,
                'id' => $friendship->id,
                'sender' => false
            ];
        }
    }
    
    return view('users.index', compact('users', 'friendshipStatus'));
}
}
