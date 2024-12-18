<template>
  <div
    v-if="isModalOpen"
    class="fixed inset-0 z-10 flex items-center justify-center"
  >
    <div class="absolute inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm" />
    <dialog open class="z-20 rounded-3xl p-10">
      <div class="flex flex-col gap-5">
        <div class="flex flex-col">
          <span class="text-xl">
            The comment by <strong>"{{ deletedCommentAuthor }}"</strong> has
            been deleted successfully.
          </span>
          <span class="mt-2 text-sm">
            You can now continue with other actions.
          </span>
        </div>
        <button class="bg-sky-400 px-4 py-2" @click="emit('closeModal')">
          Close
        </button>
      </div>
    </dialog>
  </div>
</template>
<script setup lang="ts">
  interface Props {
    isModalOpen: boolean;
    deletedCommentAuthor: string;
  }

  const props = defineProps<Props>();

  const emit = defineEmits(["closeModal"]);
</script>
