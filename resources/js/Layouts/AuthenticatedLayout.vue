<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { usePage, Link } from "@inertiajs/vue3";
import { ref } from "vue";

const { props } = usePage();
const { errors, auth } = props;

const showingNavigationDropdown = ref(false);
const showingUserDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="border-b border-gray-100 bg-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <Link :href="route('index')">
                            <ApplicationLogo
                                class="h-16 w-16 fill-current text-gray-800"
                            />
                        </Link>
                        <div class="flex">
                            <!-- Navigation Links -->
                            <div
                                v-if="auth.role === 'admin'"
                                class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                            >
                                <a
                                    :href="route('dashboard')"
                                    :class="[
                                        route().current('dashboard')
                                            ? 'border-indigo-500 text-gray-900'
                                            : 'text-gray-600 hover:text-gray-900 hover:border-indigo-500',
                                        'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium',
                                    ]"
                                >
                                    Dashboard
                                </a>
                                <a
                                    :href="route('admin.customers')"
                                    :class="[
                                        route().current('admin.customers')
                                            ? 'border-indigo-500 text-gray-900'
                                            : 'text-gray-600 hover:text-gray-900 hover:border-indigo-500',
                                        'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium',
                                    ]"
                                >
                                    Customers
                                </a>
                                <a
                                    :href="route('admin.categories.create')"
                                    :class="[
                                        route().current(
                                            'admin.categories.create'
                                        )
                                            ? 'border-indigo-500 text-gray-900'
                                            : 'text-gray-600 hover:text-gray-900 hover:border-indigo-500',
                                        'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium',
                                    ]"
                                >
                                    Categories
                                </a>
                                <a
                                    :href="route('admin.listings')"
                                    :class="[
                                        route().current('admin.listings')
                                            ? 'border-indigo-500 text-gray-900'
                                            : 'text-gray-600 hover:text-gray-900 hover:border-indigo-500',
                                        'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium',
                                    ]"
                                >
                                    Listings
                                </a>
                            </div>
                            <div
                                v-if="auth.role === 'customer'"
                                class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                            >
                                <a
                                    :href="route('dashboard')"
                                    :class="[
                                        route().current('dashboard')
                                            ? 'border-indigo-500 text-gray-900'
                                            : 'text-gray-600 hover:text-gray-900 hover:border-indigo-500',
                                        'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium',
                                    ]"
                                >
                                    Dashboard
                                </a>
                                <a
                                    :href="route('customer.listings')"
                                    :class="[
                                        route().current('customer.listings')
                                            ? 'border-indigo-500 text-gray-900'
                                            : 'text-gray-600 hover:text-gray-900 hover:border-indigo-500',
                                        'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium',
                                    ]"
                                >
                                    Listings
                                </a>
                            </div>
                        </div>

                        <div class="hidden sm:ml-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ml-3">
                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none"
                                    @click="
                                        showingUserDropdown =
                                            !showingUserDropdown
                                    "
                                >
                                    {{ auth.name }}
                                    <svg
                                        class="-mr-0.5 ml-2 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                                <div
                                    v-if="showingUserDropdown"
                                    class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                >
                                    <a
                                        :href="route('profile.edit')"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >
                                        Profile
                                    </a>
                                    <form
                                        @submit.prevent="
                                            $inertia.post(route('logout'))
                                        "
                                    >
                                        <button
                                            type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <a
                            :href="route('dashboard')"
                            :class="[
                                route().current('dashboard')
                                    ? 'border-l-4 border-indigo-500 bg-indigo-50 text-indigo-700'
                                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                                'block pl-3 pr-4 py-2 text-base font-medium',
                            ]"
                        >
                            Dashboard
                        </a>
                        <a
                            v-if="auth.role === 'admin'"
                            :href="route('admin.customers')"
                            :class="[
                                route().current('admin.customers')
                                    ? 'border-l-4 border-indigo-500 bg-indigo-50 text-indigo-700'
                                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                                'block pl-3 pr-4 py-2 text-base font-medium',
                            ]"
                        >
                            Customers
                        </a>
                        <a
                            v-if="auth.role === 'admin'"
                            :href="route('admin.categories.create')"
                            :class="[
                                route().current('admin.categories.create')
                                    ? 'border-l-4 border-indigo-500 bg-indigo-50 text-indigo-700'
                                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                                'block pl-3 pr-4 py-2 text-base font-medium',
                            ]"
                        >
                            Categories
                        </a>
                        <a
                            v-if="auth.role === 'admin'"
                            :href="route('admin.listings')"
                            :class="[
                                route().current('admin.listings')
                                    ? 'border-l-4 border-indigo-500 bg-indigo-50 text-indigo-700'
                                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                                'block pl-3 pr-4 py-2 text-base font-medium',
                            ]"
                        >
                            Listings
                        </a>
                        <a
                            v-if="auth.role === 'customer'"
                            :href="route('customer.listings')"
                            :class="[
                                route().current('customer.listings')
                                    ? 'border-l-4 border-indigo-500 bg-indigo-50 text-indigo-700'
                                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                                'block pl-3 pr-4 py-2 text-base font-medium',
                            ]"
                        >
                            Listings
                        </a>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">
                                {{ auth.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ auth.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <a
                                :href="route('profile.edit')"
                                class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                            >
                                Profile
                            </a>
                            <form
                                @submit.prevent="$inertia.post(route('logout'))"
                            >
                                <button
                                    type="submit"
                                    class="block w-full text-left pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                                >
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
