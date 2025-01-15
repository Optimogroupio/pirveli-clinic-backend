<template>
    <div>
        <breadcrumbs />
        <h1 class="text-2xl font-bold mb-4">Create Administrator</h1>
        <Form
            :fields="[
                { key: 'first_name', label: 'First Name', type: 'text', placeholder: 'Enter first name', size: 'inline' },
                { key: 'last_name', label: 'Last Name', type: 'text', placeholder: 'Enter last name', size: 'inline' },
                { key: 'login', label: 'Login', type: 'text', placeholder: 'Enter login', size: 'inline' },
                { key: 'email', label: 'Email', type: 'email', placeholder: 'Enter email', size: 'inline' },
                { key: 'password', label: 'Password', type: 'password', placeholder: 'Enter password', fullWidth: true },
                { key: 'super_admin', label: 'Super Admin', type: 'toggle', fullWidth: true },
                { key: 'roles', label: 'Role', type: 'select', placeholder: 'Select role', options: roles, valueKey: 'name', labelKey: 'name' },
            ]"
            :initialData="{ first_name: '', last_name: '', login: '', password: '', roles: [], super_admin: 0 }"
            submitLabel="Create"
            @submit="handleCreate"
        />
    </div>
</template>

<script>
import Form from '@/Dashboard/Components/Form.vue';
import Breadcrumbs from '@/Dashboard/Components/Breadcrumbs.vue';
import { router as Inertia } from '@inertiajs/vue3';

export default {
    components: { Form, Breadcrumbs },
    props: {
        permissions: Array,
        roles: Array
    },
    methods: {
        handleCreate(data) {
            Inertia.post('/dashboard/administrators', data);
        }
    }
};
</script>
