<template>
  <div
    class="toast-container position-absolute bottom-0 end-0 p-3"
    style="z-index: 2000"
  >
    <re-toast
      v-for="(toast, index) in toasts"
      :key="index"
      v-bind="toast"
    ></re-toast>
  </div>
</template>

<script>
import ReToast from "@/components/Toast";
export default {
  components: {
    ReToast,
  },
  data() {
    return {
      toasts: [],
    };
  },
  mounted() {
    this.$eventBus.$on("add-toast-master", this.addToast);
  },
  methods: {
    addToast({ data }) {
      this.toasts.push(data);
    },
  },
};
</script>

<style></style>
