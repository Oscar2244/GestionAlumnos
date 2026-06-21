<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlumnoRequest;
use App\Models\Alumno;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    /**
     * Lista alumnos con su curso relacionado (Indicador 5: se muestra
     * el nombre del curso, no solo el id). Soporta filtros por nombre,
     * curso, turno y rango de fechas de creación.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Alumno::with('curso');

        // Filtro por nombre (búsqueda parcial)
        if ($request->filled('nombre')) {
            $query->where('nombre_alumno', 'like', '%' . $request->input('nombre') . '%');
        }

        // Filtro por curso específico
        if ($request->filled('id_curso')) {
            $query->where('id_curso', $request->input('id_curso'));
        }

        // Filtro por turno (mañana/tarde/noche) -> se filtra a través del curso relacionado
        if ($request->filled('turno')) {
            $query->whereHas('curso', function ($q) use ($request) {
                $q->where('turno', $request->input('turno'));
            });
        }

        // Filtro por rango de fechas de creación
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->input('fecha_desde'));
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->input('fecha_hasta'));
        }

        $alumnos = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $alumnos,
        ]);
    }

    /**
     * Crea un alumno nuevo. Las validaciones del Indicador 6 corren
     * automáticamente porque el parámetro es AlumnoRequest.
     */
    public function store(AlumnoRequest $request): JsonResponse
    {
        $alumno = Alumno::create($request->validated());
        $alumno->load('curso');

        return response()->json([
            'message' => 'Alumno registrado correctamente.',
            'data' => $alumno,
        ], 201);
    }

    /**
     * Devuelve un alumno puntual (lo usa el modal de edición para
     * precargar el formulario, incluyendo el curso asociado).
     */
    public function show(Alumno $alumno): JsonResponse
    {
        $alumno->load('curso');

        return response()->json([
            'data' => $alumno,
        ]);
    }

    /**
     * Actualiza un alumno existente.
     */
    public function update(AlumnoRequest $request, Alumno $alumno): JsonResponse
    {
        $alumno->update($request->validated());
        $alumno->load('curso');

        return response()->json([
            'message' => 'Alumno actualizado correctamente.',
            'data' => $alumno,
        ]);
    }

    /**
     * Elimina un alumno.
     */
    public function destroy(Alumno $alumno): JsonResponse
    {
        $alumno->delete();

        return response()->json([
            'message' => 'Alumno eliminado correctamente.',
        ]);
    }
}
