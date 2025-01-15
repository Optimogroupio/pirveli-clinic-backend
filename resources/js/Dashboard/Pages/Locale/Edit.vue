<template>
    <div>
        <breadcrumbs :recordName="locale.name"/>
        <h1 class="text-2xl font-bold mb-4">Edit Locale</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', size: 'inline' },
                { key: 'code', label: 'Code', type: 'text', placeholder: 'Enter code', size: 'inline' },
                { key: 'is_default', label: 'Is default?', type: 'toggle', size: 'full' },
            ]"
            :initialData="{
                name: locale.name,
                code: locale.code,
                is_default: locale.is_default
            }"
            submitLabel="Update"
            @submit="handleUpdate"
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
        locale: Object
    },
    methods: {
        handleUpdate(data) {
            const filteredData = Object.fromEntries(
                Object.entries(data).filter(([_, value]) => value !== null && value !== '')
            );

            Inertia.patch(`/dashboard/locales/${this.locale.id}`, filteredData);
        }
    }
};
</script>
