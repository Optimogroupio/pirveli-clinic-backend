<template>
    <div>
        <breadcrumbs/>
        <h1 class="text-2xl font-bold mb-4">Create News</h1>
        <Form
            :fields="[
                { key: 'title', label: 'Title', type: 'text', placeholder: 'Enter title', translatable: true },
                { key: 'description', label: 'Description', type: 'rich-editor', placeholder: 'Enter description', size: 'full', translatable: true },
                { key: 'service_id', label: 'Service', type: 'select', options: services, labelKey: 'name', valueKey: 'id', placeholder: 'Select service', size: 'inline' },
                { key: 'doctors', label: 'Doctors', type: 'multi-select', options: doctors, labelKey: 'full_name', valueKey: 'id', placeholder: 'Select doctors', size: 'inline' },
                { key: 'image', label: 'Image', type: 'file', fileType: 'file', multiple: false, size: 'inline' },
                { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true, size: 'half' },
                { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true, size: 'half' },
            ]"
            :initialData="{ doctors: [] }"
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
        doctors: Array
    },
    methods: {
        handleCreate(data) {
            Inertia.post('/dashboard/news', data);
        }
    }
};
</script>
