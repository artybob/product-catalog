import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        auth: {
            user: null,
            token: localStorage.getItem('auth_token') || null
        }
    }),

    getters: {
        isAuthenticated: (state) => !!state.auth.token,
        getUser: (state) => state.auth.user
    },

    actions: {
        async login(credentials) {
            try {
                const response = await axios.post('/api/login', credentials);
                const { access_token, user } = response.data;

                this.auth.token = access_token;
                this.auth.user = user;

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
            if (this.auth.token) {
                try {
                    await axios.post('/api/logout', {}, {
                        headers: {
                            'Authorization': `Bearer ${this.auth.token}`
                        }
                    });
                } catch (error) {
                    console.error('Logout error:', error);
                }
            }

            this.auth.token = null;
            this.auth.user = null;
            localStorage.removeItem('auth_token');
            delete axios.defaults.headers.common['Authorization'];
        },

        loadToken() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                this.auth.token = token;
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                this.fetchUser();
            }
        },

        async fetchUser() {
            try {
                const response = await axios.get('/api/user', {
                    headers: {
                        'Authorization': `Bearer ${this.auth.token}`
                    }
                });
                this.auth.user = response.data;
            } catch (error) {
                this.logout();
            }
        }
    }
});
