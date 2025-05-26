<script setup>

import AppLayout from '@/layouts/AppLayout.vue';
//import {  BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const props = defineProps({
    pedidos: Number,
    productos: Number,
    total: Number
})



const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3 text-center">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="flex flex-1 flex-col justify-between items-safe font-bold text-3xl p-2">
                        <p>Total de Pedidos</p>
                        <p class="mt-5">{{ pedidos }}</p>
                    </div>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="flex flex-1 flex-col justify-between items-safe font-bold text-3xl p-2">
                        <p>Total de Productos</p>
                        <p class="mt-5">{{ productos }}</p>
                    </div>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="flex flex-1 flex-col justify-between items-safe font-bold text-3xl p-2">
                        <p>Total Vendido</p>
                        <p class="mt-5">$ {{ total.toFixed(2) }}</p>


                    </div>
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
