<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //affichier la page de roles
    public function index()
    {

        $roles = Role::query();
        if(auth()->user()->hasRole('admin')){
            $roles=$roles->whereNotIn('name',['super-admin','admin']);
        }
        elseif(auth()->user()->hasRole('super-admin')){
            $roles=$roles->whereNotIn('name',['super-admin']);
        }
        $roles = $roles->get();
        return view('admin.role.index', compact('roles'));
    }
    public function create()
    {
        return view('admin.role.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $role = Role::where('name', $request->name)->first();
        if ($role) {
            return response()->json(['errors' => [
                'name' => 'Ce rôle existe déjà. Veuillez choisir un autre nom.'
            ]]);
        }

        $role=Role::create(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Role ajouté avec succès',
        ]);
    }

 
    public function edit(Role $role)
    {
        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        // Récupérer le rôle de l'utilisateur connecté
        $userRole = $user->roles->first();

        // Si l'utilisateur est super admin, afficher toutes les permissions
        if ($userRole->name === 'super-admin') {
            $permissions = Permission::all();
        }
        // Si l'utilisateur est admin, afficher toutes les permissions sauf celles
        // qui sont exclusives au super admin
        elseif ($userRole->name === 'admin') {
            // Récupérer le rôle super admin
            $superAdminRole = Role::where('name', 'super-admin')->first();
            // Récupérer le rôle admin
            $adminRole = Role::where('name', 'admin')->first();

            // Récupérer les permissions du super admin
            $superAdminPermissions = $superAdminRole->permissions->pluck('id')->toArray();

            // Récupérer les permissions de l'admin
            $adminPermissions = $adminRole->permissions->pluck('id')->toArray();

            // Permissions exclusives au super admin (celles que le super admin a mais que l'admin n'a pas)
            $exclusiveSuperAdminPermissions = array_diff($superAdminPermissions, $adminPermissions);

            // Récupérer toutes les permissions sauf celles exclusives au super admin
            $permissions = Permission::whereNotIn('id', $exclusiveSuperAdminPermissions)->get();
        } else {
            // Pour les autres rôles, comportement par défaut
            $permissions = Permission::all();
        }

        return view('admin.role.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        // Valider le nom du rôle
        $validate = $request->validate([
            'name' => 'required'
        ]);

        // Vérifier si le nom du rôle existe déjà
        $existingRole = Role::where('name', $validate['name'])->first();
        if ($existingRole && $existingRole->id !== $role->id) {
            return response()->json([
                'errors' => [
                    'name' => 'Ce rôle existe déjà. Veuillez choisir un autre nom.'
                ]
            ], 422);
        }


        $role->update($validate);

        $permissionErrors = [];
        if ($request->filled('assign_permissions')) {
            $permissions = Permission::whereIn('id', $request->assign_permissions)->get();
            foreach ($permissions as $permission) {
                if(!$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                }else {
                    $permissionErrors[] = "La permission '{$permission->name}' existe déjà.";
                }
            }

        }

        if ($request->filled('remove_permissions')) {
            $permissions = Permission::whereIn('id', $request->remove_permissions)->get();
            foreach ($permissions as $permission) {
                    $role->revokePermissionTo($permission);

            }
        }

        $response = [
            'success' => true,
            'message'=> 'Rôle  modifié avec succès'];

        if (!empty($permissionErrors)) {
            $response['danger'] = $permissionErrors; // Ajouter les erreurs si elles existent
        }

        return response()->json($response);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['success' => 'Le rôle a été supprimé avec succès']);
    }





}
