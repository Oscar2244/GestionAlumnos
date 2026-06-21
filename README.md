# Gestión de Alumnos — CRUD con Laravel + Vue + SQLite

Trabajo académico: registro de alumnos vinculados a un curso, con relaciones en
3FN, validaciones en backend y frontend, filtros y edición vía modal.

## Stack

- **Backend:** Laravel 10 (PHP 8.0+) + Eloquent ORM
- **Frontend:** Vue 3 + Vite
- **Base de datos:** SQLite 

## Estructura de la base de datos (3FN)

```
cursos
├── id              (PK)
├── nombre_curso
└── turno           (mañana | tarde | noche)

alumnos
├── id              (PK)
├── nombre_alumno
├── email           (único)
├── id_curso        (FK -> cursos.id)
├── nota            (0 a 10)
└── created_at / updated_at
```

Relación: un curso tiene muchos alumnos (`hasMany`); un alumno pertenece a un
curso (`belongsTo`). Ver `app/Models/Curso.php` y `app/Models/Alumno.php`.

## Instalación local (paso a paso, para Windows con XAMPP)

### 1. Requisitos previos

| Herramienta | Cómo verificar | Cómo instalar si falta |
|---|---|---|
| PHP 8.0+ | `php -v` | Ya viene con XAMPP, en `C:\xampp\php` |
| Composer | `composer -v` | https://getcomposer.org/Composer-Setup.exe |
| Node.js 18+ | `node -v` | https://nodejs.org/ |
| npm | `npm -v` | viene con Node.js |

Si `php -v` no funciona, agregá `C:\xampp\php` al PATH de Windows
(Win → "variables de entorno" → "Editar las variables de entorno del
sistema" → "Variables de entorno" → en "Variables del sistema", `Path` →
"Editar" → "Nuevo" → `C:\xampp\php`). Cerrá y abrí una terminal nueva después.

### 2. Clonar el repositorio

```bash
git clone https://github.com/Oscar2244/GestionAlumnos.git
cd GestionAlumnos
```

### 3. Instalar dependencias de PHP

```bash
composer install --no-security-blocking
```

### 4. Configurar el entorno

```bash
copy .env.example .env
php artisan key:generate
```

### 5. Crear el archivo de base de datos SQLite

```powershell
New-Item database\database.sqlite -ItemType File
```

### 6. Ejecutar las migraciones y cargar datos de ejemplo

```bash
php artisan migrate --seed
```

Esto crea las tablas `cursos` y `alumnos`, y las llena con 6 cursos (2 por
turno) y 8 alumnos de ejemplo.

### 7. Instalar dependencias de JavaScript

```bash
npm install
```

### 8. Levantar el proyecto (necesitás DOS terminales abiertas a la vez)

**Terminal 1** — compila y sirve Vue en modo desarrollo:
```bash
npm run dev
```

**Terminal 2** — levanta el servidor de Laravel:
```bash
php artisan serve
```

### 9. Abrir en el navegador

```
http://localhost:8000
```


## Estructura del proyecto

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── AlumnoController.php   → CRUD completo + filtros
│   │   └── CursoController.php    → listado de cursos (para el select)
│   └── Requests/
│       └── AlumnoRequest.php      → validaciones del backend
├── Models/
│   ├── Alumno.php                 → relación belongsTo
│   └── Curso.php                  → relación hasMany
database/
├── migrations/                    → definición de tablas (3FN)
└── seeders/                       → datos de ejemplo
resources/
├── css/app.css                    → estilos, paleta, bordes dobles
├── js/components/
│   ├── AlumnosApp.vue             → orquesta todo, llama a la API
│   ├── AlumnoForm.vue             → formulario (alta y edición)
│   ├── FiltrosAlumnos.vue         → filtros por nombre/curso/turno/fecha
│   ├── TablaAlumnos.vue           → listado con curso y nota
│   └── ModalEdicion.vue           → ventana modal de edición
routes/
├── api.php                        → endpoints REST
└── web.php                        → sirve la vista con la app Vue
```

## Solución de problemas comunes

- **`php -v` no funciona:** falta agregar `C:\xampp\php` al PATH.
- **`composer install` bloquea por avisos de seguridad:** usá
  `composer install --no-security-blocking`.
- **La página carga pero sin estilos ni formulario:** falta correr
  `npm run dev` en una terminal aparte (debe quedar corriendo, no cerrarla).
- **Error "could not find driver" al migrar:** falta activar la extensión
  `pdo_sqlite` en el `php.ini` que esté usando tu PHP activo.

## Autor

Oscar Ocampos — Trabajo académico de programación web.
