<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const isSidebarOpen = ref(false);
function toggleSidebar() {
    isSidebarOpen.value = !isSidebarOpen.value;
}
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <Sidebar :isOpen="isSidebarOpen" @toggle="toggleSidebar" />

        <!-- Main content -->
        <div
            class="flex flex-col w-full min-h-screen transition-all duration-300 lg:ml-64"
            :class="{ 'lg:ml-64': isSidebarOpen }"
        >
            <!-- Mobile toggle -->
            <button
                class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-md shadow-md"
                @click="toggleSidebar"
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
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>

            <!-- Authenticated layout -->
            <AuthenticatedLayout>
                <main class="flex-1 py-6 px-6 sm:px-8 lg:px-10">
                    <div class="max-w-7xl mx-auto">
                        <div
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6 text-gray-900">
                                You are in listings!
                            </div>
                        </div>
                    </div>
                </main>
            </AuthenticatedLayout>
        </div>
    </div>
</template>
