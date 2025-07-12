<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["toggle"]);
</script>

<template>
    <aside
        class="fixed top-0 left-0 z-40 h-screen w-64 bg-white p-6 shadow-lg transition-transform duration-300 lg:translate-x-0"
        :class="{
            'translate-x-0': isOpen,
            '-translate-x-full': !isOpen,
        }"
    >
        <!-- Logo + Close Button -->
        <div class="flex items-center justify-between mb-8">
            <Link :href="route('home')">
                <ApplicationLogo
                    class="block h-9 w-auto fill-current text-gray-800"
                />
            </Link>

            <button
                class="p-2 rounded-md hover:bg-gray-100 lg:hidden"
                @click="$emit('toggle')"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <!-- Placeholder Sidebar Content -->
        <nav class="space-y-4">
            <p class="text-sm text-gray-500">Sidebar content here…</p>
        </nav>
    </aside>
</template>
