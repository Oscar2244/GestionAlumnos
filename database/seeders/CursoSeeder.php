<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $cursos = [
            ['nombre_curso' => 'Programación I',           'turno' => 'mañana'],
            ['nombre_curso' => 'Base de Datos',             'turno' => 'mañana'],
            ['nombre_curso' => 'Desarrollo Web',             'turno' => 'tarde'],
            ['nombre_curso' => 'Redes y Comunicaciones',     'turno' => 'tarde'],
            ['nombre_curso' => 'Inglés Técnico',             'turno' => 'noche'],
            ['nombre_curso' => 'Matemática Discreta',        'turno' => 'noche'],
        ];

        foreach ($cursos as $curso) {
            Curso::create($curso);
        }
    }
}
