<template>
  <button :class="classes" @click="handleClick">
    <slot v-if="!loading"></slot>
    <i v-show="loading" class="fas fa-circle-notch fa-spin"></i>
  </button>
</template>

<script>
export default {
  props: {
    loading: {
      type: Boolean,
      default: false,
    },
    classes: {
      type: String,
      default: "btn btn-primary",
    },
  },
  methods: {
    handleClick() {
      this.$emit("click");
    },
  },
};
</script>

<style></style>
