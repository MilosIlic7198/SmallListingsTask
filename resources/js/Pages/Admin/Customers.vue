<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex min-h-screen bg-gray-100">
        <!-- Main content -->
        <div class="flex flex-col w-full min-h-screen">
            <!-- Authenticated layout -->
            <AuthenticatedLayout>
                <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-7xl mx-auto">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="text-gray-900">
                                You are in customers!
                            </div>
                        </div>
                    </div>
                </main>
            </AuthenticatedLayout>
        </div>
    </div>
</template>
