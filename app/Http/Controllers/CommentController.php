<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, $postId)
    {
        // Validate the request
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->back()->withErrors(['auth' => 'Vous devez être connecté pour commenter.']);
        }
        
        // Find the post
        $post = Post::findOrFail($postId);
        
        // Create the comment
        $comment = new Comment();
        $comment->content = $request->input('content');
        $comment->user_id = auth()->id();
        $comment->post_id = $post->id;
        
        // Save the comment
        $comment->save();
        
        return redirect()->back()->with('success', 'Commentaire ajouté avec succès!');
    }
    
    /**
     * Remove the specified comment from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Authorization check
        if ($comment->user_id !== auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer ce commentaire.');
        }
        
        $comment->delete();
        return back()->with('success', 'Commentaire supprimé.');
    }
}