<template>
    <div>
        <!-- Breadcrumbs Component -->
        <breadcrumbs />

        <!-- Page Heading -->
        <h1 class="text-4xl font-bold mb-4">Services</h1>

        <!-- Search Input, Per Page Selector, and Create Button Row -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <!-- Custom Search Input -->
                <list-search
                    v-model="form.search"
                    placeholder="Search..."
                    class="w-full max-w-md"
                    @update:modelValue="fetchData"
                />

                <!-- Per Page Selection Dropdown -->
                <div class="flex items-center space-x-4">
                    <label for="perPage">Show:</label>
                    <select v-model="form.per_page" @change="fetchData" class="pl-5 pr-7 border rounded-sm">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="all">All</option>
                    </select>
                </div>
            </div>

            <!-- Create Button -->
            <Link
                v-if="$can('create service')"
                href="/dashboard/services/create"
                class="bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded ml-4"
            >
                + Create
            </Link>
        </div>

        <!-- Table Component -->
        <Table
            :columns="columns"
            :data="services.data"
            :pagination-links="services.links"
            :sort-by="sortBy"
            :sort-direction="sortDirection"
            :draggable="true"
            @reorder="updateOrder"
            :can-edit="$can('update service')"
            :can-delete="$can('delete service')"
            :per-page="form.per_page"
            @update:sortBy="toggleSort"
            @update:sortDirection="toggleSortDirection"
            @edit="editRecord"
            @delete="confirmDelete"
            @update:perPage="updatePerPage"
        />
    </div>
</template>

<script>
import Table from '@/Dashboard/Components/Table.vue';
import Breadcrumbs from '@/Dashboard/Components/Breadcrumbs.vue';
import ListSearch from '@/Dashboard/Components/ListSearch.vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';

export default {
    components: {
        Breadcrumbs,
        Table,
        ListSearch,
        Link,
    },
    props: {
        filters: Object,
        services: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search || '',
                per_page: this.filters.per_page || 10, // Default to 10
            },
            columns: [
                { key: 'id', label: 'ID', width: '15%', sortable: true },
                { key: 'image', label: 'Image', width: '15%', showImage: true },
                { key: 'name', label: 'Name', width: '30%' },
                { key: 'description', label: 'Description', width: '30%', limit: 40, stripHtml: true }
            ],
            sortBy: this.filters.sort_by || 'sort_order',
            sortDirection: this.filters.sort_direction || 'asc',
        };
    },
    methods: {
        toggleSort(column) {
            this.sortBy = column;
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            this.fetchData();
        },
        updatePerPage(perPage) {
            this.form.per_page = perPage;
            this.fetchData();
        },
        fetchData(page = 1) {
            this.$inertia.get('/dashboard/services', {
                ...this.form,
                page,
                sort_by: this.sortBy,
                sort_direction: this.sortDirection,
            }, { preserveState: true });
        },
        editRecord(serviceId) {
            this.$inertia.get(`/dashboard/services/${serviceId}/edit`);
        },
        async updateOrder(orderedIds) {
            await axios.post(`/dashboard/services/update-order`, { orderedIds });
        },
        confirmDelete(serviceId) {
            this.$inertia.delete(`/dashboard/services/${serviceId}`);
        },
    },
};
</script>

<style scoped>
/* Scoped styles for the search and create button */
</style>
