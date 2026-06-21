<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_curso',
        'turno',
    ];

    /**
     * Un curso puede tener muchos alumnos.
     * Indicador 3 de la rúbrica: relación hasMany.
     */
    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'id_curso');
    }
}
