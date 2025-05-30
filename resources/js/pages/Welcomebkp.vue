<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { Producto } from '@/types';
import { watch } from 'vue';
import { PlaneTakeoff } from 'lucide-vue-next';



interface CarritoItem extends Producto {
    cantidad: number;
}

interface Props {
    //productos: Producto[];
    productos: {
    data: Producto[],
    current_page: number,
    last_page: number,
    links: {
      url: string | null,
      label: string,
      active: boolean
    }[]
  };
}


const colores = [
  '#BAE6FF',
  '#D0E2FF',
  '#FFD6E8',
  '#A7F0BA',
  '#08BDBA',
  '#F1C21B'
];

const props = defineProps<Props>();


const number = '5214626016703'
const tienda = 'Helados Franco'

const carrito = ref<CarritoItem[]>([]);
const productosDisponibles = ref<Producto[]>([...props.productos.data]);
const currentYear = ref<number>(new Date().getFullYear())

const nombre = ref<string>('');
const direccion = ref<string>('');
const telefonoCliente = ref<string>('');
const metodoPago = ref<string>('')

const notificacion = ref<string>('');
const mostrarNotificacion = ref<boolean>(false);

function cambiarPagina(url: string) {
  if (url) {
    router.visit(url, { preserveScroll: true });
  }
}

const carritoTotal = computed<string>(() => {
    const total = carrito.value.reduce((sum, item) => sum + item.precio * item.cantidad, 0);
    return total;
});

function agregarAlCarrito(productoId: number): void {
  const producto = productosDisponibles.value.find(p => p.id === productoId);
  if (!producto) return

  const item = carrito.value.find(p => p.id === productoId)
  if (item) {
    item.cantidad++
  } else {
    carrito.value.push({ ...producto, cantidad: 1 })
  }

  guardarCarrito()
  mostrarMensaje(`"${producto.nombre}" agregado al carrito.`)
}

function quitarDelCarrito(productoId: number): void {
  const producto = carrito.value.find(p => p.id === productoId)
  carrito.value = carrito.value.filter(p => p.id !== productoId)
  guardarCarrito()
  if (producto) mostrarMensaje(`"${producto.nombre}" eliminado del carrito.`)
}

function actualizarCantidad(productoId: number, cantidad: number): void {
    const item = carrito.value.find(p => p.id === productoId)
//   const nuevaCantidad = parseInt(cantidad)
    if (!item) return;

    const nuevaCantidad = item.cantidad + cantidad


    if (nuevaCantidad <= 0) {
        quitarDelCarrito(productoId)
        return
    }

    item.cantidad = nuevaCantidad
    guardarCarrito()
}

function guardarCarrito(): void {
  localStorage.setItem('carritoHF', JSON.stringify(carrito.value))
}

function eliminarCarrito(): void {
  localStorage.removeItem('carritoHF');
}

function cargarCarrito():void {
  const guardado = localStorage.getItem('carritoHF')
  if (guardado) {
    try {
      const data = JSON.parse(guardado) as CarritoItem[];
      carrito.value = data;
    } catch {
      carrito.value = [];
    }
  }
}

function estaAlLimite(productoId: number): boolean {
  const item = carrito.value.find(p => p.id === productoId);
  return item?.cantidad >= 10;
}

function mostrarMensaje(msg: string): void {
  notificacion.value = msg
  mostrarNotificacion.value = true
  setTimeout(() => mostrarNotificacion.value = false, 3000)
}


function cerrarNotificacionModal(): void {
  mostrarNotificacion.value = false;
}

function generarMensajeWhatsApp(): string | null {
  if (carrito.value.length === 0) {
    mostrarMensaje("Tu carrito está vacío. Agrega productos antes de enviar el pedido.")
    return null;
  }

  if (!nombre.value.trim() || !direccion.value.trim()) {
    mostrarMensaje("Por favor, completa tu Nombre y Dirección.")
    return null;
  }

  let mensaje = `¡Hola ${tienda}! 👋 Me gustaría hacer el siguiente pedido:\n\n`
  mensaje += "🍦 *PRODUCTOS:*\n"
  carrito.value.forEach(item => {
    mensaje += `  - ${item.nombre} (x${item.cantidad}) - $${(item.precio * item.cantidad)}\n`
  })

  mensaje += `\n💰 *TOTAL:* $${carritoTotal.value} MXN\n`
  mensaje += `\n👤 *DATOS:*\n`
  mensaje += `   - Nombre: ${nombre.value}\n`
  mensaje += `   - Dirección: ${direccion.value}\n`
  if (telefonoCliente.value) mensaje += `   - Teléfono: ${telefonoCliente.value}\n`
  mensaje += `   - Método de pago: ${metodoPago.value === 'efectivo' ? 'Efectivo' : 'Transferencia'}\n`;

  mensaje += `\n¡Gracias! 😊`

  return mensaje
}

function enviarPedido(): void {
  const msg = generarMensajeWhatsApp()
  if (msg) {
    const msgenc = encodeURIComponent(msg)
    const url = `https://wa.me/${number}?text=${msgenc}`
    window.open(url, '_blank')

    eliminarCarrito();
    nombre.value = '';
    direccion.value = '';
    telefonoCliente.value = '';
    metodoPago.value = 'efectivo';

    window.location.reload()


  }
}

watch(() => props.productos.data, (nuevosProductos) => {
  productosDisponibles.value = [...nuevosProductos];
});

onMounted(() => {
  cargarCarrito()
})
</script>

<template>
    <Head title="Paletas de Hielo Franco" />
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 p-4 sm:p-6 lg:p-10 transition-colors duration-300">

        <h1 class="flex items-center justify-center gap-3 text-2xl sm:text-4xl font-bold text-center mb-8 text-pink-600 dark:text-pink-400">
            <img src="/logo/logo.svg"  class="w-16 h-16" alt="logo">
            <span>Paletas de Hielo Franco</span>
        </h1>

      <section class="mb-10">
        <h2 class="text-2xl sm:text-3xl font-semibold mb-4 text-gray-700 dark:text-gray-300">Productos disponibles</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
          <div
            v-for="(producto, index) in productosDisponibles"
            :key="producto.id"
            class="bg-white dark:bg-gray-800 shadow-md dark:shadow-xl rounded-2xl p-2 flex flex-col transition-colors duration-300"
          >
            <div class="relative h32 flex items-center justify-center mb-4">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="absolute w-32 h-32 z-0">
                    <path :fill="colores[index % colores.length]" d="M20.3,-31.5C31.1,-24.7,48,-27.2,53.2,-22.4C58.3,-17.6,51.8,-5.5,50.8,7.9C49.7,21.3,54.3,36.1,50,46.3C45.8,56.5,32.9,62.1,21,60.5C9.2,58.9,-1.4,50.1,-11.1,44.3C-20.8,38.6,-29.6,35.9,-39.5,30.7C-49.4,25.6,-60.6,17.9,-68.4,5.9C-76.2,-6.1,-80.7,-22.5,-73.8,-32.1C-66.9,-41.7,-48.6,-44.4,-34.4,-49.7C-20.2,-55,-10.1,-62.9,-2.7,-58.7C4.8,-54.6,9.5,-38.4,20.3,-31.5Z" transform="translate(100 100)" />
                </svg>
                <img v-if="producto.imagen" :src="`storage/${producto.imagen}`" :alt="producto.nombre" class="relative h-24 z-10 object-contai">
                <img v-else src="/logo/base.png" alt="General" class="relative h-24 z-10 object-contai"/>
            </div>
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">{{ producto.nombre }}</h3>
                <span v-if="producto.instock" class="inline-flex items-center gap-1 rounded-md bg-blue-700/20 px-2 py-0.5 text-sm font-medium text-blue-500">  <PlaneTakeoff /> Entrega Inmediata</span>
            </div>
            <p class="text-sm mb-2 py-2 text-gray-600 dark:text-gray-100">{{ producto.descripcion }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">${{ producto.precio }}</p>
            <!-- <button
              @click="agregarAlCarrito(producto.id)"
              class="mt-auto bg-pink-500 hover:bg-pink-600 text-white py-2 px-4 rounded-xl transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:bg-pink-700 dark:hover:bg-pink-800"
            > -->
            <button
                @click="agregarAlCarrito(producto.id)"
                :disabled="estaAlLimite(producto.id)"
                :class="[
                    'mt-auto py-2 px-4 rounded-xl transition duration-300 ease-in-out transform',
                    estaAlLimite(producto.id)
                    ? 'bg-gray-400 cursor-not-allowed text-white'
                    : 'bg-pink-500 hover:bg-pink-600 text-white hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:bg-pink-700 dark:hover:bg-pink-800'
                ]"
            >
            {{ estaAlLimite(producto.id) ? 'Máx. en carrito' : 'Agregar al carrito' }}
            </button>
          </div>
        </div>
        <div class="flex justify-center mt-6 space-x-2">
          <button
              v-for="link in props.productos.links"
              :key="link.label"
              v-html="link.label"
              :disabled="!link.url"
              @click="cambiarPagina(link.url!)"
              class="px-3 py-1 border rounded"
              :class="{
              'bg-pink-500 text-white': link.active,
              'text-gray-500 cursor-not-allowed': !link.url,
              }"
          />
        </div>
      </section>

      <section class="mb-10">
        <h2 class="text-2xl sm:text-3xl font-semibold mb-4 text-gray-700 dark:text-gray-300">🛒 Carrito</h2>
        <div v-if="carrito.length > 0" class="space-y-4">
          <div
            v-for="(item, index) in carrito"
            :key="item.id"
            class="bg-white dark:bg-gray-800 rounded-xl shadow dark:shadow-xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between transition-colors duration-300"
          >
            <div class="flex items-center mb-3 sm:mb-0">

                <div class="relative h32 flex items-center justify-center mb-4">
                    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="absolute w-18 h-18 z-0">
                        <path :fill="colores[index % colores.length]" d="M20.3,-31.5C31.1,-24.7,48,-27.2,53.2,-22.4C58.3,-17.6,51.8,-5.5,50.8,7.9C49.7,21.3,54.3,36.1,50,46.3C45.8,56.5,32.9,62.1,21,60.5C9.2,58.9,-1.4,50.1,-11.1,44.3C-20.8,38.6,-29.6,35.9,-39.5,30.7C-49.4,25.6,-60.6,17.9,-68.4,5.9C-76.2,-6.1,-80.7,-22.5,-73.8,-32.1C-66.9,-41.7,-48.6,-44.4,-34.4,-49.7C-20.2,-55,-10.1,-62.9,-2.7,-58.7C4.8,-54.6,9.5,-38.4,20.3,-31.5Z" transform="translate(100 100)" />
                    </svg>
                    <img v-if="item.imagen" :src="`storage/${item.imagen}`" :alt="item.nombre" class="relative h-12 z-10 object-contai">
                    <img v-else src="/logo/base.png" alt="General" class="relative h-12 z-10 object-contai"/>
                </div>
                 <!-- <img v-if="item.imagen" :src="`storage/${item.imagen}`" :alt="item.nombre" class="w-12 h-12 object-cover rounded-md mr-4">
                 <img v-else src="/logo/logo.svg" alt="Sin imagen" class="w-full h-24 object-cover rounded-md mb-4"/> -->
                <div>
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">{{ item.nombre }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Precio: ${{ item.precio }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Subtotal: ${{ (item.precio * item.cantidad) }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
              <button @click="actualizarCantidad(item.id, -1)" :disabled="item.cantidad === 1" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">-</button>
              <span class="text-gray-800 dark:text-gray-200">{{ item.cantidad }}</span>
              <button @click="actualizarCantidad(item.id, 1)" :disabled="item.cantidad === 10" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">+</button>
              <button @click="quitarDelCarrito(item.id)" class="ml-4 text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-500 transition">Eliminar</button>
            </div>
          </div>

          <div class="text-right text-lg font-bold text-gray-800 dark:text-gray-200">
            Total: ${{ carritoTotal }}
          </div>
        </div>
        <div v-else class="text-gray-500 dark:text-gray-400">Tu carrito está vacío.</div>
      </section>

      <section class="mb-10">
        <h2 class="text-2xl sm:text-3xl font-semibold mb-4 text-gray-700 dark:text-gray-300">📋 Tus datos</h2>
        <form class="space-y-6 max-w-xl mx-auto">
          <div>
            <label for="nombre" class="block font-medium text-gray-700 dark:text-gray-300">Nombre *</label>
            <input
              v-model="nombre"
              id="nombre"
              type="text"
              class="mt-1 w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 transition-colors duration-300"
              required
            />
          </div>
          <div>
            <label for="direccion" class="block font-medium text-gray-700 dark:text-gray-300">Dirección *</label>
            <textarea
              v-model="direccion"
              id="direccion"
              class="mt-1 w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 transition-colors duration-300"
              required
            ></textarea>
          </div>
          <div>
            <label for="telefono" class="block font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
            <input
              v-model="telefonoCliente"
              id="telefono"
              type="text"
              class="mt-1 w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 transition-colors duration-300"
            />
          </div>

          <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Método de Pago *</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            v-model="metodoPago"
                            value="efectivo"
                            class="form-radio text-pink-600 focus:ring-pink-400 dark:text-pink-400 dark:focus:ring-pink-600"
                            required
                        />
                        <span class="ml-2 text-gray-800 dark:text-gray-200">Efectivo</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            v-model="metodoPago"
                            value="transferencia"
                            class="form-radio text-pink-600 focus:ring-pink-400 dark:text-pink-400 dark:focus:ring-pink-600"
                            required
                        />
                        <span class="ml-2 text-gray-800 dark:text-gray-200">Transferencia</span>
                    </label>
                </div>
            </div>
        </form>
      </section>

      <div class="text-center mt-8">
        <button
          @click="enviarPedido"
          class="bg-green-500 hover:bg-green-600 text-white text-lg font-semibold px-6 py-3 rounded-2xl transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-400 dark:bg-green-700 dark:hover:bg-green-800"
        >
          📤 Enviar pedido por WhatsApp
        </button>
      </div>

      <transition name="fade">
        <div
          v-if="mostrarNotificacion"
          class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-pink-600 dark:bg-pink-700 text-white px-6 py-3 rounded-full shadow-lg z-50 transition-colors duration-300"
        >
          {{ notificacion }}
          <button @click="cerrarNotificacionModal" class="ml-4 text-white font-bold focus:outline-none">×</button>
        </div>
      </transition>

      <footer class="mt-16 text-center text-sm text-gray-500 dark:text-gray-400">
        &copy; {{ currentYear }} Paletas de Hielo Franco. Todos los derechos reservados.
      </footer>
    </div>
</template>

<style scoped>
/* Estilos para la transición de la notificación */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>