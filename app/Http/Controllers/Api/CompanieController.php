<?php
namespace App\Http\Controllers\Api ;

use Illuminate\Http\Request;
use App\Models\Companie;
use App\Http\Controllers\Controller;

class CompanieController extends Controller
{
     public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'cfe_number' => 'required|string'
        ]);

        if ($request->user()->type !== 'pro') {
            return response()->json(['message' => 'Accès refusé : uniquement pour les comptes PRO'], 403);
        }

        $company = Companie::create([
            'user_id'    => $request->user()->id,
            'name'       => $request->name,
            'cfe_number' => $request->cfe_number,
        ]);

        return response()->json(['message' => 'company created successfully'],201);
    }

    // Voir mes entreprises
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->companies
        );
     }

    public function destroy(Request $request, $id)
    {
    // Récupérer la company
    $company = Companie::find($id);

    if (!$company) {
        return response()->json([
            'message' => 'Entreprise introuvable.'
        ], 404);
    }

    // Vérifier que l'utilisateur connecté est bien le propriétaire
    if ($company->user_id !== $request->user()->id) {
        return response()->json([
            'message' => 'Vous n\'êtes pas autorisé à supprimer cette entreprise.'
        ], 403);
    }

    // Supprimer l’entreprise
    $company->delete();

    return response()->json([
        'message' => 'Entreprise supprimée avec succès.',
        'company_deleted' => $company
    ], 200);
   } 

   public function update(Request $request, $id)
   {
    // Validation des champs autorisés à être modifiés
    $validated = $request->validate([
        'name'       => 'sometimes|string|max:255',
        'cfe_number' => 'sometimes|string|max:255',
    ]);

    // Vérifier que la company existe
    $company = Companie::find($id);

    if (!$company) {
        return response()->json([
            'message' => 'Entreprise introuvable.'
        ], 404);
    }

    // Vérifier que l'utilisateur connecté est bien le propriétaire
    if ($company->user_id !== $request->user()->id) {
        return response()->json([
            'message' => 'Vous n\'êtes pas autorisé à modifier cette entreprise.'
        ], 403);
    }

    // Mise à jour des champs envoyés
    $company->update($validated);

    return response()->json([
        'message' => 'Entreprise modifiée avec succès.',
        'company_updated' => $company
    ], 200);
  }


}