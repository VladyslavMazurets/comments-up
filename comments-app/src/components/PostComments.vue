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
                >{{ comment.email }}
              </a>
            </div>

            <div class="flex flex-col gap-2">
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

  const isExpanded = ref<Record<number, boolean>>({});

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
      })
      .catch((error) => {
        console.error("Error fetching comments:", error);
      });
  };

  const isTruncated = (text: string): boolean => {
    return text.length > 180;
  };

  const truncatedText = (text: string): string => {
    return text.length > 180 ? text.slice(0, 180) + "..." : text;
  };

  const toggleExpanded = (key: number) => {
    isExpanded.value[key] = !isExpanded.value[key];
  };

  onMounted(() => {
    fetchComments();
  });
</script>
