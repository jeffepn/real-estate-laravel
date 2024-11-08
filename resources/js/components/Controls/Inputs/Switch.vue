<template>
  <div class="form-check form-switch">
    <input
      class="form-check-input"
      type="checkbox"
      role="switch"
      :id="idSwitch"
      :checked="value"
      @change="handleChange"
    />
    <label class="form-check-label" :for="idSwitch" v-text="label"></label>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: [Boolean, String, Number],
      default: null,
    },
    id: {
      type: String,
      default: null,
    },
    label: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      idSwitch: null,
    };
  },
  methods: {
    handleChange(e) {
      this.$emit("input", e.target.checked);
    },
  },
  beforeMount() {
    this.idSwitch = this.id ? this.id : `id-switch-defaul-${this._uid}`;
  },
};
</script>

<style lang="scss" scope>
.form-check {
  input,
  label {
    cursor: pointer;
  }
}
</style>
