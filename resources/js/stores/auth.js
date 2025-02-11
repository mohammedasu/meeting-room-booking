// store/auth.js
import { defineStore } from 'pinia';
import axios from 'axios';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () =>{
//   state: () => ({
//     user: null,
//     token: localStorage.getItem('token') || null,
//   });

    const user = ref(null);
    const token = ref(localStorage.getItem('token') || null);

    const login = async (credentials) => {
        await axios.post('/api/login', credentials).then((response) =>{
            // if (response.ok) {
            // const data = await data.json();
            // console.log(data);
            
            token.value = response.data.token;
            localStorage.setItem('token', token.value);
            axios.defaults.headers['Authorization'] = `Bearer ${token.value}`;
            user.value = response.data.user;
        }).catch((err) =>{
            throw new Error('Login failed');
        });
        // await fetch('/api/login', {
        // method: 'POST',
        // headers: { 'Content-Type': 'application/json' },
        // body: JSON.stringify(credentials),
        // });
    };

    const register = async (userData) => {
        const response = await axios.post('/api/register', userData);
        // fetch('/api/register', {
        //     method: 'POST',
        //     headers: { 'Content-Type': 'application/json' },
        //     body: JSON.stringify(userData),
        // });

        if (response.ok) {
            const data = await response.json();
            // token.value = data.token;
            // localStorage.setItem('token', token.value);
            // user.value = data.user;
        } else {
            throw new Error('Registration failed');
        }
    };

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
        register,
        logout,
    };
//   actions: {
    // async login(credentials) {
    //   try {
    //     const response = await axios.post('/api/login', credentials);
    //     this.token = response.data.token;
    //     localStorage.setItem('token', this.token);
    //     axios.defaults.headers['Authorization'] = `Bearer ${this.token}`;
    //     this.user = response.data.user;
    //   } catch (error) {
    //     console.error(error);
    //   }
    // },
    // async register(credentials) {
    //   try {
    //     await axios.post('/api/register', credentials);
    //   } catch (error) {
    //     console.error(error);
    //   }
    // },
    // logout() {
    //   this.token = null;
    //   localStorage.removeItem('token');
    //   axios.defaults.headers['Authorization'] = '';
    // },
//   },
});
