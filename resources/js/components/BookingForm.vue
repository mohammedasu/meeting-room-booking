<template>
    <div class="bg-white flex max-w-2xl mx-auto p-6 rounded shadow-md">
        <sidebar></sidebar>
        <p v-if="errors.general" class="text-red-500">{{ errors.general }}</p>
        <form @submit.prevent="submitBooking" class="space-y-4 bg-white p-6 rounded-lg shadow-md">
            <div>
                <label class="block text-sm font-medium text-gray-700">Meeting Name</label>
                <input 
                    type="text" 
                    v-model="meeting_name" 
                    @blur="validateField('meeting_name')"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': errors.meeting_name }"
                />
                <p v-if="errors.meeting_name" class="text-red-500 text-sm mt-1">{{ errors.meeting_name?.message }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Date and Time</label>
                <input 
                    type="datetime-local" 
                    v-model="formattedDateTime" 
                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': errors.meeting_datetime }"
                />
                <p v-if="errors.meeting_datetime" class="text-red-500 text-sm mt-1">{{ errors.meeting_datetime?.message }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Duration</label>
                <select 
                    v-model="duration"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': errors.duration }"
                >
                    <option value="30">30 mins</option>
                    <option value="60">60 mins</option>
                    <option value="90">90 mins</option>
                </select>
                <p v-if="errors.duration" class="text-red-500 text-sm mt-1">{{ errors.duration?.message }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Members</label>
                <input 
                    type="number" 
                    v-model="members"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': errors.members }"
                />
                <p v-if="errors.members" class="text-red-500 text-sm mt-1">{{ errors.members?.message }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Meeting Room</label>
                <select 
                    v-model="meeting_room_id"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': errors.meeting_room_id }"
                >
                    <option v-for="room in availableRooms" :value="room.id" :key="room.id">
                        {{ room.name }} (Capacity: {{ room.capacity }})
                    </option>
                </select>
                <p v-if="errors.meeting_room_id" class="text-red-500 text-sm mt-1">{{ errors.meeting_room_id?.message }}</p>
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 transition"
            >
                Book Meeting Room
            </button>
        </form>
    </div>
</template>


<script setup>
import { computed, ref, watch } from 'vue';
import { useForm } from 'vee-validate';
import { bookingSchema } from '@/utils/validation';
import Sidebar from './Sidebar.vue';

    const { errors, handleSubmit, setErrors, validateField, defineField } = useForm({
        validationSchema: bookingSchema
    });

    const [meeting_name] = defineField('meeting_name');
    const [meeting_datetime] = defineField('meeting_datetime');
    const [duration] = defineField('duration');
    const [members] = defineField('members');
    const [meeting_room_id] = defineField('meeting_room_id');
    const availableRooms = ref([]);

    const formattedDateTime = computed({
        get: () => {
            if (!meeting_datetime.value) return '';
            const date = new Date(meeting_datetime.value);
            return date.toISOString().slice(0, 16); // Converts to "YYYY-MM-DDTHH:mm"
        },
        set: (val) => {
            meeting_datetime.value = val; // Store as string
        }
    });

    watch(
        () => [meeting_datetime.value, duration.value, members.value], 
        async ([meeting_datetime, duration, members]) => {
            if (meeting_datetime && duration && members) {
                try {
                    const response = await axios.get('/api/available-rooms', { 
                        params: { meeting_datetime, duration, members } 
                    });
                    availableRooms.value = response.data;
                } catch (error) {
                    console.error("Error fetching available rooms:", error);
                }
            }
        }
    );

    const submitBooking = handleSubmit(async (values) => {
        try {
            await axios.post('/api/bookings', values);
            alert('Booking Successful');
        } catch (error) {
            alert(error.response.data.error);
            setErrors({ general: error.response.data.error });
        }
    });
</script>