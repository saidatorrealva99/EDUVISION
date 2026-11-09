<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesYPermisosSeeder extends Seeder
{
    public function run(): void
    {
        // Spatie guarda los permisos en caché: se limpia antes de crear
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permisos con formato "modulo.accion"
        $permisos = [
            'institucion.configurar',
            'usuarios.gestionar',
            'matriculas.gestionar',
            'secciones.gestionar',
            'asignaciones.gestionar',
            'notas.registrar',
            'notas.ver_todas',
            'notas.aprobar_correccion',
            'asistencia.registrar',
            'justificaciones.revisar',
            'observaciones.registrar',
            'riesgo.ver',
            'casos.gestionar',
            'comunicados.publicar',
            'citaciones.gestionar',
            'tareas.publicar',
            'encuestas.gestionar',
            'encuestas.ver_resultados',
            'siagie.exportar',
            'reportes.ver',
            'web.gestionar',
            'auditoria.ver',
            'portal.familia',
            'portal.estudiante',
        ];

        foreach ($permisos as $permiso) {
            Permission::findOrCreate($permiso, 'web');
        }

        // Qué puede hacer cada rol
        $roles = [
            // Todo, menos los portales de familia y estudiante
            'administrador' => array_values(array_diff($permisos, ['portal.familia', 'portal.estudiante'])),

            'director' => [
                'matriculas.gestionar', 'notas.ver_todas', 'notas.aprobar_correccion',
                'justificaciones.revisar', 'riesgo.ver', 'casos.gestionar',
                'comunicados.publicar', 'citaciones.gestionar', 'encuestas.gestionar',
                'encuestas.ver_resultados', 'siagie.exportar', 'reportes.ver', 'auditoria.ver',
            ],

            'coordinador' => [
                'secciones.gestionar', 'asignaciones.gestionar', 'notas.ver_todas',
                'notas.aprobar_correccion', 'justificaciones.revisar', 'observaciones.registrar',
                'riesgo.ver', 'casos.gestionar', 'comunicados.publicar',
                'citaciones.gestionar', 'encuestas.ver_resultados', 'reportes.ver',
            ],

            // El tutor NO es un rol aparte: es un docente con una sección a cargo (secciones.tutor_id)
            'docente' => [
                'notas.registrar', 'asistencia.registrar', 'observaciones.registrar',
                'tareas.publicar', 'comunicados.publicar', 'citaciones.gestionar',
                'riesgo.ver', 'casos.gestionar', 'encuestas.ver_resultados',
            ],

            'estudiante' => ['portal.estudiante'],

            'apoderado' => ['portal.familia'],
        ];

        foreach ($roles as $nombre => $lista) {
            Role::findOrCreate($nombre, 'web')->syncPermissions($lista);
        }
    }
}