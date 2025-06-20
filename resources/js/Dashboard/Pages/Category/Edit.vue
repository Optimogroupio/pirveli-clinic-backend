    <template>
        <div v-if="category">
            <breadcrumbs :recordName="category.name"/>
            <h1 class="text-2xl font-bold mb-4">Edit Blog</h1>
            <Form
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
    import {router as Inertia, usePage} from '@inertiajs/vue3';

    export default {
        components: {Form, Breadcrumbs},
        props: {
            category: Object,
            services: Array,
        },
        setup() {
            const { props } = usePage();
            const category = props.category;

            const handleUpdate = (data) => {
                data._method = "put";
                Inertia.post(`/dashboard/categories/${category.id}`, data);
            };

            return {
                category,
                handleUpdate,
            };
        }
    };
    </script>
