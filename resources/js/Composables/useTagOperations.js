import { ref, watch } from 'vue';
export function useTagOperations(tags, styles, artForm) {
    const allTags = ref(tags);
    function swapTag(tagName) {
        const existAll = existInAll(tagName);
        const existForm = existInForm(tagName)

        if (!existForm) {
            if (existAll) {
                artForm.tags.push({ name: tagName });
            }
        } else {
            artForm.tags = artForm.tags.filter(tag => tag.name !== tagName);
        }
    }

    function existInAll(tagName) {
        return allTags.value.some(tag => tag.name === tagName);
    }

    function existInForm(tagName) {
        return artForm.tags.some(tag => tag.name === tagName);
    }
    function addCustomTag(tagName) {
        const trimmedTagName = tagName.trim();

        if (trimmedTagName !== '' && !existInForm(trimmedTagName)) {
            const newTag = { name: trimmedTagName };
            artForm.tags.push(newTag);
        }
    }


    const selectedStyles = ref(artForm.styles);
    const allStyles = ref(styles);
    watch(selectedStyles, updateStyles);
    function updateStyles() {
        const selectedStyleIds = selectedStyles.value;
        artForm.styles = allStyles.value.filter((style) => selectedStyleIds.includes(style.id)).map((style) => style.id);
    }

    return {
        selectedStyles,
        swapTag,
        existInForm,
        addCustomTag,
        allTags
    };
}
