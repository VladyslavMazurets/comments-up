<script lang="ts">
  import axios from "axios";
  import { ref } from "vue";

  const basicUrl = "http://localhost:8888/upwordpress/wp-admin/admin-ajax.php";

  export const useFetch = (options: any) => {
    const data = ref(null);
    const error = ref<Error | unknown>(null);

    const fetchData = async () => {
      try {
        const response = await axios.get(basicUrl, options);
        data.value = response?.data?.data;
      } catch (err) {
        error.value = err;
        console.error(err);
      }
    };

    return {
      data,
      error,
      fetchData,
    };
  };

  export const useDelete = async (id: string) => {
    try {
      const response = await axios.post(
        basicUrl,
        {
          action: "delete_comment",
          id: id,
        },
        {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
        }
      );
      console.log("Response: ", response);
    } catch (err) {
      console.error("Error: ", err);
    }
  };
</script>
