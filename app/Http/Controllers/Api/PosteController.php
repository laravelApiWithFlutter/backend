<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Poste;
use App\Models\UserProfile;
use App\Http\Controllers\Controller;


class PosteController extends Controller
{
     public function store(Request $request)
    {
        $validated = $request->validate([
            'content'       => 'nullable|string',
            'image'         => 'nullable|image|max:2048',
            'video'         => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg|max:20000'
        ]);

        $pathImage = null;
        $pathVideo = null;

        if ($request->hasFile('image')) {
            $pathImage = $request->file('image')->store('posts_images', 'public');
        }

        if ($request->hasFile('video')) {
            $pathVideo = $request->file('video')->store('posts_videos', 'public');
        }

        $post = Poste::create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'] ?? null,
            'image'   => $pathImage,
            'video'   => $pathVideo,
        ]);

        return response()->json($post);
    }

    // Modifier une publication
    public function update(Request $request, $id)
    {
        $post = Poste::findOrFail($id);

        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $post->update($request->only(['content']));

        return response()->json($post);
    }

    // Supprimer
    public function destroy(Request $request, $id)
    {
        $post = Poste::findOrFail($id);

        if ($post->user_id !== $request->userprofile()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Publication supprimée']);
    }

    // Voir toutes les publications (public)
    public function index()
    {
        return response()->json(Poste::with('userprofile')->latest()->get());
    }

    // Voir les publications d’un utilisateur
    public function userPosts($userId)
    {
        return response()->json(
            Poste::where('user_id', $userId)->latest()->get()
        );
    }
}
