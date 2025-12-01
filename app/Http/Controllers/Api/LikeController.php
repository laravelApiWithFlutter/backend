<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Poste;
use App\Models\Like;

class LikeController extends Controller
{
    /**
     * Like a post
     */
    public function like(Request $request, $poste_id)
    {
        $user = $request->user();

        // Vérifier si la publication existe
        $poste = Poste::find($poste_id);
        if (!$poste) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Vérifier si l’utilisateur a déjà liké
        $existingLike = Like::where('poste_id', $poste_id)
                            ->where('user_id', $user->id)
                            ->first();

        if ($existingLike) {
            return response()->json(['message' => 'Already liked'], 409);
        }

        // Créer un like
        Like::create([
            'poste_id' => $poste_id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Post liked successfully',
            'likes_count' => $poste->likes()->count()
        ], 201);
    }

    /**
     * Unlike a post
     */
    public function unlike(Request $request, $poste_id)
    {
        $user = $request->user();

        $like = Like::where('poste_id', $poste_id)
                    ->where('user_id', $user->id)
                    ->first();

        if (!$like) {
            return response()->json(['message' => 'Not liked yet'], 404);
        }

        $like->delete();

        $poste = Poste::find($poste_id);

        return response()->json([
            'message' => 'Like removed',
            'likes_count' => $poste ? $poste->likes()->count() : 0
        ]);
    }

    /**
     * Get like count of a post
     */
    public function count($poste_id)
    {
        $poste = Poste::find($poste_id);

        if (!$poste) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json([
            'likes_count' => $poste->likes()->count()
        ]);
    }
}

