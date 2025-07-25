<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    listings: {
        type: Object,
        required: true,
    },
    search: String,
});

const search = ref(props.search || "");

function applySearchFilter() {
    router.get(route("customer.listings"), {
        search: search.value,
    });
}

function clearSearchFilter() {
    search.value = "";
    router.get(route("customer.listings"), {
        search: "",
    });
}

function deleteListing(id) {
    router.delete(route("shared.listings.destroy", id), {
        onSuccess: () => {
            alert("Listing deleted successfully");
        },
        onError: () => {
            alert("Failed to delete listing");
        },
    });
}
</script>

<template>
    <Head title="My Listings" />

    <div class="flex min-h-screen bg-gray-100">
        <!-- Main content -->
        <div class="flex flex-col w-full min-h-screen">
            <AuthenticatedLayout>
                <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-7xl mx-auto">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div
                                class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4"
                            >
                                <h2 class="text-2xl font-bold">My Listings</h2>
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
                                <a
                                    type="button"
                                    :href="route('shared.listings.create')"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Create Listing
                                </a>
                            </div>
                            <div v-if="listings.data.length != 0" class="p-6">
                                <div
                                    class="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                                >
                                    <!-- Listing Card -->
                                    <div
                                        v-for="(
                                            listing, index
                                        ) in listings.data"
                                        :key="index"
                                        id="listing-card"
                                        class="flex flex-col overflow-hidden rounded-lg bg-white p-4 shadow hover:shadow-md ring-1 ring-gray-200 hover:ring-gray-300 transition duration-300 min-h-[360px]"
                                    >
                                        <!-- Image -->
                                        <div
                                            id="image-container"
                                            class="relative w-full overflow-hidden rounded-md"
                                        >
                                            <img
                                                :src="
                                                    listing.image_path
                                                        ? `/storage/${listing.image_path}`
                                                        : '/images/noimage.jpg'
                                                "
                                                alt="Listing Image"
                                                class="w-full h-48 sm:h-56 object-contain object-center rounded-md"
                                            />
                                        </div>

                                        <!-- Content -->
                                        <div
                                            id="listing-card-content"
                                            class="flex flex-col justify-between flex-1 mt-4 space-y-3"
                                        >
                                            <div>
                                                <h2
                                                    class="text-base sm:text-lg font-semibold text-gray-900 line-clamp-2"
                                                >
                                                    {{ listing.title }}
                                                </h2>
                                                <p
                                                    class="mt-1 text-sm text-gray-600 line-clamp-3"
                                                >
                                                    {{ listing.description }}
                                                </p>
                                            </div>
                                            <p
                                                class="text-base font-semibold text-black"
                                            >
                                                {{ listing.price }}
                                            </p>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div
                                            class="mt-4 flex justify-end space-x-2"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'shared.listings.edit',
                                                        listing.id
                                                    )
                                                "
                                                class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                @click="
                                                    deleteListing(listing.id)
                                                "
                                                class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-sm"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pagination -->
                                <div
                                    v-if="listings.links.length > 3"
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
                                                'bg-indigo-600 text-white':
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
                            <p v-else class="p-6 mt-1 text-black">
                                No listings
                            </p>
                        </div>
                    </div>
                </main>
            </AuthenticatedLayout>
        </div>
    </div>
</template>
