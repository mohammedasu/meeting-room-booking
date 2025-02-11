<template>
    <div class="flex container mt-5">
        <sidebar></sidebar>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <div v-for="plan in subscriptions" :key="plan.id" class="bg-white p-4 rounded-lg shadow-lg">
                <h5 class="text-lg font-medium">{{ plan.name }}</h5>
                <p class="text-gray-600">Booking Limit: {{ plan.booking_limit }} per day</p>
                <template v-if="subscription && subscription.subscription.id === plan.id">
                    <button class="bg-blue-500 text-white py-2 px-4 rounded mt-3 w-full">Active</button>
                </template>
                <template v-else>
                    <button class="bg-blue-500 text-white py-2 px-4 rounded mt-3 w-full" @click="subscribe(plan.id)">Subscribe</button>
                </template>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import Sidebar from './Sidebar.vue';

const subscriptions = ref([]);
const subscription = ref(null);

const fetchSubscriptions = async () => {
    try {
        const response = await axios.get('/api/subscriptions');
        subscriptions.value = response.data;
    } catch (error) {
        console.error('Error fetching subscriptions', error);
    }
};

const subscribe = async (id) => {
    try {
        await axios.post('/api/subscribe', { subscription_id: id });
        alert('Subscription updated successfully!');
        subscription.value.subscription.id = id;
    } catch (error) {
        console.error('Error subscribing', error);
    }
};

onMounted(async () => {
    fetchSubscriptions();
    try {
        const response = await axios.get('/api/user-subscription');
        subscription.value = response.data;
    } catch (error) {
        console.error('Error fetching subscription:', error);
    } finally {
    }
});
</script>