<template>
    <div v-if="language">
        <breadcrumbs :recordName="language.name"/>
        <h1 class="text-2xl font-bold mb-4">Edit Language</h1>
        <Form
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name'},
            ]"
            :initialData="language"
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
        const language = props.language;

        const handleUpdate = (data) => {
            Inertia.patch(`/dashboard/languages/${language.id}`, data);
        };

        return {
            language,
            handleUpdate,
        };
    }
};
</script>
