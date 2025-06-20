<template>
    <div>
        <breadcrumbs/>
        <h1 class="text-2xl font-bold mb-4">Create Category</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', translatable: true },
                { key: 'description', label: 'Description', type: 'rich-editor', placeholder: 'Enter description', size: 'full', translatable: true },
                { key: 'video_iframe', label: 'Video Iframe', type: 'textarea', placeholder: 'Enter video iframe', size: 'full', translatable: false },
                { key: 'services', label: 'Services', type: 'multi-select', options: services, labelKey: 'name', valueKey: 'id', placeholder: 'Select services', size: 'full' },
                { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true, size: 'half' },
                { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true, size: 'half' },
            ]"
            :initialData="{ services: [] }"
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
        services: Array,
    },
    methods: {
        handleCreate(data) {
            Inertia.post('/dashboard/categories', data);
        }
    }
};
</script>
