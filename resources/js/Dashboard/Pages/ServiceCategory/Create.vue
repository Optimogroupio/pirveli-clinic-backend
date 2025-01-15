<template>
    <div>
        <breadcrumbs />
        <h1 class="text-2xl font-bold mb-4">Create Service Category</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', translatable: true, size: 'half' },
                { key: 'description', label: 'Description', type: 'rich-editor', placeholder: 'Enter description', translatable: true, size: 'full'},
                { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true},
                { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true},
            ]"
            :initialData="{ name: '', description: '' }"
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
            Inertia.post('/dashboard/service_categories', data);
        }
    }
};
</script>
