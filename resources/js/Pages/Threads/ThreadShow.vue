<template>
    <authenticated-layout>
        <div class="grid grid-cols-1 flex justify-center mx-3">
            <div class="card w-full bg-base-100 shadow-xl mt-5">
                <div class="card-body">
                    <div>
                        <div class="card-actions justify-center">
                            <h2 class="card-title">{{ thread.title }}</h2>
                        </div>
                        <div class="card-actions justify-center">
                            <h3>{{ thread.body }}</h3>
                        </div>
                        <div class="grid grid-cols-2">
                            <!-- vote component -->
                            <vote-component :voteableModel="'App\\Models\\Thread'" :voteableId="thread.id" />
                            <div class="flex justify-end mt-2">
                                <h6><span style="color:blue">author: </span>{{ thread.user.name }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- comment input -->
        <div v-if="$page.props.auth.user">
            <div class="flex justify-end m-3">
                <textarea v-model="form.body" class="textarea textarea-primary w-96 textarea-lg"
                    placeholder="Write your comment"></textarea>
            </div>
            <div class="flex justify-end mx-3">
                <button @click="addCommentToThread" class="btn btn-outline btn-primary btn-sm">Save</button>
            </div>
        </div>
        <!-- comments -->
        <div v-for="comment in threadComments" :key="comment.id">
            <div>
                <comment-card :model="thread" :comment="comment" :commentVoteResult="commentVoteResult" />
            </div>
        </div>
    </authenticated-layout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CommentCard from "@/Pages/Threads/Partials/CommentCard.vue";
import VoteComponent from "@/Pages/Threads/Partials/VoteComponent.vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { onMounted, ref } from "vue";

const props = defineProps({
    thread: Object,
    // comments: Object,
    commentVoteResult: Number,
    threadVoteResult: Number,
})

let threadComments = ref({});

const form = useForm({
    body: '',
})

const addCommentToThread = async () => {
    await axios.post(route('comments.store', {
        commentableModel: 'App\\Models\\Thread',
        commentableId: props.thread.id,
    }), form)
        .then(getThreadComments())
}

const getThreadComments = async () => {
    await axios.get(route('comments.comments', {
        commentableId: props.thread.id,
    }))
        .then((response) => {
            threadComments.value = response.data
        })
}

onMounted(() => {
    getThreadComments()
})

// const addComment = () => {
//     form.post(route('comments.store'), {
//         preserveScroll: true,
//         onSuccess: () => {
//             form.reset('body')
//         }
//     })
// }
</script>