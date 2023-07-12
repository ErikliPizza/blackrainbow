<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import Comment from "@/Pages/Artboard/Partials/Show/Comment.vue";
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import CharacterLimit from "@/Components/CharacterLimit.vue";
import {onMounted} from "vue";

let scrollTarget = ref(null);
let processing = ref(false);
let commentPage = ref(1);

const props = defineProps({
    comments: {
        type: Object,
    },
    art_id: {
        required: true
    }
})
const commentForm = useForm({
    content: '',
    art_id: props.art_id
});

function scrollTo(){
    setTimeout(()=>{
        if (scrollTarget.value) {
            scrollTarget.value.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            });
        }
    }, 500);
}
function getComments(next = null) {
    next === false ? commentPage.value-- : commentPage.value++;
    router.reload({
            replace: true,
            onStart: () => {processing.value = true;},
            only: ['comments'],
            data: {
                commentPage: commentPage.value
            },
            onFinish: () => {processing.value = false; scrollTo();}
        },
    );
}

function storeComment() {
    commentForm.post('/comments', {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: () => {
            commentForm.reset();
            commentPage.value = 0;
            getComments();
        }
    });
}

function destroyComment(id) {
    const uri = '/comments/' + id;
    router.delete(uri, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: () => {
            scrollTo();
        },
        onError: () => {
            alert('failed');
        },
    });
}
onMounted(
    ()=>{
        let queryString = window.location.search;
        let urlParams = new URLSearchParams(queryString);

        if( urlParams.has('comments') ){
            commentPage.value = urlParams.get('comments');
            commentPage.value = commentPage.value > props.comments.last_page ? props.comments.last_page : commentPage.value;
        }
    }
)

</script>

<template>
    <div class="mt-8">
        <form @submit.prevent class="mb-4">
            <CharacterLimit :currentLength="commentForm.content.length" :maxLength="250" />
            <TextArea
                maxlength="250"
                :disabled="commentForm.processing"
                v-model="commentForm.content"
                :required="true"
                style="resize: none;"
                class="block w-full text-start"
                rows="5"
                placeholder="hit enter to post a comment"
                @keyup.enter="storeComment"
            />
            <InputError class="mt-2" :message="commentForm.errors.content" />
        </form>

        <div id="comment-list" class="mb-4" v-if="comments" ref="scrollTarget">
            <Comment
                v-for="comment in comments.data"
                :key="comment.id"
                :content="comment.content"
                :author="comment.author"
                :destroy="comment.can.destroy"
                :created_at="comment.created_at"
                @destroy="destroyComment(comment.id)"
            />

            <div class="flex justify-between">
                <div>
                    <primary-button @click="getComments(false)" v-show="commentPage > 1" :disabled="processing">Previous</primary-button>
                </div>
                <div>
                    <primary-button @click="getComments(true)" v-show="commentPage < comments.meta.last_page" :disabled="processing">Next</primary-button>
                </div>
            </div>
        </div>
    </div>
</template>
