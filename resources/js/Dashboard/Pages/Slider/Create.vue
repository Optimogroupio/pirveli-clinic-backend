<template>
    <div>
        <breadcrumbs/>
        <h1 class="text-2xl font-bold mb-4">Create Slider</h1>
        <Form
            :fields="[
                { key: 'title', label: 'Title', type: 'text', placeholder: 'Enter title', size: 'full', translatable: true },
                { key: 'description', label: 'Description', type: 'rich-editor', placeholder: 'Enter description', size: 'full', translatable: true },
                {
                    key: 'position',
                    label: 'Position',
                    type: 'select',
                    options: [
                        { label: 'Top', value: 'top' },
                        { label: 'Bottom', value: 'bottom' }
                    ],
                    placeholder: 'Select position'
                },
                { key: 'url', label: 'URL', type: 'text', placeholder: 'URL', size: 'left'},
                { key: 'image', label: 'Image', type: 'file', fileType: 'file', multiple: false, size: 'inline' },
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
            Inertia.post('/dashboard/slider', data);
        }
    }
};
</script>
