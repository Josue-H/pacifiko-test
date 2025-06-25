<template>
  <div class="container py-4">
    <!-- Ícono del carrito -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <!-- Botón a la izquierda -->
      <button class="btn btn-outline-secondary" @click="verHistorial">
        <i class="fas fa-clock-rotate-left me-1"></i> Ver historial
      </button>

      <!-- Botón del carrito a la derecha -->
      <button
        class="btn btn-purple position-relative"
        @click="mostrarModal = true"
      >
        <i class="fas fa-cart-shopping me-1"></i> Carrito
        <span
          class="badge bg-danger position-absolute top-0 start-100 translate-middle"
        >
          {{ carrito.length }}
        </span>
      </button>
    </div>

    <h2 class="mb-4 text-center">
      <i class="fas fa-store me-2"></i>Productos disponibles
    </h2>

    <!-- Grid de productos -->
    <div class="row justify-content-center">
      <div
        class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4"
        v-for="producto in productos"
        :key="producto.id"
      >
        <div class="card h-100 shadow-sm producto-card">
          <div class="card-img-wrapper" @click="verDetalle(producto)">
            <img
              :src="producto.imagen_url"
              class="card-img-top img-fluid"
              alt="Producto"
            />
          </div>
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title mb-1">{{ producto.nombre }}</h5>
            <p class="card-text text-muted mb-1">{{ producto.descripcion }}</p>
            <p class="fw-bold mb-2">Q{{ producto.precio }}</p>

            <div
              v-if="obtenerCantidad(producto.id) > 0"
              class="d-flex justify-content-between align-items-center"
            >
              <button
                class="btn btn-outline-secondary btn-sm"
                @click="cambiarCantidad(producto.id, -1)"
              >
                ➖
              </button>
              <span class="fw-bold">{{ obtenerCantidad(producto.id) }}</span>
              <button
                class="btn btn-outline-secondary btn-sm"
                @click="cambiarCantidad(producto.id, 1)"
              >
                ➕
              </button>
            </div>

            <button
              v-else
              class="btn btn-success w-100"
              @click.stop="agregarAlCarrito(producto)"
            >
              Agregar al carrito
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal detalle producto -->
    <div
      class="modal fade show d-block"
      v-if="productoSeleccionado"
      tabindex="-1"
    >
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ productoSeleccionado.nombre }}</h5>
            <button
              type="button"
              class="btn-close"
              @click="productoSeleccionado = null"
            ></button>
          </div>
          <div class="modal-body">
            <img
              :src="productoSeleccionado.imagen_url"
              class="img-fluid rounded mb-3"
            />
            <p>
              <strong>Descripción:</strong>
              {{ productoSeleccionado.descripcion }}
            </p>
            <p><strong>Precio:</strong> Q{{ productoSeleccionado.precio }}</p>
            <p><strong>Stock:</strong> {{ productoSeleccionado.stock }}</p>
            <p class="text-muted">
              Publicado: {{ formatearFecha(productoSeleccionado.created_at) }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal carrito -->
    <div class="modal fade show d-block" v-if="mostrarModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="fas fa-receipt me-2"></i>Tu carrito
            </h5>

            <button
              type="button"
              class="btn-close"
              @click="mostrarModal = false"
            ></button>
          </div>
          <div class="modal-body">
            <ul v-if="carrito.length > 0" class="list-group mb-3">
              <li
                class="list-group-item d-flex justify-content-between align-items-center"
                v-for="item in carrito"
                :key="item.producto.id"
              >
                <div>
                  {{ item.producto.nombre }} — Q{{ item.producto.precio }} c/u
                </div>
                <div class="d-flex align-items-center">
                  <button
                    class="btn btn-sm btn-outline-secondary me-2"
                    @click="cambiarCantidad(item.producto.id, -1)"
                  >
                    <i class="fas fa-minus"></i>
                  </button>
                  <span>{{ item.cantidad }}</span>
                  <button
                    class="btn btn-sm btn-outline-secondary ms-2"
                    @click="cambiarCantidad(item.producto.id, 1)"
                  >
                    <i class="fas fa-plus"></i>
                  </button>

                  <button
                    class="btn btn-sm btn-danger ms-3"
                    @click="quitarDelCarrito(item.producto.id)"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </li>
            </ul>

            <p v-else class="text-muted">Tu carrito está vacío.</p>

            <div v-if="carrito.length > 0">
              <p><strong>Total:</strong> Q{{ total }}</p>
              <div class="form-group mb-2">
                <label>Nombre del comprador</label>
                <input
                  v-model="form.comprador"
                  type="text"
                  class="form-control"
                  placeholder="Ej. Juan Pérez"
                />
              </div>
              <button class="btn btn-primary" @click="enviarCompra">
                Confirmar compra
              </button>
              <button
                class="btn btn-secondary ms-2"
                @click="mostrarModal = false"
              >
                Cancelar
              </button>
              <p v-if="error" class="text-danger mt-2">{{ error }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import axios from "axios";
import { Inertia } from '@inertiajs/inertia'

const productos = ref([]);
const carrito = ref([]);
const mostrarModal = ref(false);
const productoSeleccionado = ref(null);
const mensaje = ref("");
const error = ref("");


const verHistorial = () => {
  Inertia.visit('/historial')
}
const form = reactive({
  comprador: "",
});

onMounted(async () => {
  const res = await axios.get("/api/productos");
  productos.value = res.data.productos || res.data;
});

const agregarAlCarrito = (producto) => {
  carrito.value.push({
    producto,
    cantidad: 1,
    subtotal: producto.precio,
  });
};

const quitarDelCarrito = (id) => {
  carrito.value = carrito.value.filter((item) => item.producto.id !== id);
};

const cambiarCantidad = (id, delta) => {
  const item = carrito.value.find((item) => item.producto.id === id);
  if (!item) return;

  item.cantidad += delta;
  if (item.cantidad <= 0) {
    quitarDelCarrito(id);
  } else {
    item.subtotal = item.cantidad * item.producto.precio;
  }
};

const obtenerCantidad = (id) => {
  const item = carrito.value.find((item) => item.producto.id === id);
  return item ? item.cantidad : 0;
};

const verDetalle = (producto) => {
  productoSeleccionado.value = producto;
};

const total = computed(() =>
  carrito.value.reduce((acc, item) => acc + item.subtotal, 0)
);

const enviarCompra = async () => {
  error.value = "";

  const payload = {
    comprador: form.comprador,
    productos: carrito.value.map((item) => ({
      producto_id: item.producto.id,
      cantidad: item.cantidad,
    })),
  };

  try {
    const res = await axios.post("/api/compras", payload);

    const { compra_id, total, mensaje: msg } = res.data;

    if (res.status === 201 && compra_id) {
      if (window.toastr) {
        toastr.success(
          `Compra #${compra_id} registrada con éxito<br>Total: Q${Number(
            total
          ).toFixed(2)}`,
          "¡Éxito!",
          {
            closeButton: true,
            progressBar: true,
            escapeHtml: false,
          }
        );
      }

      carrito.value = [];
      form.comprador = "";
      mostrarModal.value = false;
    } else {
      error.value = "La respuesta del servidor no es válida.";
    }
  } catch (e) {
    console.error("Error en la compra:", e);
    error.value = e.response?.data?.error || "Error al procesar la compra";
  }
};

const formatearFecha = (fecha) => {
  return new Date(fecha).toLocaleDateString();
};
</script>

<style scoped>
.producto-card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  cursor: pointer;
}
.producto-card:hover {
  transform: scale(1.02);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}
.card-img-wrapper {
  height: 180px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fff;
  padding: 10px;
}
.card-img-wrapper img {
  max-height: 100%;
  max-width: 100%;
  object-fit: contain;
}
.modal {
  background-color: rgba(0, 0, 0, 0.6);
}
</style>
