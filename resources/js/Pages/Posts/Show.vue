<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import {computed} from "vue";
import {formatDistance, parseISO} from "date-fns";

const props = defineProps(['post'])

const formattedDate = computed(() => formatDistance(parseISO(props.post.created_at), new Date()));

</script>

<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="font-bold text-2xl">{{ post.title }}</h1>
            <span class="block mt-1 text-sm text-gray-600">{{ formattedDate }} ago by {{ post.user.name }}</span>

            <article class="mt-6">
                <pre class="whitespace-pre-wrap font-sans">{{ post.body }}</pre>
            </article>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>
