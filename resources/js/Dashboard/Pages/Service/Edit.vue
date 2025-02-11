<template>
    <div v-if="service">
        <breadcrumbs :recordName="service.name"/>
        <h1 class="text-2xl font-bold mb-4">Edit Service</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', translatable: true},
                { key: 'description', label: 'Description', type: 'rich-editor', placeholder: 'Enter description', translatable: true, size: 'full'},
                { key: 'description', label: 'Description', type: 'textarea', placeholder: 'Enter short description', size: 'full', translatable: true },
                { key: 'svg', label: 'SVG', type: 'textarea', placeholder: 'Enter svg', size: 'full', translatable: false },
                { key: 'service_category_id', label: 'Service category', type: 'select', options: service_categories, labelKey: 'name', valueKey: 'id', placeholder: 'Select service category' },
                { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true},
                { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true},
            ]"
            :initialData="service"
            submitLabel="Update"
            @submit="handleUpdate"
        />
    </div>
    <div v-else>
        <p>Loading...</p>
    </div>
</template>

<script>
import Form from '@/Dashboard/Components/Form.vue';
import Breadcrumbs from '@/Dashboard/Components/Breadcrumbs.vue';
import { router as Inertia, usePage } from '@inertiajs/vue3';

export default {
    components: { Form, Breadcrumbs },
    props: {
        service_categories: Array
    },
    setup() {
        const { props } = usePage();
        const service = props.service;

        const handleUpdate = (data) => {
            Inertia.patch(`/dashboard/services/${service.id}`, data);
        };

        return {
            service,
            handleUpdate,
        };
    }
};
</script>
