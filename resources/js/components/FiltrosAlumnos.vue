<template>
  <div>
    <div class="filters">
      <div class="field">
        <label>Buscar por nombre</label>
        <input v-model="filtros.nombre" type="text" placeholder="Escribí un nombre..." @input="emitirFiltros">
      </div>

      <div class="field">
        <label>Curso</label>
        <select v-model="filtros.id_curso" @change="emitirFiltros">
          <option value="">Todos los cursos</option>
          <option v-for="curso in cursos" :key="curso.id" :value="curso.id">
            {{ curso.nombre_curso }}
          </option>
        </select>
      </div>

      <div class="field">
        <label>Desde</label>
        <input v-model="filtros.fecha_desde" type="date" @change="emitirFiltros">
      </div>

      <div class="field">
        <label>Hasta</label>
        <input v-model="filtros.fecha_hasta" type="date" @change="emitirFiltros">
      </div>
    </div>

    <div class="field" style="margin-top: 1.1rem;">
      <label>Turno</label>
      <div class="turno-buttons">
        <button
          type="button"
          class="turno-btn turno-btn--manana"
          :class="{ 'is-active': filtros.turno === 'mañana' }"
          @click="toggleTurno('mañana')"
        >
           Mañana
        </button>
        <button
          type="button"
          class="turno-btn turno-btn--tarde"
          :class="{ 'is-active': filtros.turno === 'tarde' }"
          @click="toggleTurno('tarde')"
        >
           Tarde
        </button>
        <button
          type="button"
          class="turno-btn turno-btn--noche"
          :class="{ 'is-active': filtros.turno === 'noche' }"
          @click="toggleTurno('noche')"
        >
           Noche
        </button>
        <button
          v-if="hayFiltrosActivos"
          type="button"
          class="btn btn--ghost btn--small"
          @click="limpiarFiltros"
        >
          Limpiar filtros
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FiltrosAlumnos',
  props: {
    cursos: { type: Array, default: () => [] },
  },
  emits: ['filtrar'],

  data() {
    return {
      filtros: {
        nombre: '',
        id_curso: '',
        turno: '',
        fecha_desde: '',
        fecha_hasta: '',
      },
    };
  },

  computed: {
    hayFiltrosActivos() {
      return Object.values(this.filtros).some(v => v !== '');
    },
  },

  methods: {
    toggleTurno(turno) {
      this.filtros.turno = this.filtros.turno === turno ? '' : turno;
      this.emitirFiltros();
    },

    limpiarFiltros() {
      this.filtros = { nombre: '', id_curso: '', turno: '', fecha_desde: '', fecha_hasta: '' };
      this.emitirFiltros();
    },

    emitirFiltros() {
      // Quita claves vacías antes de mandarlas como query params
      const limpio = {};
      for (const k in this.filtros) {
        if (this.filtros[k] !== '') limpio[k] = this.filtros[k];
      }
      this.$emit('filtrar', limpio);
    },
  },
};
</script>
