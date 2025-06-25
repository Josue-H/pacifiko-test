<template>
  <div class="container py-4">
    <h2 class="mb-4 text-center">
      <i class="fas fa-clock-rotate-left me-2"></i>Historial de Compras
    </h2>

    <!-- Filtros -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Producto</label>
            <select v-model="filtros.producto_id" class="form-select">
              <option value="">-- Todos --</option>
              <option v-for="producto in productos" :key="producto.id" :value="producto.id">
                {{ producto.nombre }}
              </option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Fecha desde</label>
            <input type="date" v-model="filtros.fecha_inicio" class="form-control" />
          </div>

          <div class="col-md-3">
            <label class="form-label">Fecha hasta</label>
            <input type="date" v-model="filtros.fecha_fin" class="form-control" />
          </div>

          <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100" @click="buscarCompras">
              <i class="fas fa-search me-1"></i> Filtrar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Resultados -->
    <div v-if="cargando" class="text-center my-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>
    </div>

    <div v-else-if="compras.length === 0" class="alert alert-info">
      No hay compras registradas para los filtros aplicados.
    </div>

    <div v-else>
      <div v-for="compra in compras" :key="compra.id" class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-2">
            Compra #{{ compra.id }} - {{ formatearFecha(compra.created_at) }}
          </h5>
          <p class="mb-2"><strong>Comprador:</strong> {{ compra.comprador }}</p>
          <p><strong>Total:</strong> Q{{ Number(compra.total).toFixed(2) }}</p>

          <ul class="list-group mt-3">
            <li
              class="list-group-item d-flex align-items-center"
              v-for="detalle in compra.detalles"
              :key="detalle.id"
            >
              <img
                :src="detalle.producto.imagen_url"
                alt="producto"
                class="rounded me-3"
                style="width: 50px; height: 50px; object-fit: cover"
              />
              <div>
                <strong>{{ detalle.producto.nombre }}</strong><br />
                Q{{ Number(detalle.precio_unitario).toFixed(2) }} x {{ detalle.cantidad }}
                = <strong>Q{{ Number(detalle.subtotal).toFixed(2) }}</strong>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const productos = ref([])
const compras = ref([])
const cargando = ref(false)

const filtros = ref({
  producto_id: '',
  fecha_inicio: '',
  fecha_fin: '',
})

const formatearFecha = (fecha) => {
  return new Date(fecha).toLocaleString('es-GT', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const buscarCompras = async () => {
  cargando.value = true

  if (filtros.value.fecha_inicio && filtros.value.fecha_fin &&
      filtros.value.fecha_fin < filtros.value.fecha_inicio) {
    alert('La fecha final no puede ser anterior a la fecha inicial.')
    cargando.value = false
    return
  }

  try {
    const res = await axios.get('/api/compras', { params: filtros.value })
    compras.value = res.data.data || []
  } catch (err) {
    console.error('Error al cargar compras:', err)
    compras.value = []
  } finally {
    cargando.value = false
  }
}

onMounted(async () => {
  try {
    const res = await axios.get('/api/productos')
    productos.value = res.data.productos || res.data
  } catch (err) {
    console.error('Error al cargar productos:', err)
  }
})
</script>

<style scoped>
.card-title {
  font-size: 1.2rem;
}
</style>
