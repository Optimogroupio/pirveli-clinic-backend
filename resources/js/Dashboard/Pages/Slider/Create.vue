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

        <h1 class="text-2xl font-bold mb-4">Create Slider</h1>
        <Form
            ref="formRef"
            :fields="[
                { key: 'title', label: 'Title', type: 'text', placeholder: 'Enter title', size: 'full', translatable: true },
                { key: 'description', label: 'Description', type: 'rich-editor', placeholder: 'Enter description', size: 'full', translatable: true },
                {
                    key: 'position',
                    label: 'Position',
                    type: 'select',
                    options: [
                        { label: 'Top', value: 'top' },
                        { label: 'Bottom', value: 'bottom' }
                    ],
                    placeholder: 'Select position'
                },
                { key: 'url', label: 'URL', type: 'text', placeholder: 'URL', size: 'left'},
                { key: 'image', label: 'Image', type: 'file', fileType: 'file', multiple: false, size: 'inline' },
            ]"
            :initialData="{}"
            :globalLocale="globalLocale"
            :uploadUrl="null"
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
        doctors: Array
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
            Inertia.post('/dashboard/slider', data);
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
