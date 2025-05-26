<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
//Table
import { Table, TableBody, TableCaption, TableCell, TableHead, TableFooter, TableRow, TableHeader} from '@/components/ui/table';


const props = defineProps({
    reporte: {
        type: Object,
        required: true
    }
})

//console.log(props.reporte);

const breadcrumbs= [{title: 'Productos a Surtir', href: '/pedidos'}];



</script>
<template>
   <Head title="Paletas"/>

   <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

        <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
            <div class="min-w-[600px]">
                <Table>
                    <TableCaption>Productos a comprar Hoy</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Producto</TableHead>
                            <TableHead class="hidden sm:table-cell">Cantidad</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="producto in props.reporte" :key="producto.id">
                            <TableCell class="font-medium">{{ producto.nombre }}</TableCell>
                            <TableCell class="hidden sm:table-cell">{{ producto.total_cantidad ?? 'N/A' }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

        </div>
    </div>
   </AppLayout>

</template>
