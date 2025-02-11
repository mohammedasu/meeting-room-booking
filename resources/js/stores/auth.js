// store/auth.js
import { defineStore } from 'pinia';
import axios from 'axios';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () =>{

    const user = ref(null);
    const token = ref(localStorage.getItem('token') || null);

    const login = async (credentials) => {
        await axios.post('/api/login', credentials).then((response) =>{
            
            token.value = response.data.token;
            localStorage.setItem('token', token.value);
            axios.defaults.headers['Authorization'] = `Bearer ${token.value}`;
            user.value = response.data.user;
        }).catch((err) =>{
            throw new Error('Login failed');
        });
    };

    // const register = async (userData) => {
    //     await axios.post('/api/register', userData).then((response) => {
    //         return response;
    //     }).catch(() => {
    //         throw new Error('Registration failed');
    //     });
    // };

    const logout = () => {
        token.value = null;
        user.value = null;
        localStorage.removeItem('token');
        axios.defaults.headers['Authorization'] = '';
    };

    return {
        user,
        token,
        login,
        logout,
    };
});
