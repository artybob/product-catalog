<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-8">
                        <Link :href="route('home')" class="text-xl font-bold text-gray-900">
                            Product Catalog
                        </Link>
                        <div class="hidden md:flex space-x-4">
                            <Link :href="route('admin.products')" class="text-gray-700 hover:text-gray-900">
                                Products
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-700">{{ auth.user?.name }}</span>
                        <button @click="logout" class="btn-secondary">Logout</button>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Page Header -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    <slot name="header">Admin Panel</slot>
                </h1>
            </div>
        </div>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { useAuthStore } from '@/Stores/auth';

const authStore = useAuthStore();
const auth = authStore.auth;

const logout = async () => {
    await authStore.logout();
    router.visit(route('home'));
};
</script>

<style scoped>
.btn-secondary {
    @apply bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 transition;
}
</style>
