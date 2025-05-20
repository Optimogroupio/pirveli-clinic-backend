<template>
    <nav
        class="sidebar-nav p-6 text-white space-y-4 transition-transform duration-300"
        :class="{ '-translate-x-full': !sidebar, 'translate-x-0': sidebar }"
    >
        <img src="/images/optimogroup-logo.svg" alt="Optimo Group Logo" class="w-32 h-auto mb-10" />

        <ul>
            <li>
                <Link
                    href="/dashboard/home"
                    :class="['sidebar-link', { 'active': $page.url === '/dashboard/home' }]"
                >
                    <i class="fa fa-tachometer-alt mr-2"></i>
                    Dashboard
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/appointments"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/appointments') }]"
                >
                    <i class="fa-regular fa-file mr-2"></i>
                    Appointments
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/service_categories"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/service_categories') }]"
                >
                    <i class="fa fa-list mr-2"></i>
                    Service Categories
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/services"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/services') }]"
                >
                    <i class="fa fa-list mr-2"></i>
                    Services
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/doctors"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/doctors') }]"
                >
                    <i class="fa fa-hospital-user mr-2"></i>
                    Doctos
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/specialties"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/specialties') }]"
                >
                    <i class="fa fa-list mr-2"></i>
                    Specialties
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/news"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/news') }]"
                >
                    <i class="fa-solid fa-newspaper mr-2"></i>
                    Blog
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/pages"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/pages') }]"
                >
                    <i class="fa-regular fa-file mr-2"></i>
                    Pages
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/slider"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/slider') }]"
                >
                    <i class="fa-solid fa-image mr-2"></i>
                    Slider
                </Link>
            </li>
            <li class="hidden">
                <Link
                    href="/dashboard/settings"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/settings') }]"
                >
                    <i class="fa-solid fa-gears mr-2"></i>
                    Settings
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/languages"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/languages') }]"
                >
                    <i class="fa fa-earth-americas mr-2"></i>
                    Languages
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/locales"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/locales') }]"
                >
                    <i class="fa fa-font mr-2"></i>
                    Locales
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/administrators"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/administrators') }]"
                >
                    <i class="fa fa-users mr-2"></i>
                    Administrators
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/roles"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/roles') }]"
                >
                    <i class="fa-solid fa-user-lock mr-2"></i>
                    Roles
                </Link>
            </li>
            <li>
                <Link
                    href="/dashboard/permissions"
                    :class="['sidebar-link', { 'active': $page.url.startsWith('/dashboard/permissions') }]"
                >
                    <i class="fa-solid fa-user-shield mr-2"></i>
                    Permissions
                </Link>
            </li>
        </ul>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { defineProps, defineEmits } from 'vue';

const props = defineProps(['sidebar']);
const emit = defineEmits(['closeSidebar']);

// Close sidebar when a link is clicked (useful on mobile)
const closeSidebar = () => {
    emit('closeSidebar');
};
</script>

<style scoped>
.sidebar-nav {
    height: 100vh;
    width: 260px;
    padding: 1.5rem;
    background-color: #006666;
    color: white;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    position: relative;
    z-index: 60;
}

.sidebar-link {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    color: #fff;
    border-radius: 0.375rem;
    transition: background-color 0.2s;
    text-decoration: none;
    margin-bottom: 5px;
}

.sidebar-link:hover {
    background-color: #2d3748;
}

.sidebar-link.active {
    background-color: #4a5568;
    font-weight: bold;
}
.sidebar-nav {
    min-height: 100%;
    background-color: #006666;
}

@media (max-width: 1023px) {
    .sidebar-nav {
        position: fixed;
        top: 0;
        bottom: 0;
        height: 100vh;
        overflow-y: auto;
    }
}

</style>

