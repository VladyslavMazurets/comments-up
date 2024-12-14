<!-- WordPressPosts.vue -->
<template>
  <div>
    <div v-for="post in posts" :key="post.id">
      <h2>{{ post.title.rendered }}</h2>
      <div v-html="post.content.rendered"></div>
    </div>
  </div>
</template>

<script setup>
  import axios from "axios";
  import { onMounted, ref } from "vue";

  const posts = ref([]);

  // const fetchPosts = async () => {
  //   try {
  //     const response = await axios.get(
  //       "https://your-wordpress-site/wp-json/wp/v2/posts"
  //     );
  //     posts.value = response.data;
  //   } catch (error) {
  //     console.error("Error fetching posts:", error);
  //   }
  // };

  // onMounted(() => {
  //   fetchPosts();
  // });
</script>
