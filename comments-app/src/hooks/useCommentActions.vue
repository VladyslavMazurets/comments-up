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
    } catch (err) {
      console.error("Error: ", err);
    }
  };

  export const useSubmitComment = () => {
    const isSubmitting = ref(false);
    const errorMessage = ref<string | null>(null);

    const submitComment = async (postId: number, options: any) => {
      isSubmitting.value = true;
      errorMessage.value = null;

      try {
        const response = await axios.post(
          basicUrl,
          {
            action: "submit_comment",
            post_id: postId,
            options,
          },
          {
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
          }
        );

        console.log("Comment submitted successfully", response.data);

        return response.data;
      } catch (error) {
        console.error("Error submitting comment:", error);
        errorMessage.value =
          "There was an error submitting your comment. Please try again.";
      } finally {
        isSubmitting.value = false;
      }
    };

    return {
      isSubmitting,
      errorMessage,
      submitComment,
    };
  };
</script>
