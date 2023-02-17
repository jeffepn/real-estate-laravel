<template>
  <div :class="classFab">
    <button class="main bg-primary" @click="toggle"></button>
    <ul>
      <li v-for="(action, index) in actions" :key="index">
        <button
          :id="`action-${action.slug}`"
          @click="emitEvent(action)"
          v-html="action.icon"
          class="bg-secondary"
          data-bs-toggle="tooltip" data-bs-placement="left" :data-bs-title="action.label"
        ></button>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    actions: {
      type: Array,
      required: true,
    },
  },
  data: () => ({
    show: false,
  }),
  computed: {
    classFab() {
      return this.show ? "fab show" : "fab";
    },
  },
  methods: {
    toggle() {
      this.show = !this.show;
    },
    emitEvent(action) {
        this.toggle();
        this.$emit('bulk-event', action);
    }
  },
};
</script>

<style>
.fab {
  position: relative;
}

.fab button {
  cursor: pointer;
  width: 48px;
  height: 48px;
  border-radius: 30px;
  border: none;
  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.4);
  font-size: 24px;
  color: white;

  -webkit-transition: 0.2s ease-out;
  -moz-transition: 0.2s ease-out;
  transition: 0.2s ease-out;
}

.fab button:focus {
  outline: none;
}

.fab button.main {
  position: relative;
  width: 60px;
  height: 60px;
  border-radius: 30px;
  right: 0;
  bottom: 0;
  z-index: 20;
}

.fab button.main:before {
  content: "⏚";
}

.fab ul {
  position: absolute;
  bottom: 0;
  right: 0;
  padding: 0;
  padding-right: 5px;
  margin: 0;
  list-style: none;
  z-index: 10;

  -webkit-transition: 0.2s ease-out;
  -moz-transition: 0.2s ease-out;
  transition: 0.2s ease-out;
}

.fab ul li {
  display: flex;
  justify-content: flex-start;
  position: relative;
  margin-bottom: -10%;
  opacity: 0;

  -webkit-transition: 0.3s ease-out;
  -moz-transition: 0.3s ease-out;
  transition: 0.3s ease-out;
}

.fab.show button.main,
.fab.show button.main {
  outline: none;
  background-color: #7716ff;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.5);
}

.fab.show button.main:before,
.fab.show button.main:before {
  content: "↑";
}

.fab.show button.main + ul,
.fab.show button.main + ul {
  bottom: 70px;
}

.fab.show button.main + ul li,
.fab.show button.main + ul li {
  margin-bottom: 10px;
  opacity: 1;
}

.fab.show button.main + ul li:hover label,
.fab.show button.main + ul li:hover label {
  opacity: 1;
}
</style>
