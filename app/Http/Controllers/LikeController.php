<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle like status for a post
     */
    public function toggle($postId)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour aimer cette publication.');
        }

        $user = auth()->user();
        $post = Post::findOrFail($postId);
        
        // Check if user already liked this post
        $existingLike = Like::where('user_id', $user->id)
                          ->where('post_id', $postId)
                          ->first();
        
        if ($existingLike) {
            // User already liked the post, so remove the like
            $existingLike->delete();
            $message = 'Vous n\'aimez plus cette publication.';
        } else {
            // User hasn't liked the post, so add a like
            Like::create([
                'user_id' => $user->id,
                'post_id' => $postId
            ]);
            $message = 'Vous aimez cette publication.';
        }
        
        return redirect()->back()->with('success', $message);
    }
}