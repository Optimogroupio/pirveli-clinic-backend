    <template>
        <div v-if="news">
            <breadcrumbs :recordName="news.title"/>
            <h1 class="text-2xl font-bold mb-4">Edit News</h1>
            <Form
                :fields="[
                    { key: 'title', label: 'Title', type: 'text', placeholder: 'Enter title', translatable: true },
                    { key: 'description', label: 'Description', type: 'rich-editor', placeholder: 'Enter description', translatable: true, size: 'full' },
                    { key: 'service_id', label: 'Service', type: 'select', options: services, labelKey: 'name', valueKey: 'id', placeholder: 'Select service' },
                    { key: 'doctors', label: 'Doctors', type: 'multi-select', options: doctors, labelKey: 'full_name', valueKey: 'id', placeholder: 'Select doctors', size: 'inline' },
                    { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true },
                    { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true },
                ]"
                :initialData="{
                    title: news.title,
                    description: news.description,
                    service_id: news.service_id,
                    doctors: (news.doctors || []).map(doctor => doctor.id),
                    meta_title: news.meta_title,
                    meta_description: news.meta_description,
                    translations: news.translations,
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
            news: Object,
            services: Array,
            doctors: Array,
        },
        setup() {
            const { props } = usePage();
            const news = props.news;

            const handleUpdate = (data) => {
                Inertia.patch(`/dashboard/news/${news.id}`, data);
            };

            return {
                news,
                handleUpdate,
            };
        }
    };
    </script>
