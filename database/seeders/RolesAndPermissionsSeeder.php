<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos de Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. CREAR PERMISOS
        $crear = Permission::firstOrCreate(['name' => 'crear tickets']);
        $ver = Permission::firstOrCreate(['name' => 'ver tickets']);
        $editar = Permission::firstOrCreate(['name' => 'editar tickets']);
        $cerrar = Permission::firstOrCreate(['name' => 'cerrar tickets']);
        $gestionarUsuarios = Permission::firstOrCreate(['name' => 'gestionar usuarios']);

        // 2. CREAR ROLES
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $usuario = Role::firstOrCreate(['name' => 'usuario']);

        // 3. ASIGNAR PERMISOS A ROLES
        $admin->syncPermissions([$crear, $ver, $editar, $cerrar, $gestionarUsuarios]);
        $supervisor->syncPermissions([$crear, $ver, $editar, $cerrar]);
        $usuario->syncPermissions([$crear, $ver]);

        // 4. CREAR USUARIOS DE PRUEBA Y ASIGNAR ROLES
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Administrador', 'password' => Hash::make('12345678')]
        );
        $adminUser->syncRoles(['admin']);

        $supervisorUser = User::firstOrCreate(
            ['email' => 'supervisor@test.com'],
            ['name' => 'Supervisor', 'password' => Hash::make('12345678')]
        );
        $supervisorUser->syncRoles(['supervisor']);

        $normalUser = User::firstOrCreate(
            ['email' => 'usuario@test.com'],
            ['name' => 'Usuario', 'password' => Hash::make('12345678')]
        );
        $normalUser->syncRoles(['usuario']);
    }
}
