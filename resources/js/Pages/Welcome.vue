<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { usePage, Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
    listings: {
        type: Object,
    },
    categories: {
        type: Array,
    },
});

const { props } = usePage();
const { errors, auth, listings } = props;

function handleImageError() {
    document.getElementById("image-container")?.classList.add("!hidden");
    document.getElementById("listing-card")?.classList.add("!row-span-1");
    document.getElementById("listing-card-content")?.classList.add("!flex-row");
}

const isSidebarOpen = ref(false);
function toggleSidebar() {
    isSidebarOpen.value = !isSidebarOpen.value;
}
</script>

<template>
    <Head title="Welcome to" />

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
                    <div
                        class="flex justify-center sm:col-start-1 sm:justify-start lg:col-start-2 lg:justify-center"
                    >
                        <ApplicationLogo
                            class="h-16 w-16 fill-current text-gray-800"
                        />
                    </div>
                    <nav class="flex justify-center sm:justify-end">
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
                    class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <!-- Example Card -->
                    <a
                        v-for="(listing, index) in listings.data"
                        :key="index"
                        href="/login"
                        id="listing-card"
                        class="flex flex-col items-start gap-4 overflow-hidden rounded-lg bg-white p-4 shadow-sm ring-1 ring-gray-200 transition duration-300 hover:text-black/70 hover:ring-gray-300 focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        <div
                            id="image-container"
                            class="relative w-full aspect-video"
                        >
                            <img
                                :src="
                                    listing.image_path
                                        ? `/storage/${listing.image_path}`
                                        : '/images/noimage.jpg'
                                "
                                alt="Listing Image"
                                class="w-full h-full rounded-md object-cover object-top"
                                @error="handleImageError"
                            />
                        </div>

                        <div
                            id="listing-card-content"
                            class="flex flex-col w-full"
                        >
                            <h2 class="text-lg font-semibold text-black">
                                {{ listing.title }}
                            </h2>
                            <p class="mt-2 text-sm text-gray-600">
                                {{ listing.description }}
                            </p>
                            <p class="mt-3 text-base font-semibold text-black">
                                {{ listing.price }}
                            </p>
                        </div>
                    </a>
                </div>
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
                                'bg-red-600 text-white': link.active,
                                'text-gray-700 hover:bg-gray-200': !link.active,
                            }"
                        />
                        <span
                            v-else
                            v-html="link.label"
                            class="px-4 py-2 rounded-md border text-sm text-gray-400"
                        />
                    </template>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-12 text-center text-sm text-gray-600">
                Developed with ❤️ by me
            </footer>
        </div>
    </div>
</template>
