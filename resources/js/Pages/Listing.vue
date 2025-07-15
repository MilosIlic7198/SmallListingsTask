<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { useStore } from "@/stores/Store";
import { usePage, Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
    listing: {
        type: Object,
    },
    categories: {
        type: Array,
    },
});

const { props } = usePage();
const { errors, auth, listing } = props;
const store = useStore(); // Use the store

const isSidebarOpen = ref(false);
function toggleSidebar() {
    isSidebarOpen.value = !isSidebarOpen.value;
}
</script>

<template>
    <Head :title="listing.title" />

    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <Sidebar
            :isOpen="isSidebarOpen"
            @toggle="toggleSidebar"
            :categories="categories"
        />

        <!-- Main Content -->
        <div
            class="flex flex-col w-full min-h-screen transition-all duration-300 lg:ml-64"
            :class="{ 'lg:ml-64': isSidebarOpen }"
        >
            <!-- Mobile Sidebar Toggle -->
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

            <!-- Header -->
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <header
                    class="grid grid-cols-1 items-center gap-4 py-8 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <!-- Logo -->
                    <div
                        class="flex justify-center sm:col-start-1 sm:justify-start lg:col-start-2 lg:justify-center"
                    >
                        <Link :href="route('publicIndex')">
                            <ApplicationLogo
                                class="h-16 w-16 fill-current text-gray-800"
                            />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <nav
                        class="flex justify-center sm:justify-end sm:col-start-2 sm:col-span-1 lg:col-start-3"
                    >
                        <Link
                            v-if="auth.isGuest"
                            :href="route('login')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="auth.isGuest"
                            :href="route('register')"
                            class="ml-4 rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                        >
                            Register
                        </Link>
                        <Link
                            v-if="
                                !auth.isGuest &&
                                (auth.role === 'admin' ||
                                    auth.role === 'customer')
                            "
                            :href="route('dashboard')"
                            class="ml-4 rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                        >
                            Dashboard
                        </Link>
                    </nav>
                </header>
            </div>

            <!-- Main Section -->
            <main class="mt-6 px-4 sm:px-6 lg:px-8">
                <div
                    class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6"
                >
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Image -->
                        <div
                            id="image-container"
                            class="relative w-full max-h-[400px] flex items-center justify-center"
                        >
                            <img
                                :src="
                                    listing.image_path
                                        ? `/storage/${listing.image_path}`
                                        : '/images/noimage.jpg'
                                "
                                alt="Listing Image"
                                class="w-full h-auto max-h-[400px] rounded-md object-contain"
                            />
                        </div>

                        <!-- Listing Details -->
                        <div class="flex flex-col gap-4">
                            <h1 class="text-2xl font-bold text-black">
                                {{ listing.title }}
                            </h1>
                            <p class="text-gray-600">
                                {{ listing.description }}
                            </p>
                            <p class="text-lg font-semibold text-black">
                                {{ listing.price }}
                            </p>
                            <p v-if="listing.location" class="text-gray-600">
                                Location: {{ listing.location }}
                            </p>
                            <p v-if="listing.phone" class="text-gray-600">
                                Phone: {{ listing.phone }}
                            </p>
                            <p v-if="listing.category" class="text-gray-600">
                                Category: {{ listing.category.name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Posted by: {{ listing.user?.name || "Unknown" }}
                            </p>
                            <Link
                                :href="route('publicIndex')"
                                class="mt-4 inline-block bg-red-600 text-white rounded-md py-2 px-4 text-sm hover:bg-red-700 transition"
                            >
                                Back to Listings
                            </Link>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-12 text-center text-sm text-gray-600">
                Developed with ❤️ by me
            </footer>
        </div>
    </div>
</template>
