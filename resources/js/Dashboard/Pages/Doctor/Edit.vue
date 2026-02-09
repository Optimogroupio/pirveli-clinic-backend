<template>
    <div>
        <!-- Breadcrumbs and Doctor Form -->
        <breadcrumbs :recordName="doctor.full_name"/>

        <!-- Global Locale Switcher -->
        <GlobalLocaleSwitcher
            v-if="locales.length > 1"
            :currentLocale="globalLocale"
            :locales="locales"
            :hasFieldOverrides="hasFieldOverrides"
            @locale-change="setGlobalLocale"
        />

        <h1 class="text-2xl font-bold mb-4">Edit Doctor</h1>
        <Form
            ref="formRef"
            :fields="[
                { key: 'full_name', label: 'Full Name', type: 'text', placeholder: 'Enter full name', size: 'inline', translatable: true },
                { key: 'specialties', label: 'Specialty', type: 'multi-select', options: specialties, labelKey: 'name', valueKey: 'id', placeholder: 'Select specialty', size: 'inline'},
                { key: 'service_id', label: 'Service', type: 'select', placeholder: 'Select service', options: services, valueKey: 'id', labelKey: 'name', size: 'inline'  },
                { key: 'languages', label: 'Languages', type: 'multi-select', placeholder: 'Select languages', options: languages, valueKey: 'id', labelKey: 'name', size: 'inline'  },
                { key: 'image', label: 'Image', type: 'file', fileType: 'file', multiple: false, size: 'half' },
                { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true, size: 'half' },
                { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true, size: 'half' },
            ]"
            :initialData="{
                full_name: doctor.full_name,
                position: doctor.position,
                image: doctor.image,
                specialties: (doctor.specialties || []).map(specialty => specialty.id),
                service_id: doctor.service_id,
                languages: (doctor.languages || []).map(language => language.id),
                translations: doctor.translations,
            }"
            :globalLocale="globalLocale"
            submitLabel="Update"
            @submit="handleUpdate"
        />

        <!-- Educations Section -->
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">
                    Educations
                </h2>
                <Link
                    v-if="$can('create doctor education')"
                    @click.prevent="createEducation"
                    class="bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded ml-4"
                >
                    + Add
                </Link>
            </div>
            <Table
                :columns="educationColumns"
                :data="doctor.educations"
                :can-edit="true"
                :can-delete="true"
                :showCheckbox="true"
                :sortBy="'name'"
                :sortDirection="'asc'"
                :draggable="true"
                @reorder="updateOrder"
                @delete-multiple="deleteMultipleEducations"
                @edit="editEducation"
                @delete="deleteEducation"
            />
        </div>

        <!-- Experiences Section -->
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">
                    Experiences
                </h2>
                <Link
                    v-if="$can('create doctor experience')"
                    @click.prevent="createExperience"
                    class="bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded ml-4"
                >
                    + Add
                </Link>
            </div>
            <Table
                :columns="experienceColumns"
                :data="doctor.experiences"
                :can-edit="true"
                :can-delete="true"
                :paginated="false"
                @edit="editExperience"
                @delete="deleteExperience"
            />
        </div>

        <!-- Certificates Section -->
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">
                    Certificates
                </h2>
                <Link
                    v-if="$can('create doctor certificate')"
                    @click.prevent="createCertificate"
                    class="bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded ml-4"
                >
                    + Add
                </Link>
            </div>
            <Table
                :columns="certificateColumns"
                :data="doctor.certificates"
                :can-edit="true"
                :can-delete="true"
                :paginated="false"
                @edit="editCertificate"
                @delete="deleteCertificate"
            />
        </div>
    </div>
</template>

<script>
import Form from '@/Dashboard/Components/Form.vue';
import Table from '@/Dashboard/Components/Table.vue';
import Breadcrumbs from '@/Dashboard/Components/Breadcrumbs.vue';
import GlobalLocaleSwitcher from '@/Dashboard/Components/GlobalLocaleSwitcher.vue';
import { router as Inertia, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

export default {
    components: {Form, Table, Breadcrumbs, GlobalLocaleSwitcher},
    props: {
        doctor: Object,
        services: Array,
        specialties: Array,
        languages: Array,
    },
    setup() {
        const { props } = usePage();
        const doctor = props.doctor;
        const formRef = ref(null);

        // Get locales from page props
        const locales = computed(() => props.locales || []);

        // Get default locale from page props
        const defaultLocale = computed(() => {
            return locales.value.find(locale => locale.is_default)?.code || 'en';
        });

        // Reactive state for global locale
        const globalLocale = ref(defaultLocale.value);

        // Track field overrides
        const hasFieldOverrides = ref(false);

        const handleUpdate = (data) => {
            const filteredData = Object.fromEntries(
                Object.entries(data).filter(([_, value]) => value !== null && value !== '')
            );
            filteredData._method =  "put";
            Inertia.post(`/dashboard/doctors/${doctor.id}`, filteredData);
        };

        const setGlobalLocale = (locale) => {
            globalLocale.value = locale;

            // Call form method to update all fields if formRef exists
            if (formRef.value && formRef.value.setGlobalLocale) {
                formRef.value.setGlobalLocale(locale);
            }
        };

        // Education methods
        const createEducation = () => {
            Inertia.get(`/dashboard/doctors/${doctor.id}/doctor-details/educations/create`);
        };

        const editEducation = (id) => {
            Inertia.get(`/dashboard/doctors/${doctor.id}/doctor-details/educations/${id}/edit`);
        };

        const deleteEducation = (id) => {
            Inertia.delete(`/dashboard/doctors/${doctor.id}/doctor-details/educations/${id}`);
        };

        // Experience methods
        const createExperience = () => {
            Inertia.get(`/dashboard/doctors/${doctor.id}/doctor-details/experiences/create`);
        };

        const editExperience = (id) => {
            Inertia.get(`/dashboard/doctors/${doctor.id}/doctor-details/experiences/${id}/edit`);
        };

        const deleteExperience = (id) => {
            Inertia.delete(`/dashboard/doctors/${doctor.id}/doctor-details/experiences/${id}`);
        };

        // Certificate methods
        const createCertificate = () => {
            Inertia.get(`/dashboard/doctors/${doctor.id}/doctor-details/certificates/create`);
        };

        const editCertificate = (id) => {
            Inertia.get(`/dashboard/doctors/${doctor.id}/doctor-details/certificates/${id}/edit`);
        };

        const deleteCertificate = (id) => {
            Inertia.delete(`/dashboard/doctors/${doctor.id}/doctor-details/certificates/${id}`);
        };

        const updateOrder = async (orderedIds) => {
            await axios.post(`/dashboard/doctors/${doctor.id}/update-detail-order`, { orderedIds });
        };

        const deleteMultipleEducations = (selectedIds) => {
            Inertia.delete(`/dashboard/doctors/${doctor.id}/delete-multiple-details`, {
                data: { ids: selectedIds },
                onSuccess: () => {
                    console.log('here')
                }
            });
        };

        // Return data for template
        const educationColumns = [
            {key: 'name', label: 'Name', width: '20%'},
            {key: 'title', label: 'Title', width: '20%'},
            {key: 'start_date', label: 'Start date', width: '20%'},
            {key: 'end_date', label: 'End date', width: '20%'},
            {key: 'to_this_day', label: 'To this day', width: '20%'},
        ];

        const experienceColumns = [
            {key: 'name', label: 'Name', width: '20%'},
            {key: 'title', label: 'Title', width: '20%'},
            {key: 'start_date', label: 'Start date', width: '20%'},
            {key: 'end_date', label: 'End date', width: '20%'},
            {key: 'to_this_day', label: 'To this day', width: '20%'},
        ];

        const certificateColumns = [
            {key: 'name', label: 'Name', width: '20%'},
            {key: 'title', label: 'Title', width: '20%'},
            {key: 'start_date', label: 'Start date', width: '20%'},
            {key: 'end_date', label: 'End date', width: '20%'},
            {key: 'to_this_day', label: 'To this day', width: '20%'},
        ];

        return {
            doctor,
            locales,
            formRef,
            globalLocale,
            hasFieldOverrides,
            educationColumns,
            experienceColumns,
            certificateColumns,
            handleUpdate,
            setGlobalLocale,
            createEducation,
            editEducation,
            deleteEducation,
            createExperience,
            editExperience,
            deleteExperience,
            createCertificate,
            editCertificate,
            deleteCertificate,
            updateOrder,
            deleteMultipleEducations
        };
    }
};
</script>
