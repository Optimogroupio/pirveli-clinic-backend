<template>
    <div>
        <breadcrumbs/>
        <h1 class="text-2xl font-bold mb-4">Create Appointment</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', size: 'inline'},
                { key: 'surname', label: 'Surname', type: 'text', placeholder: 'Enter surname', size: 'inline' },
                { key: 'phone', label: 'Phone', type: 'text', placeholder: 'Enter surname'},
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
