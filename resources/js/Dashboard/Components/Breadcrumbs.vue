<template>
    <nav aria-label="breadcrumb" class="flex space-x-2 mb-4">
        <!-- Dashboard Link -->
        <Link href="/dashboard" class="text-lg flex items-center text-primary hover:text-primary-dark">
            Dashboard
        </Link>

        <!-- Breadcrumb Separator -->
        <span class="text-lg text-gray-400">/</span>

        <!-- "Doctors" Link if present in URL -->
        <Link
            v-if="hasDoctorsSegment"
            href="/dashboard/doctors"
            class="text-lg text-primary hover:text-primary-dark capitalize flex items-center"
        >
            Doctors
        </Link>

        <!-- Separator after Doctors -->
        <span v-if="hasDoctorsSegment && (recordName || lastItemUrl)" class="text-lg text-gray-400">/</span>

        <!-- Last segment as a link if lastItemUrl is provided -->
        <Link
            v-if="lastItemUrl && recordName"
            :href="lastItemUrl"
            class="text-lg text-primary hover:text-primary-dark capitalize flex items-center"
        >
            {{ recordName }}
        </Link>

        <!-- Last segment as plain text if no lastItemUrl is provided -->
        <span v-else-if="recordName" class="capitalize text-gray-600 text-lg align-middle">
            {{ recordName }}
        </span>
    </nav>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {Link},
    props: {
        recordName: {
            type: String,
            default: ''
        },
        lastItemUrl: {
            type: String,
            default: null
        }
    },
    computed: {
        hasDoctorsSegment() {
            // Check if "doctors" is in the URL path (ignoring numeric segments)
            return this.routePath.includes("doctors");
        },
        routePath() {
            // Get path segments, removing the initial /dashboard segment
            return window.location.pathname.split('/').slice(2);
        }
    }
};
</script>

<style scoped>
/* Styles for breadcrumbs */
nav {
    font-size: 0.875rem; /* Tailwind's text-sm */
}
</style>
