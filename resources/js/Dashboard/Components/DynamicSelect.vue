<template>
    <div>
        <Multiselect
            v-model="selectedValue"
            :options="options"
            :multiple="multiple"
            :close-on-select="!multiple"
            :clear-on-select="!multiple"
            :preserve-search="true"
            :label="labelKey"
            :track-by="valueKey"
            :placeholder="multiple ? 'Select options' : 'Select an option'"
            @input="updateSelection"
        />
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';

export default {
    components: { Multiselect },
    props: {
        id: String,
        options: {
            type: Array,
            default: () => []
        },
        selected: {
            type: [Array, String, Number],
            default: () => []
        },
        multiple: {
            type: Boolean,
            default: false
        },
        valueKey: {
            type: String,
            default: 'value'
        },
        labelKey: {
            type: String,
            default: 'label'
        }
    },
    emits: ['update:selected'],
    data() {
        return {
            selectedValue: this.selected
        };
    },
    watch: {
        selected(newValue) {
            this.selectedValue = newValue;
        }
    },
    methods: {
        updateSelection(value) {
            this.$emit('update:selected', value);
        }
    }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
