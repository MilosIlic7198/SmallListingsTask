<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center"
    >
        <div>
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-800" />
            </Link>
        </div>

        <div
            class="mt-6 w-full bg-white px-6 py-4 shadow-sm sm:max-w-md rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
