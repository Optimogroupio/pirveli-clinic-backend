<template>
    <div v-if="specialty">
        <breadcrumbs :recordName="specialty.name"/>
        <h1 class="text-2xl font-bold mb-4">Edit Specialty</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Specialty', type: 'text', placeholder: 'Enter specialty', translatable: true},
            ]"
            :initialData="specialty"
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
        const specialty = props.specialty;

        const handleUpdate = (data) => {
            Inertia.patch(`/dashboard/specialties/${specialty.id}`, data);
        };

        return {
            specialty,
            handleUpdate,
        };
    }
};
</script>
