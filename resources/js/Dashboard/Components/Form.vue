``` <template>
    <form @submit.prevent="handleSubmit" class="space-y-4">
        <div
            v-for="fieldGroup in groupedFields"
            :key="fieldGroup[0].key"
            class="flex gap-4 mb-4"
            :class="groupClass(fieldGroup)"
        >
            <div
                v-for="field in fieldGroup"
                :key="field.key"
                class="flex flex-col relative"
                :class="fieldClass(field.size)"
            >
                <label :for="field.key" v-if="field.type !== 'hidden'"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    {{ field.label }}
                </label>

                <!-- Locale Switcher for Translatable Fields -->
                <div v-if="isTranslatable(field)" class="absolute z-50 right-2 top-7 flex items-center space-x-2">
                    <div @click="toggleDropdown(field.key)" class="cursor-pointer bg-white px-2 py-1">
                        {{ activeLocales[field.key] }}
                    </div>
                    <div
                        v-show="dropdowns[field.key]"
                        @click.away="closeDropdown(field.key)"
                        class="absolute right-0 mt-2 w-32 bg-white border rounded shadow-lg py-1 z-20"
                    >
                        <a
                            v-for="locale in locales"
                            :key="locale.code"
                            href="#"
                            @click.prevent="selectLocale(field.key, locale.code)"
                            class="block px-4 py-2 text-gray-600 hover:bg-gray-100"
                        >
                            {{ locale.code }}
                        </a>
                    </div>
                </div>

                <!-- Text Input -->
                <input
                    v-if="field.type === 'text' || field.type === 'email' || field.type === 'password' || field.type === 'hidden'"
                    :type="field.type"
                    :id="field.key"
                    :placeholder="field.placeholder"
                    :value="getFieldModelValue(field.key)"
                    @input="setFieldModelValue(field.key, $event.target.value)"
                    class="w-full p-2 border border-gray-300 rounded"
                />

                <!-- Textarea Input -->
                <textarea
                    rows="5"
                    v-else-if="field.type === 'textarea'"
                    :id="field.key"
                    :placeholder="field.placeholder"
                    :value="getFieldModelValue(field.key)"
                    @input="setFieldModelValue(field.key, $event.target.value)"
                    class="w-full p-2 border border-gray-300 rounded"
                ></textarea>

                <!-- CKEditor for Rich Text Editing -->
                <ckeditor
                    v-else-if="field.type === 'rich-editor'"
                    :model-value="getFieldModelValue(field.key)"
                    @update:model-value="setFieldModelValue(field.key, $event)"
                    :editor="editor"
                    :config="editorConfig"
                    class="w-full h-96"
                />

                <VueSelect
                    v-else-if="field.type === 'multi-select' || field.type === 'select'"
                    v-model="formData[field.key]"
                    :options="getTransformedOptions(field)"
                    :is-multi="field.type === 'multi-select'"
                    :placeholder="field.placeholder || 'Select an option'"
                    class="w-full"
                />

                <!-- Toggle Component -->
                <ToggleSwitch
                    v-else-if="field.type === 'toggle'"
                    v-model="formData[field.key]"
                    :label="field.label"
                />

                <flat-pickr
                    v-else-if="field.type === 'flatpickr'"
                    v-model="formData[field.key]"
                    :config="getFlatpickrConfig(field)"
                    :placeholder="field.placeholder"
                    class="w-full p-2 border border-gray-300 rounded"
                />

                <!-- Single or Multiple File Input -->
                <div v-if="field.type === 'file'" class="relative w-full">
                    <!-- File Input -->
                    <input
                        type="file"
                        :id="field.key"
                        :name="field.key"
                        :multiple="field.multiple"
                        @change="handleFileChange($event, field)"
                        class="w-full cursor-pointer"
                    />

                    <!-- File List -->
                    <div class="mt-2">
                        <ul class="space-y-1">
                            <!-- Show Initial Files -->
                            <li
                                v-for="(file, index) in initialFiles[field.key]"
                                :key="'initial-' + index"
                                class="text-sm text-gray-700 flex items-center space-x-2"
                            >
                                <a
                                    :href="file.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="underline text-blue-600 hover:text-blue-800"
                                >
                                    {{ file.name }}
                                </a>
                                <button
                                    type="button"
                                    @click="removeInitialFile(field.key, index)"
                                    class="text-red-500 hover:text-red-700"
                                >
                                    Remove
                                </button>
                            </li>
                            <!-- Show Newly Selected Files -->
                            <li
                                v-for="(file, index) in selectedFiles[field.key]"
                                :key="'new-' + index"
                                class="text-sm text-gray-700 flex items-center space-x-2"
                            >
                                {{ file.name }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Validation Error Display -->
                <p v-if="errors && errors[field.key]" class="text-red-500 text-sm mt-1">
                    {{ errors[field.key] }}
                </p>
            </div>
        </div>
        <button type="submit" class="bg-primary text-white px-4 py-2 rounded">
            {{ submitLabel }}
        </button>
    </form>
</template>

<script>
import {computed, reactive, toRefs} from 'vue';
import {usePage} from '@inertiajs/vue3';
import {Ckeditor} from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import VueSelect from 'vue3-select-component';
import ToggleSwitch from './ToggleSwitch.vue';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import monthSelectPlugin from 'flatpickr/dist/plugins/monthSelect';
import 'flatpickr/dist/plugins/monthSelect/style.css';

export default {
    computed: {
        flatpickr() {
            return flatpickr
        }
    },
    data() {
        return {
            selectedFiles: {},
            initialFiles: {},
        };
    },
    components: {VueSelect, ToggleSwitch, Ckeditor, flatPickr},
    props: {
        fields: Array,
        initialData: Object,
        submitLabel: String,
    },
    created() {
        this.fields.forEach(field => {
            if (field.type === 'file' && this.initialData[field.key]) {
                this.initialFiles[field.key] = Array.isArray(this.initialData[field.key])
                    ? this.initialData[field.key]
                    : [this.initialData[field.key]];
            }
        });
    },
    methods: {
        handleFileChange(event, field) {
            const files = Array.from(event.target.files);

            if (field.multiple) {
                // For multiple file input, append to existing selected files
                this.selectedFiles[field.key] = this.selectedFiles[field.key]
                    ? [...this.selectedFiles[field.key], ...files]
                    : files;
            } else {
                // For single file input, replace the selected file
                this.selectedFiles[field.key] = files;
                this.initialFiles[field.key] = []; // Clear existing files
            }
        },
        removeInitialFile(fieldKey, index) {
            this.initialFiles[fieldKey].splice(index, 1);
        },
        handleSubmit(event) {
            console.log("Form submit prevented!", event);
            event.preventDefault();
        },
        handleSubmits() {
            const formData = new FormData();

            // Append other form fields
            this.fields.forEach(field => {
                if (field.type !== 'file') {
                    formData.append(field.key, this.formData[field.key] || '');
                }
            });

            // Append only newly selected files for file fields
            Object.keys(this.selectedFiles).forEach(fieldKey => {
                if (this.selectedFiles[fieldKey]?.length > 0) {
                    this.selectedFiles[fieldKey].forEach(file => {
                        formData.append(fieldKey, file);
                    });
                }
            });

            // Emit the FormData to the parent or make a direct API call
            this.$emit('submit', formData);
        },
    },
    emits: ['submit'],
    setup(props, {emit}) {
        const {locales} = usePage().props;
        const defaultLocale = computed(() => locales.find(locale => locale.is_default).code);

        const formData = reactive(
            props.fields.reduce((acc, field) => {
                acc[field.key] = props.initialData[field.key] || '';
                return acc;
            }, {})
        );

        const translatedData = reactive(
            locales.reduce((acc, locale) => {
                acc[locale.code] = props.fields.reduce((fieldAcc, field) => {
                    fieldAcc[field.key] = props.initialData.translations?.find(
                        translation => translation.locale === locale.code && translation.key === field.key
                    )?.value || '';
                    return fieldAcc;
                }, {});
                return acc;
            }, {})
        );


        const getTransformedOptions = (field) => {
            if (!field.options || !Array.isArray(field.options)) return [];

            const labelKey = field.labelKey || 'label';
            const valueKey = field.valueKey || 'value';

            return field.options.map(option => ({
                label: option[labelKey] ?? option.label,
                value: option[valueKey] ?? option.value,
            }));
        };

        const getFlatpickrConfig = (field) => {
            const isMonthSelect = field.dateFormat === 'Y-m';
            return {
                dateFormat: field.dateFormat || 'Y-m-d',
                ...(isMonthSelect ? {
                    plugins: [new monthSelectPlugin({shorthand: true, dateFormat: 'Y-m', altFormat: 'F Y'})]
                } : {})
            };
        };

        const activeLocales = reactive({});
        const dropdowns = reactive({});

        props.fields.forEach(field => {
            activeLocales[field.key] = defaultLocale.value;
        });

        const errors = computed(() => usePage().props.errors || {});

        const handleSubmit = () => {

            if (formData.to_this_day === "" || formData.to_this_day === null) {
                formData.to_this_day = 0;
            }

            const translatedFields = {};

            Object.keys(translatedData).forEach(locale => {
                translatedFields[locale] = {};
                props.fields.forEach(field => {
                    if (translatedData[locale][field.key]) {
                        translatedFields[locale][field.key] = translatedData[locale][field.key];
                    }
                });
            });

            emit('submit', {...formData, Translatable: translatedFields});
        };

        const isTranslatable = field => field.translatable === true;

        const getFieldModelValue = key => {
            const locale = activeLocales[key];
            if (!locale) return ''; // Default to empty if locale is undefined
            return locale === defaultLocale.value
                ? formData[key]
                : translatedData[locale]?.[key] || ''; // Fallback to empty string if value is missing
        };

        const setFieldModelValue = (key, value) => {
            const locale = activeLocales[key];
            if (!locale) return;
            if (locale === defaultLocale.value) {
                formData[key] = value;
            } else {
                if (!translatedData[locale]) {
                    translatedData[locale] = {};
                }
                translatedData[locale][key] = value;
            }
        };

        const toggleDropdown = key => {
            dropdowns[key] = !dropdowns[key];
        };

        const closeDropdown = key => {
            dropdowns[key] = false;
        };

        const selectLocale = (key, locale) => {
            activeLocales[key] = locale;
            closeDropdown(key);
        };

        const editor = ClassicEditor;
        const editorConfig = {
            toolbar: [
                'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', 'link',
                'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo',
                '|', 'insertTable', 'mediaEmbed', 'imageUpload'
            ],
            height: '500px',
            placeholder: 'Enter your content here...'
        };

        const groupedFields = computed(() => {
            const groups = [];
            let currentGroup = [];
            props.fields.forEach(field => {
                if (field.size === 'inline') {
                    currentGroup.push(field);
                    if (currentGroup.length === 2) {
                        groups.push(currentGroup);
                        currentGroup = [];
                    }
                } else {
                    if (currentGroup.length) groups.push(currentGroup);
                    groups.push([field]);
                    currentGroup = [];
                }
            });
            if (currentGroup.length) groups.push(currentGroup);
            return groups;
        });


        const fieldClass = size => {
            if (size === 'full') return 'w-full';
            if (size === 'inline') return 'flex-1'; // Flex to take equal space in line
            return 'w-1/2'; // Default half-width
        };

        const groupClass = group => {
            return group.length === 2 ? 'flex' : 'grid grid-cols-1';
        };

        return {
            ...toRefs(formData),
            formData,
            translatedData,
            activeLocales,
            locales,
            defaultLocale,
            handleSubmit,
            errors,
            isTranslatable,
            getFieldModelValue,
            setFieldModelValue,
            toggleDropdown,
            closeDropdown,
            selectLocale,
            dropdowns,
            editor,
            editorConfig,
            groupedFields,
            fieldClass,
            groupClass,
            getTransformedOptions,
            getFlatpickrConfig
        };
    }
};
</script>

<style>
.ck-editor__editable {
    min-height: 300px !important;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 1rem;
}
input[type="file"] {
    display: block;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem;
    background-color: #f9fafb;
    cursor: pointer;
}
input[type="file"]:hover {
    background-color: #f3f4f6;
}
</style>
