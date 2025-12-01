<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;


class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          
        
             return response()->json(UserProfile::all());
             
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    
         $request->validate([
            'name'=>'required|string',
            'email'=> 'required|email|unique:user_profiles',
            'address'=>'required|string',
            'password'=>'required|string|min(6)',
            'type' =>'required|string|in:pro,prive'
         ]);

         
          $user = UserProfile::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'address'  => $request->address,
            'password' => Hash::make($request->password),
            'type'     => $request->type,
        ]);

        return response()->json($user, 201);
        
        
    } 
 
    public function show($id)
    {
        $user = UserProfile::find($id);

        if(!$user){
            return response()->json(['message'=>'user not found'],404);
        }
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $user = UserProfile::find($id);

         if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name'     => 'sometimes|required|string',
            'email'    => 'sometimes|required|email|unique:user_profiles,email,' . $id,
            'address'  => 'sometimes|required|string',
            'password' => 'nullable|string|min:6',
            'type'     => 'sometimes|required|string|in:pro,prive',
        ]);

        if ($request->filled('password')) {
            $request->merge([
                'password' => Hash::make($request->password)
            ]);
        }


        $user->update($request->all());

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       $user = UserProfile::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
    
}
