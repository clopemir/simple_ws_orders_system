
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import { Toaster } from 'vue-sonner';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{title: 'Productos', href: '/productos'}, {title: 'Crear', href: '#'}];

//Formulario

const form = ref<Partial<{nombre: string; descripcion: string; precio: number; imagen: File | null;}>>({
    nombre: '',
    descripcion: '',
    precio: undefined,
    imagen: null,
})

const resetForm = () => {
    form.value = {nombre: '', descripcion: '', precio: undefined, imagen: null,}
}
const submit = () => {

    const formData = new FormData();
    formData.append('nombre', form.value.nombre || '');
    formData.append('descripcion', form.value.descripcion || '');
    formData.append('precio', String(form.value.precio || ''));
    if (form.value.imagen) {
        formData.append('imagen', form.value.imagen);
    }

  // 'activo' por defecto en el backend
  router.post('/productos', formData, {
    forceFormData: true,
    onSuccess: resetForm,
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
                    Crear
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
                            @change="(e: Event) => form.imagen = (e.target as HTMLInputElement).files?.[0] || null"
                        />
                    </div>
                                            <div class="flex gap-4">
                        <Button type="submit" class="bg-indigo-500 hover:bg-indigo-600">Guardar</Button>
                        <Button as="a" href="/productos" variant="outline">Cancelar</Button>
                    </div>
                </form>

            </div>
        </AppLayout>

</template>
