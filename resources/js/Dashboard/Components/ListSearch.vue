<template>
    <div class="flex items-center w-full">
        <input
            v-model="localValue"
            type="text"
            :placeholder="placeholder"
            class="w-full white placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md pl-3 pr-28 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow"
            @input="updateValue"
        />
        <button
            v-if="showReset && localValue"
            @click="reset"
            class="ml-3 text-gray-500 hover:text-gray-700 focus:text-indigo-500 text-sm"
            type="button"
        >
            Clear
        </button>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: String,
        placeholder: {
            type: String,
            default: 'Search…',
        },
        showReset: {
            type: Boolean,
            default: true,
        },
    },
    emits: ['update:modelValue'],
    data() {
        return {
            localValue: this.modelValue || '',
        };
    },
    watch: {
        modelValue(newValue) {
            this.localValue = newValue;
        },
    },
    methods: {
        updateValue() {
            this.$emit('update:modelValue', this.localValue);
        },
        reset() {
            this.localValue = '';
            this.updateValue();
        },
    },
};
</script>

<style scoped>
/* Add custom styling as needed */
.form-input{
    color: #006666;
}
</style>
