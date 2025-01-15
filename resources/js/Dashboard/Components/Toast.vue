<template>
    <transition name="fade" appear>
        <div v-if="visible" :class="[toastClasses, positionClasses]" class="fixed p-4 rounded shadow-lg z-50 flex items-center">
            <i :class="iconClasses" class="mr-2"></i>
            <span>{{ message }}</span>
            <button @click="close" class="ml-4 text-white focus:outline-none">✕</button>
        </div>
    </transition>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

export default {
    props: {
        duration: {
            type: Number,
            default: 5000
        }
    },
    setup(props) {
        const visible = ref(false);
        const message = ref('');
        const type = ref('info');
        const autoDismiss = ref(props.duration);
        const position = ref('bottom-right');
        let timeoutId = null;

        const showToast = (toastData) => {
            if (toastData.message) {
                message.value = toastData.message;
                type.value = toastData.type || 'info';
                autoDismiss.value = (toastData.autoDismiss || 5) * 1000;
                position.value = toastData.position || 'bottom-right';
                visible.value = true;

                clearExistingTimeout();
                timeoutId = setTimeout(close, autoDismiss.value);
            }
        };

        const close = () => {
            visible.value = false;
            clearExistingTimeout();
        };

        const clearExistingTimeout = () => {
            if (timeoutId) {
                window.clearTimeout(timeoutId);
                timeoutId = null;
            }
        };

        const handleInertiaResponse = () => {
            const toastData = usePage().props.toast;
            if (toastData && toastData.message) {
                showToast(toastData);
            }
        };

        onMounted(() => {
            router.on('finish', handleInertiaResponse);
        });

        const toastClasses = computed(() => ({
            'bg-green-500 text-white': type.value === 'success',
            'bg-blue-500 text-white': type.value === 'info',
            'bg-yellow-500 text-black': type.value === 'warning',
            'bg-red-500 text-white': type.value === 'danger'
        }));

        const positionClasses = computed(() => {
            switch (position.value) {
                case 'top-left':
                    return 'top-4 left-4';
                case 'top-right':
                    return 'top-4 right-4';
                case 'bottom-left':
                    return 'bottom-4 left-4';
                case 'bottom-right':
                    return 'bottom-4 right-4';
                case 'center':
                    return 'top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2';
                case 'top-center':
                    return 'top-4 left-1/2 transform -translate-x-1/2';
                case 'bottom-center':
                    return 'bottom-4 left-1/2 transform -translate-x-1/2';
                default:
                    return 'bottom-4 right-4'; // default position
            }
        });

        const iconClasses = computed(() => ({
            'fa fa-check-circle': type.value === 'success',
            'fa fa-info-circle': type.value === 'info',
            'fa fa-exclamation-triangle': type.value === 'warning',
            'fa fa-times-circle': type.value === 'danger'
        }));

        return {
            visible,
            message,
            close,
            toastClasses,
            positionClasses,
            iconClasses,
        };
    }
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>
