<template>
  <form class="form-grid" @submit.prevent="onSubmit">
    <!-- Nombre -->
    <div class="field field--full">
      <label>Nombre del alumno <span class="required-mark">*</span></label>
      <input
        v-model.trim="local.nombre_alumno"
        type="text"
        placeholder="Ej: Ana López"
        :class="{ 'is-invalid': errores.nombre_alumno }"
      >
      <span v-if="errores.nombre_alumno" class="field-error">{{ errores.nombre_alumno }}</span>
    </div>

    <!-- Email -->
    <div class="field field--full">
      <label>Email <span class="required-mark">*</span></label>
      <input
        v-model.trim="local.email"
        type="email"
        placeholder="ana.lopez@correo.com"
        :class="{ 'is-invalid': errores.email }"
      >
      <span v-if="errores.email" class="field-error">{{ errores.email }}</span>
    </div>

    <!-- Select de curso: Indicador 1 de la rúbrica (v-model + carga dinámica) -->
    <div class="field">
      <label>Curso <span class="required-mark">*</span></label>
      <select
        v-model="local.id_curso"
        class="select-curso"
        :class="[claseTurnoSeleccionado, { 'is-invalid': errores.id_curso }]"
      >
        <option value="" disabled>Seleccionar curso...</option>
        <option
          v-for="curso in cursos"
          :key="curso.id"
          :value="curso.id"
          :data-turno="curso.turno"
        >
          {{ curso.nombre_curso }} — {{ curso.turno }}
        </option>
      </select>
      <span v-if="errores.id_curso" class="field-error">{{ errores.id_curso }}</span>
    </div>

    <!-- Nota (campo extra) -->
    <div class="field">
      <label>Nota (0 a 10) <span class="required-mark">*</span></label>
      <input
        v-model="local.nota"
        type="number"
        step="0.01"
        min="0"
        max="10"
        placeholder="Opcional"
        :class="{ 'is-invalid': errores.nota }"
      >
      <span v-if="errores.nota" class="field-error">{{ errores.nota }}</span>
    </div>

    <div class="form-actions">
      <button v-if="modo === 'editar'" type="button" class="btn btn--ghost" @click="$emit('cancelar')">
        Cancelar
      </button>
      <button type="submit" class="btn btn--primary" :disabled="submitting">
        {{ submitting ? 'Guardando...' : (modo === 'editar' ? 'Guardar cambios' : 'Registrar alumno') }}
      </button>
    </div>
  </form>
</template>

<script>
export default {
  name: 'AlumnoForm',
  props: {
    cursos: { type: Array, default: () => [] },
    serverErrors: { type: Object, default: () => ({}) },
    submitting: { type: Boolean, default: false },
    modo: { type: String, default: 'crear' }, // 'crear' | 'editar'
    alumnoInicial: { type: Object, default: null },
  },
  emits: ['submit', 'cancelar'],

  data() {
    return {
      local: {
        nombre_alumno: '',
        email: '',
        id_curso: '',
        nota: '',
      },
      erroresLocales: {},
    };
  },

  computed: {
    // Combina validación de frontend con los errores que devuelve Laravel (backend)
    errores() {
      return { ...this.erroresLocales, ...this.mapearErroresServidor() };
    },

    claseTurnoSeleccionado() {
      const curso = this.cursos.find(c => c.id === this.local.id_curso);
      if (!curso) return '';
      return `turno-${curso.turno === 'mañana' ? 'manana' : curso.turno}`;
    },
  },

  watch: {
    alumnoInicial: {
      immediate: true,
      handler(val) {
        if (val) {
          this.local = {
            nombre_alumno: val.nombre_alumno || '',
            email: val.email || '',
            id_curso: val.id_curso || '',
            nota: val.nota ?? '',
          };
        }
      },
    },
  },

  methods: {
    mapearErroresServidor() {
      // Laravel devuelve { campo: ["mensaje1", "mensaje2"] }; tomamos el primero.
      const mapeado = {};
      for (const campo in this.serverErrors) {
        mapeado[campo] = Array.isArray(this.serverErrors[campo])
          ? this.serverErrors[campo][0]
          : this.serverErrors[campo];
      }
      return mapeado;
    },

    // Validación en frontend (Indicador 6): no sustituye al backend,
    // pero evita viajes innecesarios al servidor con datos incompletos.
    validarLocal() {
      const errs = {};
      if (!this.local.nombre_alumno || this.local.nombre_alumno.length < 3) {
        errs.nombre_alumno = 'El nombre es obligatorio (mínimo 3 caracteres).';
      }
      if (!this.local.email) {
        errs.email = 'El email es obligatorio.';
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.local.email)) {
        errs.email = 'El email no tiene un formato válido.';
      }
      if (!this.local.id_curso) {
        errs.id_curso = 'Debe seleccionar un curso.';
      }
      if (this.local.nota === '') {
        errs.nota = 'La nota es obligatoria.';
      } else if (this.local.nota < 0 || this.local.nota > 10) {
        errs.nota = 'La nota debe estar entre 0 y 10.';
      }      

      this.erroresLocales = errs;
      return Object.keys(errs).length === 0;
    },

    onSubmit() {
      if (!this.validarLocal()) return;

      this.$emit('submit', {
        nombre_alumno: this.local.nombre_alumno,
        email: this.local.email,
        id_curso: this.local.id_curso,
        nota: this.local.nota === '' ? null : this.local.nota,
      });

      if (this.modo === 'crear') {
        // Limpiar formulario tras un alta exitosa (se resetea al recibir confirmación visual vía toast)
        this.local = { nombre_alumno: '', email: '', id_curso: '', nota: '' };
      }
    },
  },
};
</script>
