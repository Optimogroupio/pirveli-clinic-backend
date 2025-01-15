<template>
    <div>
        <breadcrumbs />
        <h1 class="text-2xl font-bold mb-4">Create Locale</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', size: 'inline' },
                { key: 'code', label: 'Code', type: 'text', placeholder: 'Enter code', size: 'inline' },
                { key: 'is_default', label: 'Is default?', type: 'toggle', fullWidth: true },
            ]"
            :initialData="{ name: '', code: '', is_default: 0 }"
            submitLabel="Create"
            @submit="handleCreate"
        />
    </div>
</template>

<script>
import Form from '@/Dashboard/Components/Form.vue';
import Breadcrumbs from '@/Dashboard/Components/Breadcrumbs.vue';
import { router as Inertia } from '@inertiajs/vue3';

export default {
    components: { Form, Breadcrumbs },
    methods: {
        handleCreate(data) {
            Inertia.post('/dashboard/locales', data);
        }
    }
};
</script>
