<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_alumno',
        'email',
        'id_curso',
        'nota',
    ];

    protected $casts = [
        'nota' => 'decimal:2',
    ];

    /**
     * Un alumno pertenece a un curso.
     * Indicador 3 de la rúbrica: relación belongsTo.
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }
}
