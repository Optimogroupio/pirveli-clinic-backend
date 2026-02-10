<template>
    <nav aria-label="breadcrumb" class="flex space-x-2 mb-4">
        <!-- Dashboard Link -->
        <Link href="/dashboard" class="text-lg flex items-center text-primary hover:text-primary-dark">
            Dashboard
        </Link>

        <!-- Dynamic segments -->
        <template v-if="resourceName">
            <span class="text-lg text-gray-400">/</span>

            <!-- Resource name (Doctors, Services, etc.) -->
            <template v-if="isListPage">
                <!-- On list page: text only -->
                <span class="capitalize text-gray-600 text-lg align-middle">
                    {{ resourceName }}
                </span>
            </template>
            <template v-else>
                <!-- On create/edit/view page: clickable link -->
                <Link
                    :href="resourceUrl"
                    class="text-lg text-primary hover:text-primary-dark capitalize flex items-center"
                >
                    {{ resourceName }}
                </Link>

                <!-- Separator if we have recordName -->
                <span v-if="recordName" class="text-lg text-gray-400">/</span>

                <!-- Record name (for edit/view pages) -->
                <span v-if="recordName" class="capitalize text-gray-600 text-lg align-middle">
                    {{ recordName }}
                </span>

                <!-- "Create" text (for create pages) -->
                <span v-if="isCreatePage && !recordName" class="capitalize text-gray-600 text-lg align-middle">
                    Create
                </span>
            </template>
        </template>
    </nav>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: { Link },
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
        currentPath() {
            return window.location.pathname;
        },

        pathSegments() {
            return this.currentPath.split('/').filter(segment => segment);
        },

        resourceSegment() {
            const segments = this.pathSegments;

            // Find the resource name (doctors, services, etc.)
            for (let i = 0; i < segments.length; i++) {
                const segment = segments[i];
                if (segment !== 'dashboard' && !this.isNumeric(segment) && !['create', 'edit'].includes(segment)) {
                    return segment;
                }
            }

            return '';
        },

        resourceName() {
            if (!this.resourceSegment) return '';

            // Capitalize and handle plurals
            const word = this.resourceSegment;
            const singularMap = {
                'doctors': 'Doctors',
                'services': 'Services',
                'news': 'News',
                'pages': 'Pages'
            };

            return singularMap[word] || word.charAt(0).toUpperCase() + word.slice(1);
        },

        resourceUrl() {
            return this.resourceSegment ? `/dashboard/${this.resourceSegment}` : '';
        },

        isListPage() {
            const segments = this.pathSegments;

            // Check if we're on a list page (e.g., /dashboard/doctors)
            // A list page has exactly 2 segments: dashboard + resource
            // OR it has resource + ?query parameters
            if (segments.length === 2 && segments[0] === 'dashboard') {
                return true;
            }

            // Also check if we don't have create/edit in the URL
            return !segments.includes('create') && !segments.includes('edit') && !this.isNumeric(segments[segments.length - 1]);
        },

        isCreatePage() {
            return this.pathSegments.includes('create');
        }
    },
    methods: {
        isNumeric(str) {
            return /^\d+$/.test(str);
        }
    }
};
</script>

<style scoped>
nav {
    font-size: 0.875rem;
}
</style>
