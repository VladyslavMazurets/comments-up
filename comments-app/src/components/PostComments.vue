<template>
  <div>
    <div class="flex flex-col gap-6 bg-sky-50 px-6 py-8">
      <h1 class="text-4xl font-bold">Comments</h1>

      <div class="flex flex-col gap-4">
        <div v-for="(comment, key) in comments" :key="key">
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
                >{{ comment.email }}</a
              >
            </div>

            <div class="flex flex-col gap-2">
              <div>
                <span class="line-clamp-6 text-base">
                  {{ comment.content }}
                </span>
              </div>
              <span class="ml-auto text-sm">
                {{ dayjs(comment.date).format("DD-MM-YYYY") }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import axios from "axios";
  import dayjs from "dayjs";
  import { onMounted, ref } from "vue";

  import { Comment } from "../vite-env";

  const comments = ref<Comment[]>([]);
  const newComment = ref<string>("");

  const fetchComments = async () => {
    await axios
      .get(
        "http://localhost:8888/upwordpress/wp-admin/admin-ajax.php?action=get_comments&post_id=1",
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
      .then((response) => {
        comments.value = response.data.data;
        console.log("🚀 ~ .then ~  response.data.data:", response.data.data);
      })
      .catch((error) => {
        console.error("Error fetching comments:", error);
      });
  };

  onMounted(() => {
    fetchComments();
  });
</script>
