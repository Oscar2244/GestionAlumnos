<?php

namespace Database\Seeders;

use App\Models\Alumno;
use Illuminate\Database\Seeder;

class AlumnoSeeder extends Seeder
{
    public function run(): void
    {
        $alumnos = [
            ['nombre_alumno' => 'Mateo Gimenez',      'email' => 'mateo.gimenez@correo.com',      'id_curso' => 1, 'nota' => 8.50],
            ['nombre_alumno' => 'Lucía Fernández',    'email' => 'lucia.fernandez@correo.com',    'id_curso' => 1, 'nota' => 9.20],
            ['nombre_alumno' => 'Bruno Ortega',       'email' => 'bruno.ortega@correo.com',       'id_curso' => 2, 'nota' => 7.00],
            ['nombre_alumno' => 'Valentina Acosta',   'email' => 'valentina.acosta@correo.com',   'id_curso' => 3, 'nota' => 8.80],
            ['nombre_alumno' => 'Joaquín Méndez',     'email' => 'joaquin.mendez@correo.com',     'id_curso' => 3, 'nota' => null],
            ['nombre_alumno' => 'Camila Rojas',       'email' => 'camila.rojas@correo.com',       'id_curso' => 4, 'nota' => 6.50],
            ['nombre_alumno' => 'Tomás Villalba',     'email' => 'tomas.villalba@correo.com',     'id_curso' => 5, 'nota' => 9.00],
            ['nombre_alumno' => 'Sofía Cabrera',      'email' => 'sofia.cabrera@correo.com',      'id_curso' => 6, 'nota' => 7.80],
        ];

        foreach ($alumnos as $alumno) {
            Alumno::create($alumno);
        }
    }
}
