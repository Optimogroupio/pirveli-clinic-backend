<template>
    <div class="navbar flex items-center justify-between p-4 bg-white shadow">
        <div class="flex items-center">
            <button
                type="button"
                class="text-gray-800 hover:text-gray-600 focus:outline-none focus:ring-0 mr-3"
                @click="toggleSidebar"
            >
                <i class="fa-solid fa-bars-staggered"></i>
                <span class="sr-only">Toggle Sidebar</span>
            </button>

            <!-- Search Input and Button -->
            <div class="w-full max-w-sm min-w-[200px]">
                <div class="relative">
                    <input
                        class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md pl-3 pr-28 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow"
                        placeholder="Search..."
                    />
                    <button
                        class="absolute top-1 right-1 flex items-center rounded bg-primary py-1 px-3 border text-center text-sm text-white transition shadow-sm hover:bg-primary-dark focus:bg-indigo-600"
                        type="button"
                    >
                        <i class="fa fa-search mr-1"></i>
                        Search
                    </button>
                </div>
            </div>
        </div>

        <!-- User Dropdown Menu -->
        <div class="flex items-center space-x-4">
            <div class="relative">
                <div @click="dropdown = !dropdown" class="flex items-center cursor-pointer">
                    <span class="mr-2 text-gray-700 font-semibold">{{ user.first_name + ' ' + user.last_name }}</span>
                </div>
                <div
                    v-show="dropdown"
                    @click.away="dropdown = false"
                    class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg py-1 z-20"
                >
                    <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Profile</a>
                    <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Settings</a>
                    <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Logout</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const dropdown = ref(false);

defineProps(['toggleSidebar']);
const page = usePage();
const user = computed(() => page.props.dashboard.user);
</script>

<style scoped>
.navbar {
    border-bottom: 1px solid #e5e7eb;
}
</style>
