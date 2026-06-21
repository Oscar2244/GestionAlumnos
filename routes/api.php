<?php

use App\Http\Controllers\Api\AlumnoController;
use App\Http\Controllers\Api\CursoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Estas rutas las consume el componente Vue vía fetch/axios.
| apiResource genera automáticamente: index, store, show, update, destroy.
*/

Route::get('/cursos', [CursoController::class, 'index']);
Route::apiResource('/alumnos', AlumnoController::class);
