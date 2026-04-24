<template>
    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Manage Products</h1>
                <Link :href="route('admin.products.create')" class="btn-primary">
                    + Add New Product
                </Link>
            </div>
        </template>

        <!-- Filters -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow">
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="label">Filter by Category</label>
                    <select v-model="filters.category_id" @change="fetchProducts" class="input">
                        <option value="">All Categories</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="label">Search</label>
                    <input
                        type="text"
                        v-model="filters.search"
                        @input="debouncedSearch"
                        placeholder="Search products..."
                        class="input"
                    >
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div v-if="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>

            <div v-else-if="products.length === 0" class="text-center py-12">
                <p class="text-gray-500">No products found.</p>
            </div>

            <table v-else class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="product in products" :key="product.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ product.id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ product.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.category?.name || 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ product.formatted_price }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <Link :href="route('admin.products.edit', product.id)" class="text-blue-600 hover:text-blue-900">
                            Edit
                        </Link>
                        <button @click="confirmDelete(product)" class="text-red-600 hover:text-red-900">
                            Delete
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="pagination.last_page > 1" class="px-6 py-4 border-t">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-700">
                        Showing {{ pagination.from || 0 }} to {{ pagination.to || 0 }} of {{ pagination.total || 0 }} results
                    </div>
                    <div class="flex space-x-2">
                        <button
                            v-for="(link, index) in pagination.links"
                            :key="index"
                            @click="goToPage(link.url)"
                            v-html="link.label"
                            :disabled="!link.url || link.active"
                            class="px-3 py-1 rounded text-sm"
                            :class="{
                        'bg-blue-600 text-white': link.active,
                        'bg-gray-100 text-gray-700 hover:bg-gray-200': !link.active && link.url,
                        'bg-gray-100 text-gray-400 cursor-not-allowed': !link.url
                    }"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900">Confirm Delete</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to delete "{{ productToDelete?.name }}"?
                        </p>
                    </div>
                    <div class="flex justify-center gap-4 mt-4">
                        <button @click="showModal = false" class="btn-secondary">Cancel</button>
                        <button @click="deleteProduct" class="btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import axios from 'axios';

const products = ref([]);
const categories = ref([]);
const loading = ref(false);
const showModal = ref(false);
const productToDelete = ref(null);
const pagination = ref({
    from: 0,
    to: 0,
    total: 0,
    links: []
});
const filters = ref({ category_id: '', search: '' });

let searchTimeout = null;

const fetchProducts = async (page = 1) => {
    loading.value = true;
    try {
        const params = { page };
        if (filters.value.category_id) params.category_id = filters.value.category_id;
        if (filters.value.search) params.search = filters.value.search;

        const response = await axios.get('/api/products', { params });

        products.value = response.data.data || [];
        pagination.value = {
            current_page: response.data.meta?.current_page || 1,
            from: response.data.meta?.from || 0,
            to: response.data.meta?.to || 0,
            total: response.data.meta?.total || 0,
            last_page: response.data.meta?.last_page || 1,
            links: response.data.meta?.links || []
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
        categories.value = response.data.data || [];
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

const confirmDelete = (product) => {
    productToDelete.value = product;
    showModal.value = true;
};

const deleteProduct = async () => {
    try {
        await axios.delete(`/api/products/${productToDelete.value.id}`);
        showModal.value = false;
        await fetchProducts();
    } catch (error) {
        console.error('Error deleting product:', error);
        alert('Failed to delete product');
    }
};

const goToPage = (url) => {
    if (url) {
        const urlParams = new URL(url);
        const page = urlParams.searchParams.get('page');
        fetchProducts(page);
    }
};

const debouncedSearch = () => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchProducts();
    }, 500);
};

onMounted(() => {
    fetchProducts();
    fetchCategories();
});

</script>
