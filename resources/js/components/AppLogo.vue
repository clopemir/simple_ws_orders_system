<script setup lang="ts">
// import AppLogoIcon from '@/components/AppLogoIcon.vue';

const  nombre:string = "Tu Nombre" // cambiar

</script>

<template>
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground">
        <!-- <AppLogoIcon class="size-5 fill-current text-white dark:text-black" /> -->
         <img src="logo/logo.svg" alt="Logo">
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate font-semibold leading-none">{{ nombre }}</span>
    </div>
</template>
