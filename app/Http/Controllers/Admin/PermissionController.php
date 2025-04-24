<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class PermissionController extends Controller
{
    

    public function index(Request $request)
    {
        // Créer la requête de base pour les permissions
        $query = Permission::with('roles.users');

        // Filtrer par rôle si spécifié
        if ($request->has('role') && !empty($request->role)) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $permissions = $query->get();

        // Calculer le nombre d'utilisateurs pour chaque permission
        foreach ($permissions as $permission) {
            // Récupérer tous les utilisateurs uniques qui ont cette permission
            // (soit directement, soit via un rôle)
            $userCount = 0;
            $userIds = [];

            // Compter les utilisateurs via les rôles
            foreach ($permission->roles as $role) {
                foreach ($role->users as $user) {
                    // Éviter de compter le même utilisateur plusieurs fois
                    if (!in_array($user->id, $userIds)) {
                        $userIds[] = $user->id;
                        $userCount++;
                    }
                }
            }

            // Ajouter le nombre d'utilisateurs comme attribut
            $permission->users_count = $userCount;
        }

        $roles = Role::all();
        $selectedRole = $request->role;

        return view('admin.permission.index', compact('permissions', 'roles', 'selectedRole'));
    }


    public function create()
    {
        return view('admin.permission.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $permission = Permission::where('name', $request->name)->first();
        if ($permission) {
            return response()->json(['errors' => [
                'name' => 'Cette permission existe déjà. Veuillez choisir un autre nom.'
            ]]); // Retourner un JSON
        }
        $permission=Permission::create(['name' => $request->name]);
        return response()->json([
            'success' => true,
            'message' => 'permission ajouté avec succès',]); // Retourner un JSON
    }
    public function edit(Permission $permission)
    {
        return view('admin.permission.edit', compact('permission'));
    }
    public function update(Request $request,Permission $permission)
    {
        $validate=$request->validate([
            'name' => 'required'
        ]);
        $existingPermission = Permission::where('name', $validate['name'])->first();
        if ($existingPermission && $existingPermission->id !== $permission->id) {
            return response()->json(['errors' => [
                'name' => 'Cette permission existe déjà. Veuillez choisir un autre nom.'
            ]]); // Retourner un JSON
        }
        $permission->update($validate);
        return response()->json(['success' => true,
            'message' => 'Permission modifié avec succès']);
    }
    public function destroy(Permission $permission)
    {
        $permission->delete();
        //return back()->with('success', 'Permission supprimé avec succès');
        return response()->json(['success' => true,
            'message' =>  'Permission supprimée avec succès']);
    }
}
