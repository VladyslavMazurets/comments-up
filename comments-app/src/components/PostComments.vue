<template>
  <div>
    <div class="flex flex-col gap-6 bg-sky-50 px-6 py-8">
      <h1 class="text-4xl font-bold">Comments</h1>

      <NotFound v-if="!comments.length" />

      <div class="flex flex-col gap-4">
        <div
          v-if="comments.length > 0"
          v-for="(comment, key) in comments"
          :key="key"
        >
          <div
            class="flex w-2/5 flex-col gap-2 rounded-3xl border border-emerald-50 bg-emerald-100 px-8 py-3"
          >
            <div class="flex flex-col">
              <span class="text-lg leading-6">
                {{ comment.author }}
              </span>
              <a
                :href="`mailto:${comment.email}`"
                target="_blank"
                class="text-sm text-gray-700"
                >{{ comment.email }}
              </a>
            </div>

            <div class="flex flex-col gap-6">
              <div>
                <span class="text-base">
                  {{
                    isExpanded[key]
                      ? comment.content
                      : truncatedText(comment.content)
                  }}
                </span>
                <button
                  class="ml-2 text-sm text-sky-500 hover:underline"
                  v-if="isTruncated(comment.content)"
                  @click="toggleExpanded(key)"
                >
                  {{ isExpanded[key] ? "Show less" : "Show more" }}
                </button>
              </div>

              <div class="flex items-center justify-between">
                <button
                  class="rounded-md bg-red-400 px-2 py-1 text-sm leading-5 text-white"
                  @click="deleteAndFetchData(comment.id)"
                >
                  Delete
                </button>
                <span class="ml-auto text-sm">
                  {{ dayjs(comment.date).format("DD-MM-YYYY") }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <form
          @submit.prevent="publishComment"
          class="flex w-2/5 flex-col gap-2"
        >
          <label for="username">Add your name</label>
          <input id="username" v-model="newCommentName" type="name" required />
          <label for="comment-area">Add your comment</label>
          <textarea
            v-model="newCommentContent"
            id="comment-area"
            name="comment"
            maxlength="200"
            rows="4"
            class="m-0 resize-none p-1"
            required
          ></textarea>
          <button type="submit" class="bg-sky-400 px-4 py-2">Submit</button>
        </form>
      </div>

      <DeleteModal
        :isModalOpen="isModalOpen"
        @closeModal="isModalOpen = false"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
  import dayjs from "dayjs";
  import { onMounted, ref, watchEffect } from "vue";

  import {
    useDelete,
    useFetch,
    useSubmitComment,
  } from "../hooks/useCommentActions.vue";
  import { Comment } from "../vite-env";
  import DeleteModal from "./DeleteModal.vue";
  import NotFound from "./NotFound.vue";

  const comments = ref<Comment[]>([]);

  const isExpanded = ref<Record<number, boolean>>({});
  const isModalOpen = ref(false);

  const newCommentName = ref("");
  const newCommentContent = ref("");

  const isTruncated = (text: string): boolean => {
    return text.length > 180;
  };

  const truncatedText = (text: string): string => {
    return text.length > 180 ? text.slice(0, 180) + "..." : text;
  };

  const toggleExpanded = (key: number) => {
    isExpanded.value[key] = !isExpanded.value[key];
  };

  const { submitComment, isSubmitting, errorMessage } = useSubmitComment();

  const { data, error, fetchData } = useFetch({
    params: {
      action: "get_comments",
      post_id: 64,
    },
  });

  const deleteAndFetchData = async (id: number) => {
    try {
      isModalOpen.value = true;
      await useDelete(id);
      fetchData();
    } catch (error) {
      console.error("Error deleting comment:", error);
    }
  };

  onMounted(() => {
    fetchData();
  });

  watchEffect(() => {
    if (data.value) {
      comments.value = data.value;
    }
  });

  const publishComment = async () => {
    if (newCommentContent.value.trim() === "") return;

    try {
      const postId = 64;
      await submitComment(postId, {
        name: newCommentName.value,
        content: newCommentContent.value,
        date: dayjs().toISOString(),
      });
      fetchData();
      newCommentContent.value = "";
      newCommentName.value = "";
    } catch (error) {
      console.error("Error submitting comment:", error);
    }
  };
</script>
