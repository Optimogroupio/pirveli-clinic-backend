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
                    <input
                        type="file"
                        :id="field.key"
                        :name="field.key"
                        :multiple="field.multiple"
                        @change="handleFileChange($event, field)"
                        class="w-full cursor-pointer"
                    />
                    <div class="mt-2">
                        <ul class="space-y-1">
                            <!-- Show Initial Files -->
                            <li
                                v-for="(file, index) in initialFiles[field.key]"
                                :key="'initial-' + index"
                                class="text-sm text-gray-700 flex flex-col items-start gap-3"
                            >
                                <div v-if="isImage(file)" class="w-24 h-24">
                                    <img :src="file.url" :alt="file.file_name" class="object-cover w-full h-full rounded">
                                </div>
                                <div class="flex items-center gap-3">
                                    <span>{{ file.file_name }}</span>
                                    <button
                                        type="button"
                                        @click="removeInitialFile(field.key, index)"
                                        class="hidden text-red-500 hover:text-red-700"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </li>
                            <li
                                v-for="(file, index) in selectedFiles[field.key]"
                                :key="'new-' + index"
                                class="text-sm text-gray-700 flex flex-col items-start gap-5 space-x-2"
                            >
                                <div v-if="isImage(file)" class="w-24 h-24">
                                    <img
                                        v-if="file && typeof file === 'object' && file.name && file.type?.startsWith('image/')"
                                        :src="getObjectURL(file)"
                                        :alt="file.name || 'Uploaded file'"
                                        class="object-cover w-full h-full rounded"
                                    >
                                    <span v-else class="text-red-500">Invalid File</span>
                                </div>
                                <span>{{ file.name }}</span>
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
import {computed, onMounted, reactive, toRefs} from 'vue';
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

        const isImage = (file) => {
            const imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            return file && imageTypes.includes(file.type || file.content_type);
        };

        const getObjectURL = (file) => {
            if (file && typeof URL !== 'undefined' && typeof URL.createObjectURL === 'function') {
                return URL.createObjectURL(file);
            }
            return '';
        };

        const revokeObjectURL = (file) => {
            if (file) {
                URL.revokeObjectURL(file);
            }
        }

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

        const selectedFiles = reactive({});
        const initialFiles = reactive({});

        props.fields.forEach(field => {
            activeLocales[field.key] = defaultLocale.value;
            if (field.type === 'file' && props.initialData[field.key]) {
                initialFiles[field.key] = Array.isArray(props.initialData[field.key])
                    ? props.initialData[field.key]
                    : [props.initialData[field.key]];
            }
        });

        onMounted(() => {
            props.fields.forEach(field => {
                if (field.type === 'file' && props.initialData[field.key]) {
                    initialFiles[field.key] = Array.isArray(props.initialData[field.key])
                        ? props.initialData[field.key]
                        : [props.initialData[field.key]];
                }
                activeLocales[field.key] = defaultLocale.value;
            });
        });

        const handleFileChange = (event, field) => {
            const files = Array.from(event.target.files);

            if (field.multiple) {
                selectedFiles[field.key] = selectedFiles[field.key]
                    ? [...selectedFiles[field.key], ...files]
                    : files;
            } else {
                selectedFiles[field.key] = files;
                initialFiles[field.key] = [];
            }
        };

        const removeInitialFile = (fieldKey, index) => {
            initialFiles[fieldKey].splice(index, 1);
        };

        const errors = computed(() => usePage().props.errors || {});

        const handleSubmit = () => {

            if (formData.to_this_day === "" || formData.to_this_day === null) {
                formData.to_this_day = 0;
            }

            props.fields.forEach(field => {
                if (field.type !== 'file') {
                    formData[field.key] = formData[field.key] || '';
                }
            });

            const translatedFields = {};

            Object.keys(translatedData).forEach(locale => {
                translatedFields[locale] = {};
                props.fields.forEach(field => {
                    if (translatedData[locale][field.key]) {
                        translatedFields[locale][field.key] = translatedData[locale][field.key];
                    }
                });
            });

            Object.keys(selectedFiles).forEach(fieldKey => {
                if (selectedFiles[fieldKey]?.length > 0) {
                    selectedFiles[fieldKey].forEach(file => {
                        formData[fieldKey] = file;
                    });
                }
            });

            emit('submit', {...formData, Translatable: translatedFields});
        };

        const isTranslatable = field => field.translatable === true;

        const getFieldModelValue = key => {
            const locale = activeLocales[key];
            if (!locale) return '';
            return locale === defaultLocale.value
                ? formData[key]
                : translatedData[locale]?.[key] || '';
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
            if (size === 'inline') return 'flex-1';
            return 'w-1/2';
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
            getFlatpickrConfig,
            handleFileChange,
            removeInitialFile,
            isImage,
            initialFiles,
            selectedFiles,
            getObjectURL,
            revokeObjectURL
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
