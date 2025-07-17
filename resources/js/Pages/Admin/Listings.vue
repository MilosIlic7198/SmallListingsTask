<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    listings: Object,
    search: String,
});

const search = ref(props.search || "");

function applySearchFilter() {
    router.get(route("admin.listings.index"), {
        search: search.value,
    });
}

function clearSearchFilter() {
    search.value = "";
    router.get(route("admin.listings.index"), {
        search: "",
    });
}
</script>

<template>
    <Head title="Listings" />

    <div class="flex min-h-screen bg-gray-100">
        <div class="flex flex-col w-full min-h-screen">
            <AuthenticatedLayout>
                <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-7xl mx-auto">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div
                                class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4"
                            >
                                <h2 class="text-2xl font-bold">Listings</h2>
                                <!-- Search Form -->
                                <div
                                    class="flex justify-center w-full sm:w-auto"
                                >
                                    <form
                                        @submit.prevent="applySearchFilter"
                                        class="flex flex-col sm:flex-row gap-2 items-center w-full max-w-lg"
                                    >
                                        <input
                                            v-model="search"
                                            type="text"
                                            placeholder="Search listings..."
                                            class="flex-1 rounded-md border-gray-300 shadow-sm px-4 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                        />
                                        <button
                                            type="submit"
                                            class="bg-red-600 text-white rounded-md py-2 px-4 text-sm hover:bg-red-700 transition"
                                        >
                                            Search
                                        </button>
                                        <button
                                            type="button"
                                            @click="clearSearchFilter"
                                            class="bg-gray-600 text-white rounded-md py-2 px-4 text-sm hover:bg-gray-700 transition"
                                        >
                                            Clear
                                        </button>
                                    </form>
                                </div>
                                <Link
                                    :href="route('shared.listings.create')"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-center"
                                >
                                    Create
                                </Link>
                            </div>

                            <div
                                v-if="listings.data.length === 0"
                                class="text-gray-500 text-center py-4"
                            >
                                No listings
                            </div>

                            <div
                                v-else
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
                            >
                                <div
                                    v-for="listing in listings.data"
                                    :key="listing.id"
                                    class="bg-white rounded-lg shadow-md overflow-hidden"
                                >
                                    <div
                                        v-if="
                                            listing.image_path &&
                                            listing.deleted_at === null
                                        "
                                        class="h-48 w-full"
                                    >
                                        <img
                                            :src="
                                                '/storage/' + listing.image_path
                                            "
                                            alt="Listing image"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                    <div
                                        v-else
                                        class="h-48 w-full bg-gray-200 flex items-center justify-center"
                                    >
                                        <span class="text-gray-500"
                                            >No Image</span
                                        >
                                    </div>
                                    <div class="p-4">
                                        <h3
                                            class="text-lg font-semibold text-gray-900 truncate"
                                        >
                                            {{ listing.title }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{
                                                listing.description
                                                    ? listing.description.substring(
                                                          0,
                                                          100
                                                      ) +
                                                      (listing.description
                                                          .length > 100
                                                          ? "..."
                                                          : "")
                                                    : "-"
                                            }}
                                        </p>
                                        <div class="mt-2 text-sm text-gray-700">
                                            <span class="font-medium"
                                                >Price:</span
                                            >
                                            {{ listing.price }}
                                        </div>
                                        <div class="text-sm text-gray-700">
                                            <span class="font-medium"
                                                >Condition:</span
                                            >
                                            {{ listing.condition }}
                                        </div>
                                        <div class="text-sm text-gray-700">
                                            <span class="font-medium"
                                                >Category:</span
                                            >
                                            {{ listing.category?.name ?? "-" }}
                                        </div>
                                        <div class="text-sm text-gray-700">
                                            <span class="font-medium"
                                                >User:</span
                                            >
                                            {{ listing.user?.name ?? "-" }}
                                        </div>
                                        <div class="text-sm text-gray-700">
                                            <span class="font-medium"
                                                >User:</span
                                            >
                                            {{ listing.phone }}
                                        </div>
                                        <div class="text-sm text-gray-700">
                                            <span class="font-medium"
                                                >Created:</span
                                            >
                                            {{
                                                new Date(
                                                    listing.created_at
                                                ).toLocaleDateString()
                                            }}
                                        </div>
                                        <div class="text-sm text-gray-700">
                                            <span class="font-medium"
                                                >Deleted:</span
                                            >
                                            {{
                                                listing.deleted_at
                                                    ? new Date(
                                                          listing.deleted_at
                                                      ).toLocaleDateString()
                                                    : "/"
                                            }}
                                        </div>
                                        <!-- Action buttons -->
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <Link
                                                :href="
                                                    route(
                                                        'shared.listings.edit',
                                                        listing.id
                                                    )
                                                "
                                                class="inline-block px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-md text-sm font-medium transition"
                                            >
                                                Edit
                                            </Link>

                                            <button
                                                v-if="
                                                    listing.deleted_at === null
                                                "
                                                @click="
                                                    $inertia.delete(
                                                        route(
                                                            'shared.listings.destroy',
                                                            listing.id
                                                        )
                                                    )
                                                "
                                                class="inline-block px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-md text-sm font-medium transition"
                                            >
                                                Delete
                                            </button>

                                            <button
                                                v-else
                                                @click="
                                                    $inertia.patch(
                                                        route(
                                                            'shared.listings.restore',
                                                            listing.id
                                                        )
                                                    )
                                                "
                                                class="inline-block px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded-md text-sm font-medium transition"
                                            >
                                                Restore
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div
                                class="mt-8 flex flex-wrap justify-center gap-2"
                            >
                                <template
                                    v-for="(link, index) in listings.links"
                                    :key="index"
                                >
                                    <Link
                                        v-if="link.url"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="px-4 py-2 rounded-md border text-sm transition"
                                        :class="{
                                            'bg-red-600 text-white':
                                                link.active,
                                            'text-gray-700 hover:bg-gray-200':
                                                !link.active,
                                        }"
                                    />
                                    <span
                                        v-else
                                        v-html="link.label"
                                        class="px-4 py-2 rounded-md border text-sm text-gray-400"
                                    />
                                </template>
                            </div>
                        </div>
                    </div>
                </main>
            </AuthenticatedLayout>
        </div>
    </div>
</template>
