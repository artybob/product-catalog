<template>
    <MainLayout>
        <div v-if="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>

        <div v-else-if="!product" class="text-center py-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Product not found</h2>
            <Link :href="route('home')" class="text-blue-600 hover:text-blue-800">
                ← Back to home
            </Link>
        </div>

        <div v-else class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-8">
                <Link :href="route('home')" class="text-blue-600 hover:text-blue-800 mb-6 inline-block">
                    ← Back to products
                </Link>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-gray-100 rounded-lg h-96 flex items-center justify-center">
                        <div class="text-center">
                            <i class="pi pi-image text-6xl text-gray-400"></i>
                            <p class="text-gray-500 mt-2">Product Image</p>
                        </div>
                    </div>

                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>

                        <div class="mb-4">
                            <span class="text-sm text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                {{ product.category.name }}
                            </span>
                        </div>

                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900">
                                {{ product.formatted_price }}
                            </span>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-600 leading-relaxed">{{ product.description }}</p>
                        </div>

                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Product Details</h3>
                            <dl class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm text-gray-500">Product ID</dt>
                                    <dd class="text-gray-900">{{ product.id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Category ID</dt>
                                    <dd class="text-gray-900">{{ product.category_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Added on</dt>
                                    <dd class="text-gray-900">{{ formatDate(product.created_at) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Last updated</dt>
                                    <dd class="text-gray-900">{{ formatDate(product.updated_at) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import axios from 'axios';

const props = defineProps({
    id: Number
});

const product = ref(null);
const loading = ref(false);

const fetchProduct = async () => {
    loading.value = true;
    try {
        const response = await axios.get(`/api/products/${props.id}`);
        product.value = response.data.data;
    } catch (error) {
        console.error('Error fetching product:', error);
    } finally {
        loading.value = false;
    }
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

onMounted(() => {
    fetchProduct();
});
</script>
