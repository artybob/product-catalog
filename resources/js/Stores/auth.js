import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('auth_token') || null,
        user: null
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        auth: (state) => ({ user: state.user, token: state.token })
    },

    actions: {
        async login(credentials) {
            try {
                const response = await axios.post('/api/login', credentials);
                const { access_token, user } = response.data;

                this.token = access_token;
                this.user = user;

                localStorage.setItem('auth_token', access_token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;

                return { success: true };
            } catch (error) {
                return {
                    success: false,
                    error: error.response?.data?.message || 'Login failed'
                };
            }
        },

        async logout() {
            if (this.token) {
                try {
                    await axios.post('/api/logout', {}, {
                        headers: {
                            'Authorization': `Bearer ${this.token}`
                        }
                    });
                } catch (error) {
                    console.error('Logout error:', error);
                }
            }

            this.token = null;
            this.user = null;
            localStorage.removeItem('auth_token');
            delete axios.defaults.headers.common['Authorization'];

            window.location.href = '/';
        },

        async fetchUser() {
            if (!this.token) return;

            try {
                const response = await axios.get('/api/user', {
                    headers: {
                        'Authorization': `Bearer ${this.token}`
                    }
                });
                this.user = response.data;
            } catch (error) {
                this.logout();
            }
        },

        init() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                this.token = token;
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                this.fetchUser();
            }
        }
    }
});
