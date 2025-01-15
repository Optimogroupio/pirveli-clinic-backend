<template>
    <div>
        <breadcrumbs/>
        <h1 class="text-2xl font-bold mb-4">Create Role</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter role name', size: 'inline' },
                { key: 'guard_name', label: 'Guard Name', type: 'select', placeholder: 'Enter guard name', options: guards, valueKey: 'value', labelKey: 'name', size: 'inline'},
                { key: 'permissions', label: 'Permissions', type: 'multi-select', options: permissions, labelKey: 'name', valueKey: 'id', placeholder: 'Select permissions' }
            ]"
            :initialData="{ name: '', guard_name: 'dashboard', permissions: [] }"
            submitLabel="Create Role"
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
        permissions: Array,
        guards: Array
    },
    methods: {
        handleCreate(data) {
            Inertia.post('/dashboard/roles', data);
        }
    }
};
</script>
