import {router} from "@inertiajs/vue3";
export function useFilterRequest(credentials) {
    const uri = new URL(window.location.href).pathname;

    router.get(
        uri, { search: credentials.search, tag: credentials.tag, style: credentials.style, author: credentials.user, order: credentials.order }, {
            only: ['arts', 'filters'],
            preserveState: false,
            replace: true,
        }
    );
}
