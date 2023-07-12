import { useForm } from "@inertiajs/vue3";

const artForm = useForm({
    title: '',
    description: '',
    hours_spent: '',
    tags: [],
    styles: [],
    images: []
});

// Export the module using the default export
export default {
    artForm,
};
