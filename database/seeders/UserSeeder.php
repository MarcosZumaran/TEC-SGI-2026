<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Crea los usuarios base del sistema con contraseñas encriptadas.
     *
     * Credenciales de acceso (cambiar en producción):
     *  - Admin:     admin@tec.edu.pe     / Admin123*
     *  - Técnico:   tecnico@tec.edu.pe   / Tecnico123*
     *  - Directivo: directivo@tec.edu.pe / Directivo123*
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador TEC',
                'email' => 'admin@tec.edu.pe',
                'password' => 'Admin123*',
                'role' => 'admin',
            ],
            [
                'name' => 'Técnico de Soporte',
                'email' => 'tecnico@tec.edu.pe',
                'password' => 'Tecnico123*',
                'role' => 'tecnico',
            ],
            [
                'name' => 'Directivo TEC',
                'email' => 'directivo@tec.edu.pe',
                'password' => 'Directivo123*',
                'role' => 'directivo',
            ],
        ];

        foreach ($users as $user) {
            // Hash::make() encripta la contraseña con bcrypt de forma segura.
            // (El cast 'password' => 'hashed' del modelo evita el doble hash).
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'role' => $user['role'],
                ]
            );
        }
    }
}
