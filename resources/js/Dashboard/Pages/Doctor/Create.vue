<template>
    <div>
        <breadcrumbs/>

        <!-- Global Locale Switcher -->
        <GlobalLocaleSwitcher
            v-if="locales.length > 1"
            :currentLocale="globalLocale"
            :locales="locales"
            :hasFieldOverrides="hasFieldOverrides"
            @locale-change="setGlobalLocale"
        />

        <h1 class="text-2xl font-bold mb-4">Create Doctor</h1>
        <Form
            ref="formRef"
            :fields="[
                { key: 'full_name', label: 'Full Name', type: 'text', placeholder: 'Enter full name', size: 'inline', translatable: true },
                { key: 'specialties', label: 'Specialty', type: 'multi-select', options: specialties, labelKey: 'name', valueKey: 'id', placeholder: 'Select specialty', size: 'inline'},
                { key: 'service_id', label: 'Service', type: 'select', options: services, labelKey: 'name', valueKey: 'id', placeholder: 'Select service', size: 'inline' },
                { key: 'languages', label: 'Languages', type: 'multi-select', options: languages, labelKey: 'name', valueKey: 'id', placeholder: 'Select languages', size: 'inline' },
                { key: 'image', label: 'Image', type: 'file', fileType: 'file', multiple: false, size: 'half' },
                { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true, size: 'half' },
                { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true, size: 'half' },
            ]"
            :initialData="{ full_name: '', specialties: [], meta_title: '', meta_description: '', languages: [] }"
            :globalLocale="globalLocale"
            submitLabel="Create"
            @submit="handleCreate"
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
    components: {Form, Breadcrumbs, GlobalLocaleSwitcher},
    props: {
        services: Array,
        specialties: Array,
        languages: Array,
    },
    setup() {
        const { props } = usePage();
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

        const handleCreate = (data) => {
            Inertia.post('/dashboard/doctors', data);
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
            handleCreate,
            setGlobalLocale,
        };
    }
};
</script>
