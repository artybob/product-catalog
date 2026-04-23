<template>
    <div class="min-h-screen bg-gray-50">
        <header class="bg-white shadow-sm">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-8">
                        <Link :href="route('home')" class="text-xl font-bold text-gray-900">
                            Product Catalog
                        </Link>
                        <div class="hidden md:flex space-x-4">
                            <Link :href="route('home')" class="text-gray-700 hover:text-gray-900">
                                Home
                            </Link>
                        </div>
                    </div>
                    <div>
                        <Link v-if="!auth.user" :href="route('login')" class="btn-secondary">
                            Admin Login
                        </Link>
                        <div v-else class="flex items-center space-x-4">
                            <span class="text-sm text-gray-700">{{ auth.user.name }}</span>
                            <Link :href="route('admin.products')" class="btn-primary">
                                Admin Panel
                            </Link>
                            <button @click="logout" class="btn-secondary">Logout</button>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>

        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-500 text-sm">
                    © 2024 Product Catalog. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { useAuthStore } from '@/Stores/auth';

const authStore = useAuthStore();
const auth = authStore.auth;

const logout = async () => {
    await authStore.logout();
    window.location.href = route('home');
};
</script>
