<template>
    <div v-if="isVisible" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-96 p-6">
            <h3 class="text-lg font-semibold mb-4">{{ title }}</h3>
            <p class="text-gray-700 mb-6">{{ message }}</p>
            <div class="flex justify-end space-x-4">
                <button @click="close" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Cancel
                </button>
                <button @click="confirm" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isVisible: {
            type: Boolean,
            required: true,
        },
        title: {
            type: String,
            default: 'Confirm Action',
        },
        message: {
            type: String,
            required: true,
        },
    },
    emits: ['close', 'confirm'],
    methods: {
        close() {
            this.$emit('close');
        },
        confirm() {
            this.$emit('confirm');
        },
    },
};
</script>

<style scoped>
/* Styles for modal */
</style>
