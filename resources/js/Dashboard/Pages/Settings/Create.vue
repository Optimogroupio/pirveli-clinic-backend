<template>
    <div>
        <breadcrumbs/>
        <h1 class="text-2xl font-bold mb-4">Create Settings</h1>
        <Form
            :fields="[
                { key: 'key', label: 'Key', type: 'text', placeholder: 'Enter key', translatable: false },
                { key: 'value', label: 'Value', type: 'textarea', placeholder: 'Enter value', size: 'full', translatable: true },
                { key: 'banner_image', label: 'Banner image', type: 'file', fileType: 'image', multiple: false, size: 'inline' },
                { key: 'logo', label: 'Logo', type: 'file', fileType: 'image', multiple: false, size: 'inline' },
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
        services: Array,
        doctors: Array
    },
    methods: {
        handleCreate(data) {
            Inertia.post('/dashboard/settings', data);
        }
    }
};
</script>
