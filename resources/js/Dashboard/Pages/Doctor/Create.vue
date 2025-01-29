<template>
    <div>
        <breadcrumbs/>
        <h1 class="text-2xl font-bold mb-4">Create Doctor</h1>
        <Form
            :fields="[
                { key: 'full_name', label: 'Full Name', type: 'text', placeholder: 'Enter full name', size: 'inline', translatable: true },
                { key: 'specialties', label: 'Specialty', type: 'multi-select', options: specialties, labelKey: 'name', valueKey: 'id', placeholder: 'Select specialty', size: 'inline'},
                { key: 'service_id', label: 'Service', type: 'select', options: services, labelKey: 'name', valueKey: 'id', placeholder: 'Select service', size: 'inline' },
                { key: 'languages', label: 'Languages', type: 'multi-select', options: languages, labelKey: 'name', valueKey: 'id', placeholder: 'Select languages', size: 'inline' },
                { key: 'image', label: 'Image', type: 'file', fileType: 'file', multiple: false, size: 'half' },
                { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true, size: 'half' },
                { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true, size: 'half' },
            ]"
            :initialData="{ full_name: '', specialties: [], meta_title: '', meta_description: '', languages: [] }"
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
        specialties: Array,
        languages: Array,
    },
    methods: {
        handleCreate(data) {
            Inertia.post('/dashboard/doctors', data);
        }
    }
};
</script>
