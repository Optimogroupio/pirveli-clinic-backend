<template>
    <div v-if="slider">
        <breadcrumbs :recordName="slider.title"/>

        <!-- Global Locale Switcher -->
        <GlobalLocaleSwitcher
            v-if="locales.length > 1"
            :currentLocale="globalLocale"
            :locales="locales"
            :hasFieldOverrides="hasFieldOverrides"
            @locale-change="setGlobalLocale"
        />

        <h1 class="text-2xl font-bold mb-4">Edit Slider</h1>
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
                { key: 'image', label: 'Image', type: 'file', fileType: 'file', multiple: false, size: 'left' },
            ]"
            :initialData="{
                title: slider.title,
                description: slider.description,
                position: slider.position,
                url: slider.url,
                image: slider.image,
                translations: slider.translations,
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
        slider: Object
    },
    setup() {
        const {props} = usePage();
        const slider = props.slider;
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
            Inertia.post(`/dashboard/slider/${slider.id}`, data);
        };

        const setGlobalLocale = (locale) => {
            globalLocale.value = locale;

            // Call form method to update all fields if formRef exists
            if (formRef.value && formRef.value.setGlobalLocale) {
                formRef.value.setGlobalLocale(locale);
            }
        };

        return {
            slider,
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
