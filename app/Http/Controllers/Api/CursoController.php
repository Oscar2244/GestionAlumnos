<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\JsonResponse;

class CursoController extends Controller
{
    /**
     * Devuelve todos los cursos. Lo consume el <select> en Vue
     * para cargarlos dinámicamente (Indicador 1 de la rúbrica).
     */
    public function index(): JsonResponse
    {
        $cursos = Curso::orderBy('nombre_curso')->get();

        return response()->json([
            'data' => $cursos,
        ]);
    }
}
