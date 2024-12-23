import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Vue3TouchEvents from 'vue3-touch-events'
import { createPinia } from 'pinia'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const pinia = createPinia()
        const app = createApp({ render: () => h(App, props) })

        app.use(plugin)
           .use(ZiggyVue)
           .use(Vue3TouchEvents)
           .use(pinia)
           .mount(el);

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
