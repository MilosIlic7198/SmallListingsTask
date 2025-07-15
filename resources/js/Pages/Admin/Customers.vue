<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    customers: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Customers" />

    <div class="flex min-h-screen bg-gray-100">
        <!-- Main content -->
        <div class="flex flex-col w-full min-h-screen">
            <!-- Authenticated layout -->
            <AuthenticatedLayout>
                <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-7xl mx-auto">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-bold">Customers</h2>
                                <Link
                                    :href="route('admin.customers.create')"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo- FacetGridLayout-500 focus:ring-offset-2 disabled:opacity-50"
                                >
                                    Create
                                </Link>
                            </div>

                            <div
                                v-if="customers.length === 0"
                                class="text-gray-500 text-center py-4"
                            >
                                No customers
                            </div>

                            <div v-else class="overflow-x-auto">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Name
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Email
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Deleted at
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Created at
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200"
                                    >
                                        <tr
                                            v-for="customer in customers"
                                            :key="customer.id"
                                        >
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                            >
                                                {{ customer.name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                            >
                                                {{ customer.email }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                            >
                                                {{
                                                    customer.deleted_at === null
                                                        ? "/"
                                                        : new Date(
                                                              customer.deleted_at
                                                          ).toLocaleDateString()
                                                }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                            >
                                                {{
                                                    new Date(
                                                        customer.created_at
                                                    ).toLocaleDateString()
                                                }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                            >
                                                <div class="flex space-x-2">
                                                    <Link
                                                        :href="
                                                            route(
                                                                'admin.customers.edit',
                                                                customer.id
                                                            )
                                                        "
                                                        class="text-blue-600 hover:text-blue-800"
                                                    >
                                                        Edit
                                                    </Link>
                                                    <button
                                                        v-if="
                                                            customer.deleted_at ===
                                                            null
                                                        "
                                                        @click="
                                                            $inertia.delete(
                                                                route(
                                                                    'admin.customers.destroy',
                                                                    customer.id
                                                                )
                                                            )
                                                        "
                                                        class="text-red-600 hover:text-red-800"
                                                    >
                                                        Delete
                                                    </button>
                                                    <button
                                                        v-else
                                                        @click="
                                                            $inertia.patch(
                                                                route(
                                                                    'admin.customers.restore',
                                                                    customer.id
                                                                )
                                                            )
                                                        "
                                                        class="text-green-600 hover:text-green-800"
                                                    >
                                                        Restore
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </AuthenticatedLayout>
        </div>
    </div>
</template>
