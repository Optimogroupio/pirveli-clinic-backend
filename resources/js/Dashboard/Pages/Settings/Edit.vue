<template>
    <div v-if="settings">
        <breadcrumbs :recordName="settings.key"/>
        <h1 class="text-2xl font-bold mb-4">Edit Settings</h1>
        <Form
            :fields="[
                    { key: 'key', label: 'Key', type: 'text', placeholder: 'Enter key', translatable: false },
                    { key: 'value', label: 'Value', type: 'textarea', placeholder: 'Enter value', size: 'full', translatable: true },
                { key: 'banner_image', label: 'Banner image', type: 'file', fileType: 'image', multiple: false, size: 'inline' },
                { key: 'logo', label: 'Logo', type: 'file', fileType: 'image', multiple: false, size: 'inline' },
                ]"
            :initialData="settings"
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
import {router as Inertia, usePage} from '@inertiajs/vue3';

export default {
    components: {Form, Breadcrumbs},
    props: {
        settings: Object
    },
    setup() {
        const { props } = usePage();
        const settings = props.settings;

        const handleUpdate = (data) => {
            data._method = "put";
            Inertia.post(`/dashboard/settings/${settings.id}`, data);
        };

        return {
            settings,
            handleUpdate,
        };
    }
};
</script>
