<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Poste;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Ajouter un commentaire à un poste
     */
    public function store(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Vérifier si le poste existe
        $post = Poste::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Poste introuvable.'
            ], 404);
        }

        // Création du commentaire
        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'poste_id' => $post->id,
            'comment' => $validated['comment'],
        ]);

        return response()->json([
            'message' => 'Commentaire ajouté avec succès.',
            'comment' => $comment
        ], 201);
    }

     public function index($postId)
    {
        // Vérifie si le post existe
        $post = Poste::findOrFail($postId);

        // Récupère tous les commentaires liés
        $comments = Comment::where('poste_id', $postId)
            ->with('user')
            ->latest()
            ->get();

        return response()->json($comments);
    }


     public function update(Request $request, $commentId)
     {
        $validated = $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $comment = Comment::findOrFail($commentId);

        // Vérifie si l'utilisateur connecté est l’auteur
        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $comment->comment = $validated['comment'];
        $comment->save();

        return response()->json([
            'message' => 'Commentaire modifié avec succès',
            'data' => $comment
        ]);
    }
}
