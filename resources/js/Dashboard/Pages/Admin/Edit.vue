<template>
    <div>
        <breadcrumbs :recordName="admin.first_name + ' ' + admin.last_name"/>
        <h1 class="text-2xl font-bold mb-4">Edit Administrator</h1>
        <Form
            :fields="[
                { key: 'first_name', label: 'First Name', type: 'text', placeholder: 'Enter first name', size: 'inline' },
                { key: 'last_name', label: 'Last Name', type: 'text', placeholder: 'Enter last name', size: 'inline' },
                { key: 'login', label: 'Login', type: 'text', placeholder: 'Enter login', size: 'inline' },
                { key: 'email', label: 'Email', type: 'email', placeholder: 'Enter email', size: 'inline' },
                { key: 'password', label: 'Password', type: 'password', placeholder: 'Enter password', fullWidth: true },
                { key: 'super_admin', label: 'Super Admin', type: 'toggle', fullWidth: true },
                { key: 'roles', label: 'Role', type: 'multi-select', placeholder: 'Select role', options: roles, valueKey: 'id', labelKey: 'name' },
            ]"
            :initialData="{
                first_name: admin.first_name,
                last_name: admin.last_name,
                login: admin.login,
                email: admin.email,
                password: null,
                roles: admin.roles.map(role => role.id),
                super_admin: admin.super_admin
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
        admin: Object,
        roles: Array
    },
    methods: {
        handleUpdate(data) {
            // Remove any fields with null or empty values before sending
            const filteredData = Object.fromEntries(
                Object.entries(data).filter(([_, value]) => value !== null && value !== '')
            );

            Inertia.patch(`/dashboard/administrators/${this.admin.id}`, filteredData);
        }
    }
};
</script>
