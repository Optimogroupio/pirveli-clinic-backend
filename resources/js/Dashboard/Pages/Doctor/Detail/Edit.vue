<template>
    <div>
        <breadcrumbs :recordName="doctor.full_name" :lastItemUrl="`/dashboard/doctors/${doctor.id}/edit`"/>
        <h1 class="text-2xl font-bold mb-4">Edit {{ displayType }}</h1>
        <Form
            :fields="[
                { key: 'doctor_id', label: 'Doctor ID', type: 'hidden', translatable: false },
                { key: 'type', label: 'Type', type: 'hidden', translatable: false },
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', size: 'inline', translatable: true },
                { key: 'title', label: 'Title', type: 'text', placeholder: 'Enter title', size: 'inline', translatable: true },
                { key: 'start_date', label: 'Start date', type: 'flatpickr', placeholder: 'Choose start date', size: 'inline', dateFormat: 'Y-m' },
                { key: 'end_date', label: 'End date', type: 'flatpickr', placeholder: 'Choose end date', size: 'inline', dateFormat: 'Y-m' },
                { key: 'to_this_day', label: 'To this day', type: 'toggle', fullWidth: true }
            ]"
            :initialData="{
                doctor_id: doctor.id,
                type: displayType,
                name: doctorDetail.name,
                title: doctorDetail.title,
                start_date: doctorDetail.start_date,
                end_date: doctorDetail.end_date,
                to_this_day: doctorDetail.to_this_day,
                translations: doctorDetail.translations
            }"
            submitLabel="Update"
            @submit="handleUpdate"
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
        doctor: Object,
        doctorDetail: Object,
        type: String
    },
    computed: {
        displayType() {
            return this.type.endsWith('s') ? this.type.slice(0, -1) : this.type;
        }
    },
    methods: {
        handleUpdate(data) {
            Inertia.patch(`/dashboard/doctors/${this.doctor.id}/doctor-details/${this.type}/${this.doctorDetail.id}`, data);
        }
    }
};
</script>
