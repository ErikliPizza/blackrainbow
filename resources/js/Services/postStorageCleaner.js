import {onMounted} from "vue";
export function postStorageCleaner() {
    onMounted(
        () => {
            document.body.classList.remove('overflow-hidden')

            const url = new URL(window.location.href).pathname;
            const modifiedUrl = url.replace(/\/arts\/\w+$/, '/arts');
            console.log(modifiedUrl);
            switch (modifiedUrl) {
            case '/myArts':
                sessionStorage.removeItem('/likedArts');
                sessionStorage.removeItem('/');
                break;
            case '/likedArts':
                sessionStorage.removeItem('/myArts');
                sessionStorage.removeItem('/');
                break;
            case '/':
                sessionStorage.removeItem('/myArts');
                sessionStorage.removeItem('/likedArts');
                break;
            case '/arts':
                break;
            default:
                sessionStorage.removeItem('/myArts');
                sessionStorage.removeItem('/likedArts');
                sessionStorage.removeItem('/');
                break;
            }
        }
    )
}
