<template>
  <div>
    <!-- Mensajes de éxito / error -->
    <div class="toast-wrap">
      <div v-for="toast in toasts" :key="toast.id" :class="['toast', toast.type === 'success' ? 'toast--success' : 'toast--error']">
        <span>{{ toast.type === 'success' ? '✓' : '⚠' }}</span>
        <span>{{ toast.message }}</span>
      </div>
    </div>

    <header class="app-header">
      <p class="app-header__eyebrow">Sistema académico</p>
      <h1>Gestión de Alumnos</h1>
      <p>ocampOS.</p>
    </header>

    <!-- Formulario de registro (alta) -->
    <section class="card">
      <h2 class="card__title">Registrar nuevo alumno</h2>
      <AlumnoForm
        :cursos="cursos"
        :server-errors="serverErrors"
        :submitting="submitting"
        modo="crear"
        @submit="crearAlumno"
      />
    </section>

    <!-- Filtros -->
    <section class="card">
      <h2 class="card__title">Filtrar alumnos</h2>
      <FiltrosAlumnos
        :cursos="cursos"
        @filtrar="aplicarFiltros"
      />
    </section>

    <!-- Tabla -->
    <section class="card">
      <h2 class="card__title">Listado de alumnos ({{ alumnos.length }})</h2>
      <TablaAlumnos
        :alumnos="alumnos"
        :loading="loading"
        @editar="abrirModalEdicion"
        @eliminar="confirmarEliminar"
      />
    </section>

    <!-- Modal de edición -->
    <ModalEdicion
      v-if="alumnoEnEdicion"
      :alumno="alumnoEnEdicion"
      :cursos="cursos"
      :server-errors="serverErrors"
      :submitting="submitting"
      @cerrar="cerrarModal"
      @guardar="actualizarAlumno"
    />
  </div>
</template>

<script>
import AlumnoForm from './AlumnoForm.vue';
import FiltrosAlumnos from './FiltrosAlumnos.vue';
import TablaAlumnos from './TablaAlumnos.vue';
import ModalEdicion from './ModalEdicion.vue';

export default {
  name: 'AlumnosApp',
  components: { AlumnoForm, FiltrosAlumnos, TablaAlumnos, ModalEdicion },

  data() {
    return {
      cursos: [],
      alumnos: [],
      loading: false,
      submitting: false,
      serverErrors: {},
      alumnoEnEdicion: null,
      filtrosActuales: {},
      toasts: [],
      toastSeq: 0,
    };
  },

  async created() {
    await this.cargarCursos();
    await this.cargarAlumnos();
  },

  methods: {
    // ---------- Carga de datos ----------
    async cargarCursos() {
      try {
        const res = await fetch('/api/cursos');
        const json = await res.json();
        this.cursos = json.data;
      } catch (e) {
        this.mostrarToast('error', 'No se pudieron cargar los cursos. Verificá la conexión con el servidor.');
      }
    },

    async cargarAlumnos(filtros = {}) {
      this.loading = true;
      try {
        const params = new URLSearchParams(filtros).toString();
        const res = await fetch(`/api/alumnos${params ? '?' + params : ''}`);
        const json = await res.json();
        this.alumnos = json.data;
      } catch (e) {
        this.mostrarToast('error', 'No se pudo cargar el listado de alumnos.');
      } finally {
        this.loading = false;
      }
    },

    aplicarFiltros(filtros) {
      this.filtrosActuales = filtros;
      this.cargarAlumnos(filtros);
    },

    // ---------- Alta ----------
    async crearAlumno(formData) {
      this.submitting = true;
      this.serverErrors = {};
      try {
        const res = await fetch('/api/alumnos', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify(formData),
        });
        const json = await res.json();

        if (res.status === 422) {
          this.serverErrors = json.errors || {};
          this.mostrarToast('error', 'Revisá los campos marcados en rojo.');
          return;
        }
        if (!res.ok) throw new Error(json.message || 'Error al registrar el alumno.');

        this.mostrarToast('success', json.message || 'Alumno registrado correctamente.');
        await this.cargarAlumnos(this.filtrosActuales);
      } catch (e) {
        this.mostrarToast('error', e.message || 'Ocurrió un error inesperado.');
      } finally {
        this.submitting = false;
      }
    },

    // ---------- Edición ----------
    abrirModalEdicion(alumno) {
      this.serverErrors = {};
      this.alumnoEnEdicion = alumno;
    },

    cerrarModal() {
      this.alumnoEnEdicion = null;
      this.serverErrors = {};
    },

    async actualizarAlumno(formData) {
      this.submitting = true;
      this.serverErrors = {};
      try {
        const res = await fetch(`/api/alumnos/${this.alumnoEnEdicion.id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify(formData),
        });
        const json = await res.json();

        if (res.status === 422) {
          this.serverErrors = json.errors || {};
          this.mostrarToast('error', 'Revisá los campos marcados en rojo.');
          return;
        }
        if (!res.ok) throw new Error(json.message || 'Error al actualizar el alumno.');

        this.mostrarToast('success', json.message || 'Alumno actualizado correctamente.');
        this.cerrarModal();
        await this.cargarAlumnos(this.filtrosActuales);
      } catch (e) {
        this.mostrarToast('error', e.message || 'Ocurrió un error inesperado.');
      } finally {
        this.submitting = false;
      }
    },

    // ---------- Baja ----------
    async confirmarEliminar(alumno) {
      if (!window.confirm(`¿Eliminar a ${alumno.nombre_alumno}? Esta acción no se puede deshacer.`)) {
        return;
      }
      try {
        const res = await fetch(`/api/alumnos/${alumno.id}`, { method: 'DELETE' });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Error al eliminar el alumno.');

        this.mostrarToast('success', json.message || 'Alumno eliminado correctamente.');
        await this.cargarAlumnos(this.filtrosActuales);
      } catch (e) {
        this.mostrarToast('error', e.message || 'Ocurrió un error inesperado.');
      }
    },

    // ---------- Toasts ----------
    mostrarToast(type, message) {
      const id = ++this.toastSeq;
      this.toasts.push({ id, type, message });
      setTimeout(() => {
        this.toasts = this.toasts.filter(t => t.id !== id);
      }, 4000);
    },
  },
};
</script>
