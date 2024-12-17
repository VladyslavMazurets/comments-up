<script lang="ts">
  import axios from "axios";
  import { ref } from "vue";

  export const useFetch = (url: string, options: any) => {
    const data = ref(null);
    const error = ref<Error | unknown>(null);
    const loading = ref(false);

    const fetchData = async () => {
      loading.value = true;
      try {
        const response = await axios.get(url, options);
        data.value = response.data.data;
      } catch (err) {
        error.value = err;
        console.error("Fetch error:", err);
      } finally {
        loading.value = false;
      }
    };

    fetchData();

    return { data, error, loading, fetchData };
  };
</script>
