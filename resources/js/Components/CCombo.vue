<template>
    <div class="top-16">
        <Combobox v-model="selectedItems" multiple>
            <div class="relative mt-1">
                <div class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm">
                    <ComboboxInput
                        :placeholder="placeHolder"
                        class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
                        :displayValue="(item) => item.name"
                        @change="query = $event.target.value"
                        @click="handleInputFocus"
                    />
                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2" ref="comboBtn">
                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                    </ComboboxButton>
                </div>
                <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100" leaveTo="opacity-0" @after-leave="query = ''">
                    <ComboboxOptions class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                        <div v-if="filteredItems.length === 0 && query !== ''" class="relative cursor-default select-none py-2 px-4 text-gray-700">
                            Nothing found.
                        </div>

                        <ComboboxOption v-for="item in filteredItems" :key="item.id" :value="item" v-slot="{ selected, active }">
                            <li class="relative cursor-default select-none py-2 pl-10 pr-4" :class="{'bg-teal-600 text-white': active, 'text-gray-900': !active}">
                <span class="block truncate" :class="{'font-medium': selected, 'font-normal': !selected}">
                  {{ item.name }}
                </span>
                                <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3" :class="{'text-white': active, 'text-teal-600': !active}">
                  <CheckIcon class="h-5 w-5" aria-hidden="true" />
                </span>
                            </li>
                        </ComboboxOption>
                    </ComboboxOptions>
                </TransitionRoot>
            </div>
        </Combobox>
    </div>
</template>

<script setup>
import {ref, computed, watch, onMounted} from 'vue'
import { Combobox, ComboboxInput, ComboboxButton, ComboboxOptions, ComboboxOption, TransitionRoot } from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import {debounce} from "lodash-es";

// hooks
onMounted(
    () => {
        if (props.modelValue !== undefined && props.whatAmI !== 'author')
        {
            selectedItems.value = selectedItems.value.concat(props.modelValue.map(name => {
                return {
                    name: name
                };
            }));
        }
        if (props.whatAmI === 'author' && props.modelValue !== undefined && props.modelValue > 0) {
            singleAuthor();
        }

        searchTags();
    }
);
// hooks

// variables
const props = defineProps({
    modelValue: Array,
    whatAmI: {
        type: String,
        required: true
    },
    limit: {
        type: Number,
        default: 3
    }
});
let comboBtn = ref(null);
const handleInputFocus = () => comboBtn.value?.$el.click();
const items = ref([]);
const selectedItems = ref([]);
const placeHolder = ref(props.whatAmI);
let query = ref('');
let emit = defineEmits(['update:modelValue']);
// variables



// functionalities
const searchTags = async () => {
    if(filteredItems.value.length < 3) {
        try {
            const response = await axios.get(`/api/${props.whatAmI}`, { params: { query: query.value } });
            console.log('sended');
            items.value  = response.data;
            items.value = filteredItems.value.filter((item) => {
                const itemNameLower = item.name.toLowerCase();
                return !selectedItems.value.some(
                    (selectedItem) => selectedItem.name.toLowerCase() === itemNameLower
                );
            });
        } catch (error) {
            console.error(error);
        }
    }
};
const singleAuthor = async () => {
    try {
        const response = await axios.get(`/api/single-author`, { params: { query: props.modelValue } });
        selectedItems.value = selectedItems.value.concat({
            user_id: response.data.user_id,
            name: response.data.name
        });
    } catch (error) {
        console.error(error);
    }
};
const filteredItems = computed(() => {
    const selected = selectedItems.value.filter(item => item.name.toLowerCase().includes(query.value.toLowerCase()));
    const unselected = items.value.filter(item => !selectedItems.value.includes(item) && item.name.toLowerCase().includes(query.value.toLowerCase()));
    if (selectedItems.value.length === props.limit) {
        return selected;
    }

    return selected.concat(unselected);
});

// functionalities


// watchers
watch(query, debounce(function () {
    searchTags();
}, 300));

watch(selectedItems, (newValue) => {
    if (props.whatAmI === 'author')
    {
        emit('update:modelValue',  newValue.map(item => item.user_id));
    } else {
        emit('update:modelValue',  newValue.map(item => item.name));
    }
    if (newValue.length >= props.limit) {
        placeHolder.value = `${newValue.length} selected (max) ${props.whatAmI}`;
    } else if (newValue.length > 0) {
        placeHolder.value = `${newValue.length} selected ${props.whatAmI}`;
    } else {
        placeHolder.value = props.whatAmI;
    }
});
// watchers

</script>
