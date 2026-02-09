<template>
    <div v-if="locales && locales.length > 1" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Translation Settings</h3>
                <p class="text-sm text-gray-600">Switch locale for all translatable fields on this page</p>
            </div>
            <div class="relative">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Global Locale:</span>
                    <div @click="toggleDropdown" class="cursor-pointer bg-white px-3 py-2 border border-gray-300 rounded-md shadow-sm flex items-center">
                        <span>{{ currentLocale }}</span>
                        <svg class="ml-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div
                    v-show="showDropdown"
                    @click.away="closeDropdown"
                    class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-30"
                >
                    <a
                        v-for="locale in locales"
                        :key="locale.code"
                        href="#"
                        @click.prevent="selectLocale(locale.code)"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        :class="{ 'bg-blue-50': locale.code === currentLocale }"
                    >
                        {{ locale.code }}
                    </a>
                </div>
            </div>
        </div>
        <div v-if="hasFieldOverrides" class="mt-3 text-sm text-blue-600">
            <span class="inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Some fields have individual locale overrides
            </span>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name: 'GlobalLocaleSwitcher',
    props: {
        currentLocale: {
            type: String,
            required: true
        },
        locales: {
            type: Array,
            required: true,
            default: () => []
        },
        hasFieldOverrides: {
            type: Boolean,
            default: false
        }
    },
    emits: ['locale-change'],
    setup(props, { emit }) {
        const showDropdown = ref(false);

        const toggleDropdown = () => {
            showDropdown.value = !showDropdown.value;
        };

        const closeDropdown = () => {
            showDropdown.value = false;
        };

        const selectLocale = (locale) => {
            emit('locale-change', locale);
            closeDropdown();
        };

        return {
            showDropdown,
            toggleDropdown,
            closeDropdown,
            selectLocale
        };
    }
};
</script>
