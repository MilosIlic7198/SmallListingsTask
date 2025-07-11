<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

function handleImageError() {
    document.getElementById('image-container')?.classList.add('!hidden');
    document.getElementById('listing-card')?.classList.add('!row-span-1');
    document.getElementById('listing-card-content')?.classList.add('!flex-row');
}
</script>

<template>
    <Head title="Welcome to" />
    <div class="bg-gray-100 text-black">
        <div
        class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
        <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
            <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                <div class="flex lg:col-start-2 lg:justify-center">
                    <ApplicationLogo class="h-20 w-20 fill-current text-gray-800" />
                </div>
                <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </header>

            <main class="mt-6">
                <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                    <a
                        href="http://localhost:8000/login"
                        id="listing-card"
                        class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[4px_4px_10px_rgba(0,0,0,0.08)] ring-1 ring-black/10 transition duration-300 hover:text-black/70 hover:ring-black/30 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10"
                    >
                        <div
                            id="image-container"
                            class="relative flex w-full flex-1 items-stretch"
                        >
                            <img
                                src="/images/bicikla.jpg"
                                alt="..."
                                class="aspect-video h-full w-full flex-1 rounded-[10px] object-cover object-top drop-shadow-[0px_4px_34px_rgba(0,0,0,0.06)]"
                                @error="handleImageError"
                            />
                        </div>

                        <div class="relative flex items-center gap-6 lg:items-end">
                            <div
                                id="listing-card-content"
                                class="flex items-start gap-6 lg:flex-col"
                            >
                                <div class="pt-3 sm:pt-5 lg:pt-0">
                                    <h2 class="text-xl font-semibold text-black">
                                        MTB 29
                                    </h2>

                                    <p class="mt-4 text-sm/relaxed text-black/70">
                                        Dobro očuvan bicikl, korišćen svega nekoliko puta. Idealno za gradske vožnje...
                                    </p>
                                    
                                    <p class="mt-4 text-lg font-semibold text-black">
                                        12.000 RSD
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </main>

            <footer class="py-16 text-center text-sm text-black">
                Developed with ❤️ by me
            </footer>
        </div>
    </div>
</div>

</template>
