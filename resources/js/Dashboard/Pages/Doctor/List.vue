<template>
    <div>
        <!-- Breadcrumbs Component -->
        <breadcrumbs />

        <!-- Page Heading -->
        <h1 class="text-4xl font-bold mb-4">Doctors</h1>

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
                v-if="$can('create doctor')"
                href="/dashboard/doctors/create"
                class="bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded ml-4"
            >
                + Create
            </Link>
        </div>

        <!-- Table Component -->
        <Table
            :columns="columns"
            :data="doctors.data"
            :pagination-links="doctors.links"
            :sort-by="sortBy"
            :sort-direction="sortDirection"
            :draggable="true"
            @reorder="updateOrder"
            :can-edit="$can('update doctor')"
            :can-delete="$can('delete doctor')"
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

export default {
    components: {
        Breadcrumbs,
        Table,
        ListSearch,
        Link,
    },
    props: {
        filters: Object,
        doctors: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search || '',
                per_page: this.filters.per_page || 10,
            },
            columns: [
                { key: 'id', label: 'ID', width: '15%' },
                { key: 'image', label: 'Image', width: '15%', showImage: true },
                { key: 'full_name', label: 'Full Name', width: '30%' },
                { key: 'position', label: 'Position', width: '30%' }
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
            this.$inertia.get('/dashboard/doctors', {
                ...this.form,
                page,
                sort_by: this.sortBy,
                sort_direction: this.sortDirection,
            }, { preserveState: true });
        },
        editRecord(doctorId) {
            this.$inertia.get(`/dashboard/doctors/${doctorId}/edit`);
        },
        async updateOrder(orderedIds) {
            await axios.post(`/dashboard/doctors/update-order`, { orderedIds });
        },
        confirmDelete(doctorId) {
            this.$inertia.delete(`/dashboard/doctors/${doctorId}`, {
                onSuccess: () => {

                },
            });
        },
    },
};
</script>

<style scoped>
/* Scoped styles for the search, per-page selector, and create button */
</style>
