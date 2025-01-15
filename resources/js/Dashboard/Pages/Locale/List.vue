<template>
    <div>
        <!-- Breadcrumbs Component -->
        <breadcrumbs />

        <!-- Page Heading -->
        <h1 class="text-4xl font-bold mb-4">Locales</h1>
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
                v-if="$can('create locale')"
                href="/dashboard/locales/create"
                class="bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded ml-4"
            >
                + Create
            </Link>
        </div>

        <!-- Table Component -->
        <Table
            :columns="columns"
            :data="locales.data"
            :pagination-links="locales.links"
            :sort-by="sortBy"
            :sort-direction="sortDirection"
            :can-edit="$can('update locale')"
            :can-delete="$can('delete locale')"
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
        locales: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search || '',
            },
            columns: [
                { key: 'id', label: 'ID', width: '15%' },
                { key: 'name', label: 'Name', width: '30%' },
                { key: 'code', label: 'Code', width: '30%' },
                { key: 'is_default', label: 'Is default', width: '30%' }
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
            this.$inertia.get('/dashboard/locales', {
                ...this.form,
                page,
                sort_by: this.sortBy,
                sort_direction: this.sortDirection,
            }, { preserveState: true });
        },
        editRecord(localeId) {
            this.$inertia.get(`/dashboard/locales/${localeId}/edit`);
        },
        confirmDelete(localeId) {
            this.$inertia.delete(`/dashboard/locales/${localeId}`, {
                onSuccess: () => {

                },
            });
        },
    },
};
</script>

<style scoped>
/* Scoped styles for the search and create button */
</style>
