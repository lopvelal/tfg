<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reestablecer la caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permisoGestionarReservas = Permission::create(['name' => 'gestionar_reservas']);

        // Crear roles y asignar permisos existentes
        $rolProfesor = Role::create(['name' => 'profesor']);
        $rolProfesor->givePermissionTo($permisoGestionarReservas);
    }
}
