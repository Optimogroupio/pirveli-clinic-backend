<template>
    <div class="flex items-center space-x-2">
        <div
            @click="toggle"
            :class="{ 'bg-primary': value, 'bg-gray-300': !value }"
            class="w-10 h-5 flex items-center rounded-full p-1 cursor-pointer transition-colors duration-300"
        >
            <div
                :class="{ 'translate-x-5': value, 'translate-x-0': !value }"
                class="w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-300"
            ></div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: {
            type: Number,
            default: 0,
        },
        label: {
            type: String,
            default: '',
        }
    },
    emits: ['update:modelValue'],
    computed: {
        value: {
            get() {
                return this.modelValue === 1;
            },
            set(val) {
                this.$emit('update:modelValue', val ? 1 : 0);
            }
        }
    },
    methods: {
        toggle() {
            this.value = !this.value;
        }
    }
};
</script>

<style scoped>
/* Styles for the toggle switch */
</style>
