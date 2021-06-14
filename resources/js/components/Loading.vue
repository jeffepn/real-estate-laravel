<template>
  <div class="loading" :class="{ 'd-none': !visible }">
    <i class="fas fa-spinner fa-pulse fa-3x"></i>
  </div>
</template>

<script>
export default {
  data() {
    return {
      visible: false,
    };
  },
  mounted() {
    this.$eventBus.$on("loading-container-master", (loading) => {
      this.visible = loading;
    });
  },
};
</script>

<style lang="scss" scoped>
.loading {
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  right: 0;
  background: #fff;
  color: blue;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
