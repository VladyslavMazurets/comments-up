<template>
  <form @submit.prevent="publishComment" class="flex w-2/5 flex-col gap-3">
    <label for="username" class="text-lg font-semibold leading-3"
      >Add your name
    </label>
    <input
      id="username"
      v-model="newCommentName"
      type="name"
      autocomplete="name"
      class="h-10 px-1"
      required
    />
    <label for="comment-area" class="text-lg font-semibold leading-3"
      >Add your comment
    </label>
    <textarea
      v-model="newCommentContent"
      id="comment-area"
      name="comment"
      maxlength="200"
      rows="4"
      class="m-0 resize-none p-1"
      required
    ></textarea>
    <button type="submit" class="bg-sky-400 px-4 py-2 text-lg font-semibold">
      Submit
    </button>
  </form>
</template>

<script setup lang="ts">
  import dayjs from "dayjs";
  import { ref } from "vue";

  import { useSubmitComment } from "../hooks/useCommentActions.vue";

  const { submitComment, isSubmitting, errorMessage } = useSubmitComment();

  const emit = defineEmits(["fetchData"]);
  const newCommentName = ref("");
  const newCommentContent = ref("");

  const publishComment = async () => {
    if (newCommentContent.value.trim() === "") return;

    try {
      const postId = 64;
      await submitComment(postId, {
        name: newCommentName.value,
        content: newCommentContent.value,
        date: dayjs().toISOString(),
      });
      emit("fetchData");
      newCommentContent.value = "";
      newCommentName.value = "";
    } catch (error) {
      console.error("Error submitting comment:", error);
    }
  };
</script>
