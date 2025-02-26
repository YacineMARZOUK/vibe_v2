<?php

namespace App\Http\Controllers;


use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{   
    public function index()
    {
        $posts = Post::with('user')->get(); // Récupère les posts avec les utilisateurs associés
        return view('posts.index', compact('posts')); // Passe les posts à la vue
    }
    // Créer une nouvelle publication
    public function create(Request $request)
    {
        // Debug information at the start
        \Log::info('PostController:create method called');
        \Log::info('Request data:', $request->all());
        
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                \Log::error('User not authenticated');
                return redirect()->back()->withErrors(['auth' => 'Vous devez être connecté pour publier.']);
            }
            
            // Log user ID
            \Log::info('User ID: ' . auth()->id());
            
            // Validate the request with less stringent validation for testing
            $validated = $request->validate([
                'content' => 'required',
                'image' => 'nullable|file',
            ]);
            
            // Create new post with explicit values
            $post = new Post();
            $post->content = $request->input('content');
            $post->user_id = auth()->id();
            
            // Handle image if present
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
                $post->image = $imagePath;
            }
            
            // Save with error checking
            if (!$post->save()) {
                \Log::error('Failed to save post');
                return redirect()->back()->withErrors(['db' => 'Erreur de base de données']);
            }
            
            // Get the post ID
            \Log::info('Post saved with ID: ' . $post->id);
            
            // Use a more visible success message
            $message = 'Publication ajoutée avec succès! ID: ' . $post->id;
            \Log::info($message);
            
            // Return with flash data
            return redirect()->back()->with('success', $message);
        } 
        catch (\Exception $e) {
            // Log the detailed error
            \Log::error('Exception: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return with detailed error
            return redirect()->back()->withErrors(['exception' => 'Erreur: ' . $e->getMessage()]);
        }
    }
    public function testDb()
{
    try {
        // Try to get one post
        $post = Post::first();
        
        // Get table schema
        $columns = \Schema::getColumnListing('posts');
        
        // Return debug info
        return response()->json([
            'connection' => 'success',
            'post' => $post,
            'columns' => $columns,
            'post_count' => Post::count()
        ]);
    } 
    catch (\Exception $e) {
        return response()->json([
            'connection' => 'failed',
            'error' => $e->getMessage()
        ], 500);
    }
}
    


    // Modifier une publication
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            return back()->with('error', 'Tu ne peux pas modifier cette publication.');
        }

        return view('posts.edit', compact('post'));
    }

    // Mettre à jour la publication
    public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);

    // Authorization check
    if ($post->user_id !== auth()->id()) {
        return back()->with('error', 'Tu ne peux pas modifier cette publication.');
    }
    
    // Validate input
    $request->validate([
        'content' => 'required',
    ]);

    // Update post
    $post->content = $request->content;
    $post->save();

    return redirect()->route('posts.index')->with('success', 'Publication mise à jour avec succès.');
}

    // Supprimer une publication
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            return back()->with('error', 'Tu ne peux pas supprimer cette publication.');
        }

        $post->delete();
        return back()->with('success', 'Publication supprimée.');
    }
}