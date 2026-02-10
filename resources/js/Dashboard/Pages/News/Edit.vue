<template>
    <div v-if="news">
        <breadcrumbs :recordName="news.title"/>

        <!-- Global Locale Switcher -->
        <GlobalLocaleSwitcher
            v-if="locales.length > 1"
            :currentLocale="globalLocale"
            :locales="locales"
            :hasFieldOverrides="hasFieldOverrides"
            @locale-change="setGlobalLocale"
        />

        <h1 class="text-2xl font-bold mb-4">Edit News</h1>
        <Form
            ref="formRef"
            :fields="[
                { key: 'title', label: 'Title', type: 'text', placeholder: 'Enter title', translatable: true },
                { key: 'description', label: 'Description', type: 'rich-editor', placeholder: 'Enter description', translatable: true, size: 'full' },
                { key: 'service_id', label: 'Service', type: 'select', options: services, labelKey: 'name', valueKey: 'id', placeholder: 'Select service' },
                { key: 'doctors', label: 'Doctors', type: 'multi-select', options: doctors, labelKey: 'full_name', valueKey: 'id', placeholder: 'Select doctors', size: 'inline' },
                { key: 'image', label: 'Image', type: 'file', fileType: 'file', multiple: false, size: 'half' },
                { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true },
                { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true },
            ]"
            :initialData="{
                title: news.title,
                description: news.description,
                service_id: news.service_id,
                image: news.image,
                doctors: (news.doctors || []).map(doctor => doctor.id),
                meta_title: news.meta_title,
                meta_description: news.meta_description,
                translations: news.translations,
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
        news: Object,
        services: Array,
        doctors: Array,
    },
    setup() {
        const { props } = usePage();
        const news = props.news;
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
            Inertia.post(`/dashboard/news/${news.id}`, data);
        };

        const setGlobalLocale = (locale) => {
            globalLocale.value = locale;

            // Call form method to update all fields if formRef exists
            if (formRef.value && formRef.value.setGlobalLocale) {
                formRef.value.setGlobalLocale(locale);
            }
        };

        return {
            news,
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
