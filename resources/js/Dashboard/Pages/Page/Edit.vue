<template>
    <div v-if="page">
        <breadcrumbs :recordName="page.name"/>
        <h1 class="text-2xl font-bold mb-4">Edit Page</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', translatable: true},
                { key: 'description', label: 'Description', type: 'rich-editor', placeholder: 'Enter description', translatable: true, size: 'full'},
                { key: 'image', label: 'Image', type: 'file', fileType: 'file', multiple: false, size: 'half' },
                { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true},
                { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true},
            ]"
            :initialData="page"
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
    setup() {
        const { props } = usePage();
        const page = props.page;

        const handleUpdate = (data) => {
            data._method = "put";
            Inertia.post(`/dashboard/pages/${page.id}`, data);
        };

        return {
            page,
            handleUpdate,
        };
    }
};
</script>
