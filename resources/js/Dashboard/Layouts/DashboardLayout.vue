<template>
    <div :class="['dashboard-layout', { 'sidebar-open': sidebarVisible && isMobile }]">
        <!-- Sidebar -->
        <aside :class="['sidebar', { collapsed: !sidebarVisible && isMobile }]">
            <Sidebar :sidebar="sidebarVisible" @closeSidebar="toggleSidebar" />
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <Navbar :toggleSidebar="toggleSidebar" />
            <main class="p-6 pb-10 bg-gray-100">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Sidebar from '../Components/Sidebar.vue';
import Navbar from '../Components/Navbar.vue';
import Toast from '@/Dashboard/Components/Toast.vue';
import { usePage } from '@inertiajs/vue3';

const sidebarVisible = ref(window.innerWidth >= 1024);
const isMobile = ref(window.innerWidth < 1024);

const toggleSidebar = () => {
    sidebarVisible.value = !sidebarVisible.value;
};

onMounted(() => {
    window.addEventListener('resize', () => {
        isMobile.value = window.innerWidth < 1024;
        if (!isMobile.value) {
            sidebarVisible.value = true;
        }
    });
});
</script>

<style scoped>
html, body, #app {
    height: 100%;
    margin: 0;
}

.dashboard-layout {
    display: flex;
    min-height: 100vh;
    transition: all 0.3s ease;
    overflow-x: hidden;
}

/* Sidebar */
.sidebar {
    width: 260px;
    background-color: #006666;
    transition: transform 0.3s ease;
    height: 100vh;
}

.sidebar.collapsed {
    transform: translateX(-100%);
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    z-index: 50;
}

/* Main */
.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    background-color: #f9fafb;
    transition: margin-left 0.3s ease, transform 0.3s ease;
}

/* Responsive */
@media (max-width: 1023px) {
    .dashboard-layout {
        flex-direction: row;
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 50;
        transform: translateX(-100%);
    }

    .sidebar-open .sidebar {
        transform: translateX(0);
    }

    .main-content {
        transform: translateX(0);
        width: 100%;
    }

    .sidebar-open .main-content {
        transform: translateX(260px);
    }
}
</style>
