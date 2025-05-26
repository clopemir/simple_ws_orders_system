
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { onMounted, reactive, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem, Producto } from '@/types';
import { Toaster } from '@/components/ui/sonner';
import { toast } from 'vue-sonner';

const {props} = usePage();
const producto = props.producto as Producto;

const breadcrumbs: BreadcrumbItem[] = [{title: 'Productos', href: '/productos'}, {title: 'Editar', href: '#'}];

const previewUrl = ref<string | null>(null);

const handleImageChange = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0];
  if (file) {
    form.imagen = file;
    previewUrl.value = URL.createObjectURL(file);
  }
};

//Formulario

const form = reactive<Partial<{nombre: string; descripcion: string; precio: number; imagen: File | null; activo: boolean; instock: boolean;}>>({
    nombre: '',
    descripcion: '',
    precio: undefined,
    imagen: null,
    activo: true,
    instock: false

})

onMounted(() => {
    form.nombre = producto.nombre;
    form.descripcion = producto.descripcion ?? '';
    form.precio = Number(producto.precio);
    //form.imagen = producto.imagen ?? 'NA';
    form.activo = Boolean(producto.activo);
    form.instock = Boolean(producto.instock);
    if (producto.imagen) {
    previewUrl.value = `/storage/${producto.imagen}`; // Ajusta si la ruta es diferente
  }
})

// const resetForm = () => {
//     form.nombre = '';
//     form.descripcion = '';
//     form.precio = undefined;
//     form.imagen = null;
//     form.activo = true;
// }

const submit = () => {

    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('nombre', form.nombre || '');
    formData.append('descripcion', form.descripcion || '');
    formData.append('precio', String(form.precio || ''));
    formData.append('activo', form.activo ? '1' : '0');
    formData.append('instock', form.instock ? '1' : '0');


    if (form.imagen instanceof File) {
        formData.append('imagen', form.imagen);
    }

  router.post(`/productos/${producto.id}`, formData, {
    forceFormData: true,
    onSuccess: () => {
        // Notificación si usas toast o similar
        //alert('Producto actualizado con éxito');
        toast('Actualizado', {
          description: 'El producto se actualizo correctamente',
          action: {
            label: 'Cerrar'
          },
        });
        setTimeout(() => {
            router.visit('/productos');
        },2000)

    },
    onError: (errors) => {
        console.error(errors);
    }
  });

}
</script>

<template>
    <Toaster />
    <Head title="Nuevo Producto"/>
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
                <h1 class="text-2xl font-bold">
                    Editar
                </h1>

                <form @submit.prevent="submit" class="space-y-6 max-w-lg">
                    <div v-for="(label, key) in {nombre: 'Nombre', descripcion: 'Descripción', precio: 'Precio'}" :key="key" class="space-y-2">
                        <Label :for="key">{{ label }}</Label>
                        <Input
                            :id="key"
                            v-model="form[key]"
                            :type="key === 'precio' ? 'number' : 'text'"
                            :placeholder="label"
                            :required="key"
                            :step="key === 'precio' ? '0.50':undefined"
                        />
                    </div>
                    <div class="space-y-2">
                        <Label for="imagen">Imagen</Label>
                        <Input
                            id="imagen"
                            type="file"
                            accept="image/*"
                            @change="handleImageChange"
                        />
                        <div v-if="previewUrl" class="mt-2">
                            <img :src="previewUrl" alt="Vista previa" class="h-32 object-contain rounded" />
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="activo">Activo</label>
                        <input
                            id="activo"
                            type="checkbox"
                            v-model="form.activo"
                            class="w-5 h-5"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="instock">Disponible para entrega?</label>
                        <input
                            id="instock"
                            type="checkbox"
                            v-model="form.instock"
                            class="w-5 h-5"
                        />
                    </div>
                    <div class="flex gap-4">
                        <Button type="submit" class="bg-indigo-500 hover:bg-indigo-600">Guardar Cambios</Button>
                        <Button as="a" href="/productos" variant="outline">Cancelar</Button>
                    </div>
                </form>

            </div>
        </AppLayout>

</template>
