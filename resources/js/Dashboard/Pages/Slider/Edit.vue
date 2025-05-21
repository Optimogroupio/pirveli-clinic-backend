<template>
    <div v-if="slider">
        <breadcrumbs :recordName="slider.title"/>
        <h1 class="text-2xl font-bold mb-4">Edit Slider</h1>
        <Form
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
                { key: 'URL', label: 'URL', type: 'text', placeholder: 'URL', size: 'left'},
                { key: 'image', label: 'Image', type: 'file', fileType: 'file', multiple: false, size: 'left' },
                ]"
            :initialData="{
                    title: slider.title,
                    description: slider.description,
                    position: slider.position,
                    opens_modal: slider.opens_modal,
                    button_url: slider.button_url,
                    button_title: slider.button_title,
                    image: slider.image,
                    translations: slider.translations,
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
        slider: Object
    },
    setup() {
        const {props} = usePage();
        const slider = props.slider;

        const handleUpdate = (data) => {
            data._method = "put";
            Inertia.post(`/dashboard/slider/${slider.id}`, data);
        };

        return {
            slider,
            handleUpdate,
        };
    }
};
</script>
