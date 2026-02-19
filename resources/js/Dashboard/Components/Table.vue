<template>
    <div class="table-wrapper">
        <!-- Delete Selected Button (outside the table) -->
        <div v-if="selectedItems.length" class="mb-4">
            <button @click="confirmDeleteSelected" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Delete Selected ({{ selectedItems.length }})
            </button>
        </div>

        <!-- Table with dynamic headers and draggable rows if enabled -->
        <div class="table-container bg-white rounded-md shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                    <tr class="text-left font-bold">
                        <!-- Checkbox for selecting all -->
                        <th class="pb-4 pt-6 px-6">
                            <input
                                type="checkbox"
                                :checked="allSelected"
                                @change="toggleSelectAll"
                            />
                        </th>
                        <th v-for="column in columns" :key="column.key" :style="{ width: column.width || 'auto' }"
                            class="pb-4 pt-6 px-6" :class="{ 'cursor-pointer': column.sortable }" @click="column.sortable ? toggleSort(column.key) : null">
                            {{ column.label }}
                            <i v-if="column.sortable" :class="getSortIconClass(column.key)" class="ml-2"></i>
                        </th>
                        <th v-if="hasActions" class="pb-4 pt-6 px-6">Actions</th>
                    </tr>
                    </thead>
                    <!-- No Records Message -->
                    <tbody v-if="displayData.length === 0">
                    <tr>
                        <td :colspan="columns.length + (hasActions ? 2 : 1)" class="text-center py-4 text-gray-500">
                            No records found
                        </td>
                    </tr>
                    </tbody>
                    <!-- Draggable rows if draggable is true -->
                    <draggable
                        v-else-if="draggable"
                        v-model="displayData"
                        tag="tbody"
                        @end="onDragEnd"
                        item-key="id"
                    >
                        <template #item="{ element: row }">
                            <tr :key="row.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                                <td class="border-t px-6 py-4">
                                    <input type="checkbox" :value="row.id" v-model="selectedItems"/>
                                </td>
                                <td v-for="column in columns" :key="column.key" class="border-t px-6 py-4">
                                    <img v-if="row[column.key]?.url" :src="row[column.key]?.url" :alt="row[column.key]?.file_name || 'Image'" class="w-24 h-24 rounded object-cover">
                                    <span v-else>{{ formatColumnValue(row[column.key], column) }}</span>
                                </td>
                                <td v-if="hasActions" class="border-t px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button v-if="canEdit" @click="$emit('edit', row.id)"
                                                class="flex items-center text-white bg-primary hover:bg-primary-dark px-4 py-2 rounded">
                                            <i class="fa fa-edit mr-2"></i> Edit
                                        </button>
                                        <button v-if="canDelete" @click="confirmDelete(row.id)"
                                                class="flex items-center text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded">
                                            <i class="fa fa-trash mr-2"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </draggable>
                    <!-- Static rows when draggable is false -->
                    <tbody v-else>
                    <tr v-for="row in displayData" :key="row.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t px-6 py-4">
                            <input type="checkbox" :value="row.id" v-model="selectedItems"/>
                        </td>
                        <td v-for="column in columns" :key="column.key" class="border-t px-6 py-4">
                            <img v-if="row[column.key]?.url" :src="row[column.key]?.url" :alt="row[column.key]?.file_name || 'Image'" class="w-24 h-24 rounded object-cover">
                            <span v-else>{{ formatColumnValue(row[column.key], column) }}</span>
                        </td>
                        <td v-if="hasActions" class="border-t px-6 py-4">
                            <div class="flex space-x-2">
                                <button v-if="canEdit" @click="$emit('edit', row.id)"
                                        class="flex items-center text-white bg-primary hover:bg-primary-dark px-4 py-2 rounded">
                                    <i class="fa fa-edit mr-2"></i> Edit
                                </button>
                                <button v-if="canDelete" @click="confirmDelete(row.id)"
                                        class="flex items-center text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded">
                                    <i class="fa fa-trash mr-2"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Controls -->
        <div v-if="paginated && paginationLinks && paginationLinks.length > 0" class="mt-10">
            <pagination :links="paginationLinks"/>
        </div>

        <!-- Delete Confirmation Modal -->
        <confirm-modal
            v-if="isModalVisible"
            :is-visible="isModalVisible"
            :title="modalTitle"
            :message="modalMessage"
            @close="closeModal"
            @confirm="confirmDeleteAction"
        />
    </div>
</template>

<script>
import Pagination from '@/Dashboard/Components/Pagination.vue';
import ConfirmModal from '@/Dashboard/Components/ConfirmModal.vue';
import draggable from 'vuedraggable';

export default {
    name: 'Table',
    components: {
        Pagination,
        ConfirmModal,
        draggable,
    },
    props: {
        columns: {
            type: Array,
            required: true,
        },
        data: {
            type: Array,
            required: true,
        },
        paginationLinks: {
            type: Array,
            default: null,
        },
        sortBy: {
            type: String,
            default: null,
        },
        sortDirection: {
            type: String,
            default: 'asc',
        },
        canEdit: {
            type: Boolean,
            default: false,
        },
        canDelete: {
            type: Boolean,
            default: false,
        },
        paginated: {
            type: Boolean,
            default: true,
        },
        draggable: {
            type: Boolean,
            default: false,
        },
        showCheckbox: {
            type: Boolean,
            default: true,
        }
    },
    emits: ['edit', 'delete', 'delete-multiple', 'reorder', 'update:sortBy', 'update:sortDirection'],
    data() {
        return {
            isModalVisible: false,
            itemToDelete: null,
            selectedItems: [],
            isMultipleDelete: false,
            displayData: [],
        };
    },
    watch: {
        data: {
            immediate: true,
            handler(newData) {
                this.updateDisplayData(newData);
                // Clear selected items when data changes
                this.selectedItems = [];
            },
        },
    },
    computed: {
        hasActions() {
            return this.canEdit || this.canDelete;
        },
        allSelected() {
            return this.displayData.length > 0 && this.selectedItems.length === this.displayData.length;
        },
        modalTitle() {
            return this.isMultipleDelete ? "Confirm Multiple Deletion" : "Confirm Deletion";
        },
        modalMessage() {
            return this.isMultipleDelete
                ? `Are you sure you want to delete ${this.selectedItems.length} selected item(s)?`
                : "Are you sure you want to delete this item?";
        },
    },
    methods: {
        updateDisplayData(newData) {
            if (this.paginated && this.paginationLinks) {
                // If paginated, use the data as is (it should already be paginated from the server)
                this.displayData = [...newData];
            } else {
                this.displayData = [...newData];
            }
        },
        toggleSort(columnKey) {
            const newDirection = this.sortBy === columnKey ? (this.sortDirection === 'asc' ? 'desc' : 'asc') : 'asc';
            this.$emit('update:sortBy', columnKey);
            this.$emit('update:sortDirection', newDirection);
        },
        getSortIconClass(column) {
            if (this.sortBy === column) {
                return this.sortDirection === 'asc' ? 'fa fa-sort-up' : 'fa fa-sort-down';
            }
            return 'fa fa-sort';
        },
        confirmDelete(id) {
            this.isMultipleDelete = false;
            this.itemToDelete = id;
            this.isModalVisible = true;
        },
        confirmDeleteSelected() {
            if (this.selectedItems.length === 0) {
                return;
            }
            this.isMultipleDelete = true;
            this.isModalVisible = true;
        },
        closeModal() {
            this.isModalVisible = false;
            this.itemToDelete = null;
        },
        confirmDeleteAction() {
            if (this.isMultipleDelete) {
                // Emit the event with the selected IDs
                this.$emit('delete-multiple', [...this.selectedItems]);
                this.selectedItems = [];
            } else {
                this.$emit('delete', this.itemToDelete);
            }
            this.closeModal();
        },
        formatColumnValue(value, column) {
            if (value === null || value === undefined) {
                return 'N/A';
            }

            if (Array.isArray(value)) {
                if (value.length === 0) {
                    return 'N/A';
                }
                const attribute = column.relationAttribute || 'name';
                return value.map(item => {
                    if (typeof item === 'object' && item !== null) {
                        return item[attribute] || 'N/A';
                    }
                    return item;
                }).join(', ');
            }

            if (typeof value === 'object' && value !== null) {
                // Handle relationship objects
                if (column.relationAttribute && value[column.relationAttribute]) {
                    return value[column.relationAttribute];
                }
                return JSON.stringify(value);
            }

            if (column.stripHtml && typeof value === 'string') {
                const parser = new DOMParser();
                const parsedDoc = parser.parseFromString(value, 'text/html');
                value = parsedDoc.body.textContent || '';
            }

            if (column.limit && typeof value === 'string' && value.length > column.limit) {
                value = value.slice(0, column.limit) + '...';
            }

            return value;
        },
        toggleSelectAll(event) {
            if (event.target.checked) {
                this.selectedItems = this.displayData.map(row => row.id);
            } else {
                this.selectedItems = [];
            }
        },
        onDragEnd() {
            const reorderedData = this.displayData.map((item, index) => ({
                id: item.id,
                order: index + 1,
            }));
            this.$emit('reorder', reorderedData);
        },
    },
};
</script>

<style scoped>
.table-container {
    border-radius: 8px;
    overflow: hidden;
}

button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

button i {
    margin-right: 0.5rem;
}

.fa-sort-up, .fa-sort-down, .fa-sort {
    margin-left: 0.5rem;
}

.cursor-pointer {
    cursor: pointer;
}

.object-cover {
    object-fit: cover;
}
</style>
