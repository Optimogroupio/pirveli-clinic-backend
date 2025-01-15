<template>
    <div>
        <breadcrumbs/>
        <h1 class="text-2xl font-bold mb-4">Create Appointment</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', size: 'inline'},
                { key: 'surname', label: 'Surname', type: 'text', placeholder: 'Enter surname', size: 'inline' },
                { key: 'specialty_id', label: 'Specialty', type: 'select', options: specialties, labelKey: 'name', valueKey: 'id', placeholder: 'Select specialty', size: 'inline' },
                { key: 'doctor_id', label: 'Doctor', type: 'select', options: doctors, labelKey: 'full_name', valueKey: 'id', placeholder: 'Select doctor', size: 'inline' },
                { key: 'phone', label: 'Phone', type: 'text', placeholder: 'Enter surname'},
            ]"
            :initialData="{}"
            :uploadUrl="null"
            submitLabel="Create"
            @submit="handleCreate"
        />
    </div>
</template>

<script>
import Form from '@/Dashboard/Components/Form.vue';
import Breadcrumbs from '@/Dashboard/Components/Breadcrumbs.vue';
import {router as Inertia} from '@inertiajs/vue3';

export default {
    components: {Form, Breadcrumbs},
    props: {
        specialties: Array,
        doctors: Array
    },
    methods: {
        handleCreate(data) {
            Inertia.post('/dashboard/appointments', data);
        }
    }
};
</script>
