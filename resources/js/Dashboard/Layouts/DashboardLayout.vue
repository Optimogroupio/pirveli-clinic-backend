<template>
    <div class="dashboard-layout">
        <!-- Sidebar, conditionally rendered based on sidebarVisible state -->
        <aside :class="['sidebar', { 'collapsed': !sidebarVisible }]">
            <Sidebar :sidebar="sidebarVisible" @closeSidebar="toggleSidebar" />
        </aside>

        <!-- Toast Notification -->
        <Toast v-if="$page.props.toast && $page.props.toast.message"
               :message="$page.props.toast.message"
               :type="$page.props.toast.type || 'info'"
               :auto-dismiss="$page.props.toast.autoDismiss || 5"
               :position="$page.props.toast.position || 'bottom-right'"/>

        <!-- Main Content Area -->
        <div :class="['main-content', sidebarVisible ? '' : 'full-width']">
            <Navbar :toggleSidebar="toggleSidebar"/>
            <main class="p-6 pb-10 bg-gray-100">
                <slot/>
            </main>
        </div>
    </div>
</template>

<script setup>
import {usePage} from '@inertiajs/vue3';
import {ref} from 'vue';
import Sidebar from '../Components/Sidebar.vue';
import Navbar from '../Components/Navbar.vue';
import Toast from '@/Dashboard/Components/Toast.vue';

const sidebarVisible = ref(true);

const toggleSidebar = () => {
    sidebarVisible.value = !sidebarVisible.value;
};

const page = usePage();
</script>

<style scoped>
.dashboard-layout {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 260px;
    background-color: #006666;
    transition: transform 0.3s ease;
    transform: translateX(0);
}

.sidebar.collapsed {
    transform: translateX(-100%);
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    z-index: 50;
}

.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    background-color: #f9fafb;
    transition: margin-left 0.3s ease;
}

.full-width {
    margin-left: 0;
    width: 100%;
}

@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        transform: translateX(-100%);
        z-index: 50;
    }

    .sidebar.collapsed {
        transform: translateX(0);
    }

    .main-content {
        margin-left: 0;
        width: 100%;
    }
}
</style>
