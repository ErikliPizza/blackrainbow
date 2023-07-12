import {ref, watch} from "vue";
export function useAlphaNumeric(initialValue) {
    const val = ref(initialValue);

    watch(val, (newValue, oldValue) => {
        const alphanumericRegex = /^[a-z0-9ığüşöç]*$/;
        if (!alphanumericRegex.test(newValue)) {
            val.value = oldValue;
        }
    });
    return {
        val
    };
}
