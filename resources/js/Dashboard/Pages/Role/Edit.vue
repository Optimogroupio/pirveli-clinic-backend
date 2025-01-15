<template>
    <div>
        <breadcrumbs :recordName="role.name"/>
        <h1 class="text-2xl font-bold mb-4">Edit Role</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter role name', size: 'inline' },
                { key: 'guard_name', label: 'Guard Name', type: 'select', placeholder: 'Enter guard name', options: guards, valueKey: 'value', labelKey: 'name', size: 'inline'},
                { key: 'permissions', label: 'Permissions', type: 'multi-select', options: permissions, valueKey: 'id', labelKey: 'name', placeholder: 'Select permissions' }
            ]"
            :initialData="{
                name: role.name,
                guard_name: role.guard_name,
                permissions: role.permissions.map(permission => permission.id) // Pre-select permissions
            }"
            submitLabel="Update Role"
            @submit="handleUpdate"
        />
    </div>
</template>

<script>
import Form from '@/Dashboard/Components/Form.vue';
import Breadcrumbs from '@/Dashboard/Components/Breadcrumbs.vue';
import { router as Inertia } from '@inertiajs/vue3';

export default {
    components: {Form, Breadcrumbs},
    props: {
        guards: Array, // Permissions passed from backend
        permissions: Array, // Permissions passed from backend
        role: Object // Role passed from backend, with pre-assigned permissions
    },
    methods: {
        handleUpdate(data) {
            Inertia.patch(`/dashboard/roles/${this.role.id}`, data);
        }
    }
};
</script>
