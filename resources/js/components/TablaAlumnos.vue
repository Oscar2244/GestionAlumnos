<template>
  <div class="table-wrap">
    <div v-if="loading" class="empty-state">
      <strong>Cargando alumnos...</strong>
    </div>

    <div v-else-if="alumnos.length === 0" class="empty-state">
      <strong>No hay alumnos para mostrar</strong>
      <p>Probá registrar uno nuevo o ajustar los filtros aplicados.</p>
    </div>

    <table v-else class="alumnos-table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Email</th>
          <th>Curso</th>
          <th>Turno</th>
          <th>Nota</th>
          <th>Registrado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="alumno in alumnos" :key="alumno.id">
          <td>{{ alumno.nombre_alumno }}</td>
          <td>{{ alumno.email }}</td>
          <!-- Indicador 5: se muestra el NOMBRE del curso, no el id -->
          <td>{{ alumno.curso?.nombre_curso ?? '—' }}</td>
          <td>
            <span v-if="alumno.curso" :class="['badge-turno', badgeClase(alumno.curso.turno)]">
              {{ alumno.curso.turno }}
            </span>
            <span v-else>—</span>
          </td>
          <td>
            <span v-if="alumno.nota !== null" class="nota-pill">{{ Number(alumno.nota).toFixed(2) }}</span>
            <span v-else class="nota-pill--sin-nota">Sin nota</span>
          </td>
          <td>{{ formatearFecha(alumno.created_at) }}</td>
          <td>
            <div class="row-actions">
              <button class="btn btn--small btn--edit" @click="$emit('editar', alumno)">Editar</button>
              <button class="btn btn--small btn--delete" @click="$emit('eliminar', alumno)">Eliminar</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: 'TablaAlumnos',
  props: {
    alumnos: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
  },
  emits: ['editar', 'eliminar'],

  methods: {
    badgeClase(turno) {
      if (turno === 'mañana') return 'badge-turno--manana';
      if (turno === 'tarde') return 'badge-turno--tarde';
      return 'badge-turno--noche';
    },

    formatearFecha(fechaIso) {
      if (!fechaIso) return '—';
      const fecha = new Date(fechaIso);
      return fecha.toLocaleDateString('es-PY', { day: '2-digit', month: '2-digit', year: 'numeric' });
    },
  },
};
</script>
