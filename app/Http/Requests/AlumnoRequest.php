<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlumnoRequest extends FormRequest
{
    /**
     * Indicador 6 de la rúbrica: validaciones activas en backend (Laravel).
     * Estas reglas corren SIEMPRE en el servidor, sin importar lo que
     * haya validado (o no) el frontend en Vue.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Si es una edición (PUT/PATCH), el id del alumno viene en la ruta
        // y lo usamos para que el email no choque contra sí mismo.
        $alumnoId = $this->route('alumno')?->id;

        return [
            'nombre_alumno' => ['required', 'string', 'min:3', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('alumnos', 'email')->ignore($alumnoId),
            ],
            'id_curso' => ['required', 'integer', 'exists:cursos,id'],
            'nota' => ['required', 'numeric', 'min:0', 'max:10'],
        ];
    }

    /**
     * Mensajes de error claros y en español, como pide el indicador 6.
     */
    public function messages(): array
    {
        return [
            'nombre_alumno.required' => 'El nombre del alumno es obligatorio.',
            'nombre_alumno.min' => 'El nombre debe tener al menos 3 caracteres.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email no tiene un formato válido.',
            'email.unique' => 'Ya existe un alumno registrado con este email.',
            'id_curso.required' => 'Debe seleccionar un curso.',
            'id_curso.exists' => 'El curso seleccionado no es válido.',
            'nota.numeric' => 'La nota debe ser un número.',
            'nota.min' => 'La nota no puede ser menor a 0.',
            'nota.max' => 'La nota no puede ser mayor a 10.',
        ];
    }
}
