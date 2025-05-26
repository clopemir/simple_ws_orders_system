<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage, Link, router } from '@inertiajs/vue3';
import { Producto, type BreadcrumbItem, type SharedData } from '@/types';
//Table
import { Table, TableBody, TableCaption, TableCell, TableHead, TableFooter, TableRow, TableHeader} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
//icons
import { Pencil, Trash, CirclePlus, CircleCheckBig, CircleOff } from 'lucide-vue-next';
import { computed } from 'vue';

interface ProductoPageProps extends SharedData {
    productos: Producto[];
}

const {props} = usePage<ProductoPageProps>();
const productos = computed(() => props.productos)

const breadcrumbs: BreadcrumbItem[] = [{title: 'Productos', href: '/productos'}];

// Metodo para eliminar

const deleteProduct = async(id:number) => {
    if(!window.confirm('Seguro de que quieres Eliminar este producto?')) return;

    router.delete(`/productos/${id}`, {
        preserveScroll: true,
        onSuccess: () => {
            router.visit('/productos', {replace: true});
        },
        onError: (e) => {
            console.error('Error al eliminar: ', e)
        }
    });
}

</script>
<template>
   <Head title="Paletas"/>

   <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex">
            <Button as-child size="sm" class="bg-indigo-500 text-white hover:bg-indigo-700">
                <Link href="/productos/create"> <CirclePlus /> Crear</Link>
            </Button>
        </div>


        <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
            <div class="min-w-[600px]">
                <Table>
                    <TableCaption>Lista de productos</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Nombre</TableHead>
                            <TableHead class="hidden sm:table-cell">Descripción</TableHead>
                            <TableHead class="text-right">Precio</TableHead>
                            <TableHead class="hidden sm:table-cell">Imagen</TableHead>
                            <TableHead>Activo</TableHead>
                            <TableHead>En Stock</TableHead>
                            <TableHead class="text-center">Acciones</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="producto in productos" :key="producto.id">
                            <TableCell class="font-medium">{{ producto.nombre }}</TableCell>
                            <TableCell class="hidden sm:table-cell">{{ producto.descripcion ?? 'N/A' }}</TableCell>
                            <TableCell>{{ new Intl.NumberFormat('es-MX', {style: 'currency', currency: 'MXN'}).format(producto.precio) }}</TableCell>

                            <TableCell v-if="producto.imagen" class="text-center"><img class="hidden sm:table-cell size-8 rounded-full ring-2 ring-white" :src="`storage/${producto.imagen}`" :alt="`${producto.nombre}`"></TableCell>
                            <TableCell v-else class="text-center"><img class="hidden sm:table-cell size-8 rounded-full ring-2 ring-white" src="logo/base.png" alt="Base"></TableCell>

                            <TableCell v-if="producto.activo"><CircleCheckBig /></TableCell>
                            <TableCell v-else><CircleOff /></TableCell>
                            <TableCell v-if="producto.instock"><CircleCheckBig /></TableCell>
                            <TableCell v-else><CircleOff /></TableCell>
                            <TableCell class="flex justify-center gap-2">
                                <Button as-child size="sm" class="bg-blue-500 text-white hover:bg-blue-700">
                                    <Link :href="`/productos/${producto.id}/edit`"> <Pencil /> </Link>
                                </Button>

                                <Button size="sm" class="bg-red-500 text-white hover:bg-red-700" @click="deleteProduct(producto.id)">
                                    <Trash />
                                </Button>
                            </TableCell>

                        </TableRow>
                    </TableBody>
                </Table>
            </div>

        </div>
    </div>
   </AppLayout>

</template>
