<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function enregistrer(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prénom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'mot_de_passe' => 'required|string|min:8',
        ]);

        $validatedData['mot_de_passe'] = Hash::make($validatedData['mot_de_passe']);

        $admin = Admin::create($validatedData);

        return response()->json(['success' => true, 'admin' => $admin]);
    }

    public function authentifier(Request $request)
    {
        $credentials = $request->only('email', 'mot_de_passe');
    
        $admin = Admin::where('email', $credentials['email'])->first();
    
        if (!$admin || !Hash::check($credentials['mot_de_passe'], $admin->mot_de_passe)) {
            return response()->json(['success' => false, 'message' => 'Identifiants incorrects'], 401);
        }
    
        // Auth::login($admin); // Connecte l'utilisateur
        return response()->json(['success' => true, 'admin' => $admin]);
    }
    
}
