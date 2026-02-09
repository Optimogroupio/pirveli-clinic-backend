<template>
    <div>
        <breadcrumbs :recordName="doctor.full_name" :lastItemUrl="`/dashboard/doctors/${doctor.id}/edit`"/>

        <!-- Global Locale Switcher -->
        <GlobalLocaleSwitcher
            v-if="locales.length > 1"
            :currentLocale="globalLocale"
            :locales="locales"
            :hasFieldOverrides="hasFieldOverrides"
            @locale-change="setGlobalLocale"
        />

        <h1 class="text-2xl font-bold mb-4">Edit {{ displayType }}</h1>
        <Form
            ref="formRef"
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
            :globalLocale="globalLocale"
            submitLabel="Update"
            @submit="handleUpdate"
        />
    </div>
</template>

<script>
import Form from '@/Dashboard/Components/Form.vue';
import Breadcrumbs from '@/Dashboard/Components/Breadcrumbs.vue';
import GlobalLocaleSwitcher from '@/Dashboard/Components/GlobalLocaleSwitcher.vue';
import { router as Inertia, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

export default {
    components: { Form, Breadcrumbs, GlobalLocaleSwitcher },
    props: {
        doctor: Object,
        doctorDetail: Object,
        type: String
    },
    setup(props) {
        const { props: pageProps } = usePage();
        const formRef = ref(null);

        // Get locales from page props
        const locales = computed(() => pageProps.locales || []);

        // Get default locale from page props
        const defaultLocale = computed(() => {
            return locales.value.find(locale => locale.is_default)?.code || 'en';
        });

        // Reactive state for global locale
        const globalLocale = ref(defaultLocale.value);

        // Track field overrides
        const hasFieldOverrides = ref(false);

        // Computed display type
        const displayType = computed(() => {
            return props.type.endsWith('s') ? props.type.slice(0, -1) : props.type;
        });

        const handleUpdate = (data) => {
            Inertia.patch(`/dashboard/doctors/${props.doctor.id}/doctor-details/${props.type}/${props.doctorDetail.id}`, data);
        };

        const setGlobalLocale = (locale) => {
            globalLocale.value = locale;

            // Call form method to update all fields if formRef exists
            if (formRef.value && formRef.value.setGlobalLocale) {
                formRef.value.setGlobalLocale(locale);
            }
        };

        return {
            locales,
            formRef,
            globalLocale,
            hasFieldOverrides,
            displayType,
            handleUpdate,
            setGlobalLocale,
        };
    }
};
</script>
