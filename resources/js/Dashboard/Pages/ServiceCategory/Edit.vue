<template>
  <div v-if="service_category">
    <breadcrumbs :recordName="service_category.name"/>
    <h1 class="text-2xl font-bold mb-4">Edit Service Category</h1>
    <Form
        :fields="[
                { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name', translatable: true},
                { key: 'description', label: 'Description', type: 'rich-editor', placeholder: 'Enter description', size: 'full', translatable: true },
                { key: 'meta_title', label: 'Meta Title', type: 'text', placeholder: 'Enter meta title', translatable: true},
                { key: 'meta_description', label: 'Meta Description', type: 'textarea', placeholder: 'Enter meta description', translatable: true},
            ]"
        :initialData="service_category"
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
  setup() {
    const {props} = usePage();
    const service_category = props.service_category;

    const handleUpdate = (data) => {
      Inertia.patch(`/dashboard/service_categories/${service_category.id}`, data);
    };

    return {
      service_category,
      handleUpdate,
    };
  }
};
</script>
