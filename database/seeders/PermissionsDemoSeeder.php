<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class PermissionsDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //creer les permissions

        $permission1= Permission::create(['name'=>'gérer des permissions']);
        $permission2= Permission::create(['name'=>'gérer des rôles']);
        //creer les roles + leur donner des permissions
        $superAdmin= Role::create(['name'=>'super-admin'])->givePermissionTo($permission1);;
        $admin = Role::create(['name'=>'admin'])->givePermissionTo($permission2);
        $professeur= Role::create(['name'=>'professeur']);
        $etudiant= Role::create(['name'=>'etudiant']);
        \App\Models\User::factory()->create([
            'name' => 'oumayma',
            'lastname' => 'zahrouni',
            'phone'=>'90120430',
            'email' => 'superAdmin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('superAdmin123'),
            'status' =>'active'
        ])->assignRole($superAdmin);
        \App\Models\User::factory()->create([
            'name' => 'hiba',
            'lastname' => 'hamila',
            'phone'=>'99125430',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'status' =>'active'
        ])->assignRole($admin);

    }
}
