<template>
    <div>
        <!-- Breadcrumbs Component -->
        <breadcrumbs />

        <!-- Page Heading -->
        <h1 class="text-4xl font-bold mb-4">Slider</h1>
        <!-- Search Input and Create Button Row -->
        <div class="flex items-center justify-between mb-6">
            <!-- Custom Search Input -->
            <list-search
                v-model="form.search"
                placeholder="Search..."
                class="w-full max-w-md"
                @update:modelValue="fetchData"
            />
            <!-- Create Button -->
            <Link
                v-if="$can('create slider')"
                href="/dashboard/slider/create"
                class="bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded ml-4"
            >
                + Create
            </Link>
        </div>

        <!-- Table Component -->
        <Table
            :columns="columns"
            :data="slider.data"
            :pagination-links="slider.links"
            :sort-by="sortBy"
            :sort-direction="sortDirection"
            :can-edit="$can('update slider')"
            :can-delete="$can('delete slider')"
            :draggable="true"
            @reorder="updateOrder"
            @update:sortBy="toggleSort"
            @update:sortDirection="toggleSortDirection"
            @edit="editRecord"
            @delete="confirmDelete"
        />
    </div>
</template>

<script>
import Table from '@/Dashboard/Components/Table.vue';
import Breadcrumbs from '@/Dashboard/Components/Breadcrumbs.vue';
import ListSearch from '@/Dashboard/Components/ListSearch.vue';
import { Link, router } from '@inertiajs/vue3';

export default {
    components: {
        Breadcrumbs,
        Table,
        ListSearch,
        Link,
    },
    props: {
        filters: Object,
        slider: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search || '',
            },
            columns: [
                { key: 'image', label: 'Image', width: '15%', showImage: true },
                { key: 'id', label: 'ID', width: '15%' },
                { key: 'title', label: 'Title', width: '30%', limit: 40, stripHtml: true },
                { key: 'description', label: 'Description', width: '30%', limit: 40, stripHtml: true },
                { key: 'position', label: 'Position', width: '5%' }
            ],
            sortBy: 'id',
            sortDirection: 'asc',
        };
    },
    methods: {
        toggleSort(column) {
            this.sortBy = column;
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            this.fetchData();
        },
        fetchData(page = 1) {
            this.$inertia.get('/dashboard/slider', {
                ...this.form,
                page,
                sort_by: this.sortBy,
                sort_direction: this.sortDirection,
            }, { preserveState: true });
        },
        editRecord(sliderId) {
            this.$inertia.get(`/dashboard/slider/${sliderId}/edit`);
        },
        confirmDelete(sliderId) {
            this.$inertia.delete(`/dashboard/slider/${sliderId}`, {
                onSuccess: () => {

                },
            });
        },
        async updateOrder(orderedIds) {
            await axios.post(`/dashboard/slider/update-order`, { orderedIds });
        },
    },
};
</script>
