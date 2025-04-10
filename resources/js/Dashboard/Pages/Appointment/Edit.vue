    <template>
        <div v-if="appointment">
            <breadcrumbs :recordName="appointment.name"/>
            <h1 class="text-2xl font-bold mb-4">Edit Appointmnet</h1>
            <Form
                :fields="[
                { key: 'full_name', label: 'Full Name', type: 'text', placeholder: 'Enter full name', size: 'inline'},
                { key: 'phone', label: 'Contact information', type: 'text', placeholder: 'Enter contact information', size: 'inline'},
                { key: 'comment', label: 'Comment', type: 'textarea', placeholder: 'Enter comment'},
            ]"
                :initialData="appointment"
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
            appointment: Object,
        },
        setup() {
            const { props } = usePage();
            const appointment = props.appointment;

            const handleUpdate = (data) => {
                Inertia.patch(`/dashboard/appointments/${appointment.id}`, data);
            };

            return {
                appointment,
                handleUpdate,
            };
        }
    };
    </script>
