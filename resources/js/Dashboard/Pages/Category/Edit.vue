<template>
    <div v-if="category">
        <breadcrumbs :recordName="category.name"/>

        <!-- Global Locale Switcher -->
        <GlobalLocaleSwitcher
            v-if="locales.length > 1"
            :currentLocale="globalLocale"
            :locales="locales"
            :hasFieldOverrides="hasFieldOverrides"
            @locale-change="setGlobalLocale"
        />

        <h1 class="text-2xl font-bold mb-4">Edit Check-Up</h1>
        <Form
            ref="formRef"
            :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', translatable: true },
                { key: 'description', label: 'Description', type: 'rich-editor', placeholder: 'Enter description', size: 'full', translatable: true },
                { key: 'video_iframe', label: 'Video Iframe', type: 'textarea', placeholder: 'Enter video iframe', size: 'full', translatable: false },
                { key: 'services', label: 'Services', type: 'multi-select', options: services, labelKey: 'name', valueKey: 'id', placeholder: 'Select services', size: 'half' },
                { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true, size: 'half' },
                { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true, size: 'half' },
            ]"
            :initialData="{
                name: category.name,
                description: category.description,
                video_iframe: category.video_iframe,
                services: (category.services || []).map(service => service.id),
                meta_title: category.meta_title,
                meta_description: category.meta_description,
                translations: category.translations,
            }"
            :globalLocale="globalLocale"
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
import GlobalLocaleSwitcher from '@/Dashboard/Components/GlobalLocaleSwitcher.vue';
import { router as Inertia, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

export default {
    components: {Form, Breadcrumbs, GlobalLocaleSwitcher},
    props: {
        category: Object,
        services: Array,
    },
    setup() {
        const { props } = usePage();
        const category = props.category;
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
            data._method = "put";
            Inertia.post(`/dashboard/check-up/${category.id}`, data);
        };

        const setGlobalLocale = (locale) => {
            globalLocale.value = locale;

            // Call form method to update all fields if formRef exists
            if (formRef.value && formRef.value.setGlobalLocale) {
                formRef.value.setGlobalLocale(locale);
            }
        };

        return {
            category,
            locales,
            formRef,
            globalLocale,
            hasFieldOverrides,
            handleUpdate,
            setGlobalLocale,
        };
    }
};
</script>
