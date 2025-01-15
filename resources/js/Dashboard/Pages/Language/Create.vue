<template>
    <div>
        <breadcrumbs/>
        <h1 class="text-2xl font-bold mb-4">Create Language</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', translatable: true },
            ]"
            :initialData="{ name: '' }"
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
    methods: {
        handleCreate(data) {
            Inertia.post('/dashboard/languages', data);
        }
    }
};
</script>
