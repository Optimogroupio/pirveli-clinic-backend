<template>
    <div>
        <breadcrumbs :recordName="permission.name"/>
        <h1 class="text-2xl font-bold mb-4">Edit Permission</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter permission name', size: 'inline' },
                { key: 'guard_name', label: 'Guard Name', type: 'text', placeholder: 'Enter guard name', size: 'inline' }
            ]"
            :initialData="permission"
            submitLabel="Update"
            @submit="handleUpdate"
        />
    </div>
</template>

<script>
import Form from '@/Dashboard/Components/Form.vue';
import Breadcrumbs from '@/Dashboard/Components/Breadcrumbs.vue';
import {router as Inertia, usePage} from '@inertiajs/vue3';

export default {
    components: {Form, Breadcrumbs},
    setup() {
        const {props} = usePage();
        const permission = props.permission;

        const handleUpdate = (data) => {
            Inertia.patch(`/dashboard/permissions/${permission.id}`, data);
        };

        return {
            permission,
            handleUpdate
        };
    }
};
</script>
