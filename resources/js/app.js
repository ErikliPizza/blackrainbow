import './bootstrap';
import '../css/app.css';
import 'vue3-carousel/dist/carousel.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
            .then((component) => {
                component.default.layout = component.default.layout || AuthenticatedLayout; // Set the default layout
                return component;
            });
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(Toast, {
                // You can set your default options here
            });

        app.component('Head', Head); // Register the Head component globally
        app.component('Link', Link); // Register the Link component globally

        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
