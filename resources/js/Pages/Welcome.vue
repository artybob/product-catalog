<template>
    <MainLayout>
        <!-- Filters -->
        <div class="mb-8 bg-white p-4 rounded-lg shadow-sm">
            <div class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Filter by category:</label>
                <select
                    v-model="selectedCategory"
                    @change="filterProducts"
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2"
                >
                    <option value="">All Categories</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Products Grid -->
        <div v-if="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>

        <div v-else-if="products.length === 0" class="text-center py-12">
            <p class="text-gray-500">No products found.</p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="product in products"
                :key="product.id"
                class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden"
            >
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        {{ product.name }}
                    </h3>
                    <div class="mb-2">
                        <span class="text-sm text-blue-600 bg-blue-50 px-2 py-1 rounded">
                            {{ product.category?.name || 'Uncategorized' }}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4 line-clamp-2">
                        {{ product.description }}
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-gray-900">
                            ${{ parseFloat(product.price).toFixed(2) }}
                        </span>
                        <Link
                            :href="route('product.show', product.id)"
                            class="text-blue-600 hover:text-blue-800 font-medium"
                        >
                            View Details →
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center" v-if="pagination.last_page > 1">
            <div class="flex space-x-2">
                <button
                    v-for="page in pagination.links"
                    :key="page.label"
                    @click="goToPage(page.url)"
                    v-html="page.label"
                    :disabled="!page.url || page.active"
                    class="px-3 py-2 rounded-md text-sm font-medium"
                    :class="{
                        'bg-blue-600 text-white': page.active,
                        'bg-white text-gray-700 hover:bg-gray-50': !page.active && page.url,
                        'bg-gray-100 text-gray-400 cursor-not-allowed': !page.url
                    }"
                />
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'; // Добавляем Link
import { ref, onMounted, watch } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import axios from 'axios';

const products = ref([]);
const categories = ref([]);
const selectedCategory = ref('');
const loading = ref(false);
const pagination = ref({
    current_page: 1,
    last_page: 1,
    links: []
});

const fetchProducts = async () => {
    loading.value = true;
    try {
        const params = selectedCategory.value ? { category_id: selectedCategory.value } : {};
        const response = await axios.get('/api/products', { params });
        products.value = response.data.data;
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            links: response.data.links
        };
    } catch (error) {
        console.error('Error fetching products:', error);
    } finally {
        loading.value = false;
    }
};

const fetchCategories = async () => {
    try {
        const response = await axios.get('/api/categories');
        categories.value = response.data.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

const filterProducts = () => {
    fetchProducts();
};

const goToPage = (url) => {
    if (url) {
        window.location.href = url;
    }
};

onMounted(() => {
    fetchProducts();
    fetchCategories();
});
</script>
