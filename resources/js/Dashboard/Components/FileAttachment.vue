<template>
    <div>
        <!-- File input with custom styling -->
        <div class="file-input-wrapper">
            <label
                class="flex items-center justify-center w-full h-24 border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-100 transition"
            >
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16v5h10v-5M5 12l7-7 7 7M12 4v12"></path>
                    </svg>
                    <span class="mt-2 text-sm font-medium text-gray-600">{{ labelText }}</span>
                </div>
                <input
                    type="file"
                    :multiple="multiple"
                    class="hidden"
                    @change="handleFileChange"
                    :accept="fileType === 'image' ? 'image/*' : '*'"
                />
            </label>
        </div>

        <!-- Image Preview Section for Existing and New Files -->
        <div v-if="fileType === 'image' && allFiles.length" class="grid grid-cols-3 gap-4 mt-4">
            <div
                v-for="(file, index) in allFiles"
                :key="index"
                class="relative p-2 border rounded-lg shadow-sm bg-white"
            >
                <img :src="file.previewUrl" alt="Preview" class="w-full h-24 object-cover rounded-md" />
                <p class="mt-1 text-xs text-gray-600 text-center truncate">{{ file.name }}</p>
                <button
                    class="absolute top-1 right-1 p-1 w-6 h-6 text-white bg-red-500 rounded-full hover:bg-red-600"
                    @click="removeFile(index)"
                >
                    &times;
                </button>
            </div>
        </div>

        <!-- File List Section for Non-Image Files -->
        <div v-if="fileType === 'file' && allFiles.length" class="mt-4">
            <ul class="space-y-2">
                <li
                    v-for="(file, index) in allFiles"
                    :key="index"
                    class="flex items-center justify-between p-2 bg-gray-100 rounded-lg shadow-sm"
                >
                    <span class="text-sm font-medium text-gray-700 truncate">{{ file.name }}</span>
                    <button
                        class="px-2 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-600"
                        @click="removeFile(index)"
                    >
                        Remove
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        label: { type: String, default: 'File' },
        fileType: { type: String, default: 'file' },
        multiple: { type: Boolean, default: false },
        modelValue: { type: Array, default: () => [] },
        existingFiles: { type: Array, default: () => [] }, // New prop for existing files
    },
    data() {
        return {
            newFiles: [], // Stores newly added files
        };
    },
    computed: {
        labelText() {
            return `Upload ${this.label}${this.multiple ? 's' : ''}`;
        },
        allFiles() {
            // Combine existing files with new files
            return [
                ...this.existingFiles.map(file => ({
                    name: file.name || 'Existing File',
                    previewUrl: file.url, // Existing file URLs
                    isNew: false // Mark existing files as not new
                })),
                ...this.newFiles.map(file => ({
                    ...file,
                    isNew: true // Mark new files as new
                }))
            ];
        },
    },
    methods: {
        handleFileChange(event) {
            const selectedFiles = Array.from(event.target.files);
            const mappedFiles = selectedFiles.map(file => ({
                file,
                name: file.name,
                previewUrl: this.fileType === 'image' ? URL.createObjectURL(file) : null,
            }));

            // Update new files based on `multiple` prop
            this.newFiles = this.multiple ? [...this.newFiles, ...mappedFiles] : mappedFiles.slice(0, 1);

            // Emit only new files for the model update
            this.$emit('update:modelValue', this.newFiles.map(f => f.file));
        },
        removeFile(index) {
            const fileToRemove = this.allFiles[index];

            if (!fileToRemove.isNew) {
                // Emit event to remove an existing file
                this.$emit('removeExistingFile', fileToRemove);
                this.existingFiles.splice(index, 1);
            } else {
                // Remove new file and update modelValue
                this.newFiles.splice(index - this.existingFiles.length, 1);
                this.$emit('update:modelValue', this.newFiles.map(f => f.file));
            }
        },
    },
    beforeUnmount() {
        // Clean up object URLs created for previews of new files
        this.newFiles.forEach(file => {
            if (file.previewUrl) {
                URL.revokeObjectURL(file.previewUrl);
            }
        });
    },
};
</script>

<style scoped>
.file-input-wrapper {
    width: 100%;
    max-width: 200px;
}
</style>
