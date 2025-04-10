<template>
    <div>
        <breadcrumbs/>
        <h1 class="text-2xl font-bold mb-4">Create Appointment</h1>
        <Form
            :fields="[
                { key: 'full_name', label: 'Full Name', type: 'text', placeholder: 'Enter full name', size: 'inline'},
                { key: 'phone', label: 'Contact information', type: 'text', placeholder: 'Enter contact information', size: 'inline'},
                { key: 'comment', label: 'Comment', type: 'textarea', placeholder: 'Enter comment'},
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
