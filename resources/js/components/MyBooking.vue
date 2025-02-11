<template>
    <div class="flex container mx-auto p-4">
        <sidebar></sidebar>
        <!-- Filter Buttons -->
        <div class="flex justify-between mb-6">
            <div class="space-x-4">
                <button 
                    @click="fetchBookings('upcoming')" 
                    :class="{
                        'bg-blue-500 text-white hover:bg-blue-600': filter.value === 'upcoming',
                        'bg-gray-200 text-gray-700 hover:bg-gray-300': filter.value !== 'upcoming'
                    }"
                    class="px-6 py-2 rounded-lg transition-all duration-300">
                    Upcoming Bookings
                </button>
                <button 
                    @click="fetchBookings('past')" 
                    :class="{
                        'bg-blue-500 text-white hover:bg-blue-600': filter.value === 'past',
                        'bg-gray-200 text-gray-700 hover:bg-gray-300': filter.value !== 'past'
                    }"
                    class="px-6 py-2 rounded-lg transition-all duration-300">
                    Past Bookings
                </button>
            </div>
        </div>

        <!-- Bookings List -->
        <div v-if="bookings.data.length > 0">
            <div v-for="booking in bookings.data" :key="booking.id" class="mb-6 p-6 border border-gray-200 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <p><strong class="font-semibold">Meeting Name:</strong> {{ booking.meeting_name }}</p>
                <p><strong class="font-semibold">Date:</strong> {{ formatDate(booking.meeting_datetime) }}</p>
                <p><strong class="font-semibold">Member:</strong> {{ booking.members }}</p>
                <p><strong class="font-semibold">Duration:</strong> {{ booking.duration }}</p>
            </div>

            <!-- Pagination Controls -->
            <div class="flex justify-center mt-6 space-x-4">
                <button 
                    v-if="bookings.prev_page_url" 
                    @click="changePage(bookings.prev_page_url)" 
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">
                    Previous
                </button>
                <button 
                    v-if="bookings.next_page_url" 
                    @click="changePage(bookings.next_page_url)" 
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">
                    Next
                </button>
            </div>
        </div>

        <!-- No Bookings Found Message -->
        <p v-else class="text-center text-gray-600 text-xl">No bookings found.</p>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Sidebar from './Sidebar.vue';
    const bookings = ref({
        data: [],
        next_page_url: null,
        prev_page_url: null,
    });
    const filter = ref('upcoming'); // Default filter
    const currentPage = ref(1);

    // Fetch bookings based on the filter (upcoming or past)
    const fetchBookings = async (newFilter) => {
        
        filter.value = newFilter;
        currentPage.value = 1;

        const response = await axios.get('/api/my-bookings', {
            params: { filter: newFilter, page: currentPage.value }
        });
        bookings.value = response.data;
    };

    // Change page and update the bookings list
    const changePage = async (url) => {
        const response = await axios.get(url);
        bookings.value = response.data;
        currentPage.value = bookings.value.current_page; // Update current page
    };

    const formatDate = (date) => {
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(date).toLocaleDateString('en-US', options);
    };

    onMounted(() => {
        fetchBookings(filter.value);
    });
</script>

<style scoped>
button {
    transition: background-color 0.2s ease-in-out;
}
</style>
