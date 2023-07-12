import { onBeforeUnmount, onMounted, ref } from "vue";
import axios from "axios";
export default function useInfiniteLoading(props) {
    const lastClickedArt = ref(null);
    const url = new URL(window.location.href).pathname;
    const itemName = ref(url);
    onBeforeUnmount(() => {
        if (lastClickedArt.value > 0) {
            sessionStorage.setItem(itemName.value, lastClickedArt.value);
        }
    });

    onMounted(async () => {
        const LastArt = sessionStorage.getItem(itemName.value);
        if (LastArt > 0) {
            lastClickedArt.value = parseInt(LastArt);
            sessionStorage.removeItem(itemName.value);
            load();
        }
    });

    const isArts = ref(props.arts.data);
    const nextPageUrl = ref(props.arts.links.next);
    const loading = ref(false);
    const api = axios.create();

    const load = async ($state) => {
        if (lastClickedArt.value > 0) {
            console.warn('started for' + lastClickedArt.value);
            let clickedArt = lastClickedArt.value;
            let shouldContinueFetching = true;
            let attempts = 0;
            while (
                nextPageUrl.value &&
                !loading.value &&
                shouldContinueFetching && attempts < 60
                ) {
                attempts++;
                loading.value = true;
                try {
                    const response = await api.get(nextPageUrl.value);
                    isArts.value.push(...response.data.data);
                    nextPageUrl.value = response.data.links.next;

                    const foundArt = isArts.value.find(
                        (art) => art.id === lastClickedArt.value
                    );
                    if (foundArt) {
                        shouldContinueFetching = false;
                        lastClickedArt.value = null;
                    }
                } catch (error) {
                    console.error(error);
                }
                loading.value = false;
            }

            setTimeout(() => {
                const element = document.getElementById(clickedArt);
                if (element) {
                    element.scrollIntoView({ behavior: "smooth" });
                }
            }, 300);
        } else {
            if (nextPageUrl.value && !loading.value) {
                loading.value = true;
                try {
                    const response = await api.get(nextPageUrl.value);
                    isArts.value.push(...response.data.data);
                    nextPageUrl.value = response.data.links.next;
                } catch (error) {
                    console.error(error);
                }
                loading.value = false;
            }
        }
    };


    return {
        lastClickedArt,
        isArts,
        load,
    };
}
