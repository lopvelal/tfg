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
        $permisoListarReservas = Permission::create(['name' => 'listar_reservas']);
        $permisoMostrarReserva = Permission::create(['name' => 'mostrar_reserva']);
        $permisoCrearReservas = Permission::create(['name' => 'crear_reservas']);
        $permisoEditarReservas = Permission::create(['name' => 'editar_reservas']);
        $permisoEliminarReservas = Permission::create(['name' => 'eliminar_reservas']);

        $permisoListarAulas = Permission::create(['name' => 'listar_aulas']);
        $permisoMostrarAula = Permission::create(['name' => 'mostrar_aula']);
        $permisoCrearAulas = Permission::create(['name' => 'crear_aulas']);
        $permisoEditarAulas = Permission::create(['name' => 'editar_aulas']);
        $permisoEliminarAulas = Permission::create(['name' => 'eliminar_aulas']);

        $permisoListarUsuarios = Permission::create(['name' => 'listar_usuarios']);
        $permisoCrearUsuarios = Permission::create(['name' => 'crear_usuarios']);
        $permisoEditarUsuarios = Permission::create(['name' => 'editar_usuarios']);
        $permisoEliminarUsuarios = Permission::create(['name' => 'eliminar_usuarios']);

        // Crear roles y asignar permisos existentes
        // Rol profesor y sus permisos
        $rolProfesor = Role::create(['name' => 'profesor']);
        $rolProfesor->givePermissionTo($permisoListarReservas);
        $rolProfesor->givePermissionTo($permisoMostrarReserva);
        $rolProfesor->givePermissionTo($permisoCrearReservas);
        $rolProfesor->givePermissionTo($permisoEditarReservas);
        $rolProfesor->givePermissionTo($permisoEliminarReservas);
        $rolProfesor->givePermissionTo($permisoListarAulas);
        $rolProfesor->givePermissionTo($permisoMostrarAula);

        // Rol alumno y sus permisos
        $rolAlumno = Role::create(['name' => 'alumno']);
        $rolAlumno->givePermissionTo($permisoListarReservas);
        $rolAlumno->givePermissionTo($permisoMostrarReserva);
        $rolAlumno->givePermissionTo($permisoListarAulas);
        $rolAlumno->givePermissionTo($permisoMostrarAula);

    }
}
