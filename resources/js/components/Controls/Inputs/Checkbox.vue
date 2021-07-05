<template>
  <div class="form-check">
    <input
      class="form-check-input"
      type="checkbox"
      :id="idCheckbox"
      :checked="value"
      @change="handleChange"
    />
    <label class="form-check-label" :for="idCheckbox" v-text="label"></label>
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
      idCheckbox: null,
    };
  },
  methods: {
    handleChange(e) {
      this.$emit("input", e.target.checked);
    },
  },
  beforeMount() {
    this.idCheckbox = this.id ? this.id : `id-checkbox-defaul-${this._uid}`;
  },
};
</script>

<style></style>
