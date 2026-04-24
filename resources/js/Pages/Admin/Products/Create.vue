<template>
    <AdminLayout>
        <template #header>
            Create New Product
        </template>

        <div class="bg-white rounded-lg shadow p-6">
            <form @submit.prevent="submitForm">
                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="label">Product Name *</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        class="input"
                        :class="{ 'border-red-500': errors.name }"
                    >
                    <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <label for="category_id" class="label">Category *</label>
                    <select
                        id="category_id"
                        v-model="form.category_id"
                        required
                        class="input"
                        :class="{ 'border-red-500': errors.category_id }"
                    >
                        <option value="">Select a category</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <p v-if="errors.category_id" class="text-red-500 text-sm mt-1">{{ errors.category_id }}</p>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <label for="price" class="label">Price *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input
                            id="price"
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            min="0.01"
                            required
                            class="input pl-8"
                            :class="{ 'border-red-500': errors.price }"
                        >
                    </div>
                    <p v-if="errors.price" class="text-red-500 text-sm mt-1">{{ errors.price }}</p>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="label">Description *</label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="5"
                        required
                        class="input"
                        :class="{ 'border-red-500': errors.description }"
                    ></textarea>
                    <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <Link :href="route('admin.products')" class="btn-secondary">
                        Cancel
                    </Link>
                    <button type="submit" :disabled="loading" class="btn-primary">
                        {{ loading ? 'Creating...' : 'Create Product' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import axios from 'axios';
import { useAuthStore } from '@/Stores/auth';

const authStore = useAuthStore();
const loading = ref(false);
const errors = ref({});
const categories = ref([]);

const form = ref({
    name: '',
    category_id: '',
    price: '',
    description: ''
});

const fetchCategories = async () => {
    try {
        const response = await axios.get('/api/categories');
        categories.value = response.data.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

const submitForm = async () => {
    loading.value = true;
    errors.value = {};

    try {
        await axios.post('/api/products', form.value, {
            headers: {
                'Authorization': `Bearer ${authStore.auth.token}`,
                'Content-Type': 'application/json'
            }
        });

        router.visit(route('admin.products'));
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            alert('Failed to create product. Please try again.');
        }
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchCategories();
});
</script>
