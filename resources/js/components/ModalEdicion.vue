<template>
  <div class="modal-overlay" @click.self="$emit('cerrar')">
    <div class="modal-box">
      <div class="modal-header">
        <h2>Editar alumno</h2>
        <button class="modal-close" @click="$emit('cerrar')" aria-label="Cerrar">✕</button>
      </div>
      <div class="modal-body">
        <!-- Reutilizamos el mismo formulario que el alta, pero en modo "editar" -->
        <AlumnoForm
          modo="editar"
          :cursos="cursos"
          :server-errors="serverErrors"
          :submitting="submitting"
          :alumno-inicial="alumno"
          @submit="$emit('guardar', $event)"
          @cancelar="$emit('cerrar')"
        />
      </div>
    </div>
  </div>
</template>

<script>
import AlumnoForm from './AlumnoForm.vue';

export default {
  name: 'ModalEdicion',
  components: { AlumnoForm },
  props: {
    alumno: { type: Object, required: true },
    cursos: { type: Array, default: () => [] },
    serverErrors: { type: Object, default: () => ({}) },
    submitting: { type: Boolean, default: false },
  },
  emits: ['cerrar', 'guardar'],
};
</script>
