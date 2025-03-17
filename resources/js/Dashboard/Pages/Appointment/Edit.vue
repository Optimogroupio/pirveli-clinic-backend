    <template>
        <div v-if="appointment">
            <breadcrumbs :recordName="appointment.name"/>
            <h1 class="text-2xl font-bold mb-4">Edit Appointmnet</h1>
            <Form
                :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', size: 'inline'},
                { key: 'surname', label: 'Surname', type: 'text', placeholder: 'Enter surname', size: 'inline' },
                { key: 'phone', label: 'Phone', type: 'text', placeholder: 'Enter surname'},
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
