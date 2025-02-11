import './bootstrap';
import '../css/app.css';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';

const pinia = createPinia();

const app = createApp(App)
.use(router)
.use(pinia);
app.mount('#app');
