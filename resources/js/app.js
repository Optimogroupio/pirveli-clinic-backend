import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Link } from '@inertiajs/vue3';
import { can } from './can';
import Toast from './Dashboard/Components/Toast.vue';

import WebLayout from './Web/Layouts/AppLayout.vue';
import DashboardLayout from './Dashboard/Layouts/DashboardLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: title => title ? `${title} - Ping CRM` : `${appName}`,
    resolve: async (name) => {
        const isDashboardRoute = window.location.pathname.startsWith('/dashboard');
        const basePath = isDashboardRoute ? './Dashboard/Pages' : './Web/Pages';
        const pagePath = `${basePath}/${name}.vue`;

        const webPages = import.meta.glob('./Web/Pages/**/*.vue');
        const dashboardPages = import.meta.glob('./Dashboard/Pages/**/*.vue');
        const pages = isDashboardRoute ? dashboardPages : webPages;

        const page = await resolvePageComponent(pagePath, pages);
        const pathname = window.location.pathname;
        const isAuthPage = pathname === '/dashboard/login';

        if(!isAuthPage){
            page.default.layout = page.default.layout || (isDashboardRoute ? DashboardLayout :  WebLayout);
        }

        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.config.globalProperties.$can = can;
        app.component('Toast', Toast);
        app.component('Link', Link);
        app.use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
