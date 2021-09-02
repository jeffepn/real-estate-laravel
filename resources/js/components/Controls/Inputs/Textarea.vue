<template>
  <div class="form-floating">
    <textarea
      class="form-control"
      :class="{ 'is-invalid': error }"
      placeholder="Comece a escrever..."
      style="height: 100px"
      :value="value"
      @input="handleInput"
      :maxlength="maxLength ? maxLength : 524288"
      :id="idInput"
    ></textarea>
    <label :for="idInput" v-text="placeholderFormated"></label>
    <div v-if="error" class="text-danger" v-text="errorMessage"></div>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      default: null,
    },
    value: {
      type: [String, Number],
      default: null,
    },
    error: {
      type: Boolean,
      default: false,
    },
    errorMessage: {
      type: String,
      default: "",
    },
    placeholder: {
      type: String,
      default: null,
    },
    maxLength: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      idInput: null,
    };
  },
  computed: {
    placeholderFormated() {
      if (this.isNumber || !this.maxLength) {
        return this.placeholder;
      }
      let lengthInput = this.value ? this.value.length : 0;
      return `${this.placeholder} ${lengthInput}/${this.maxLength}`;
    },
  },
  beforeMount() {
    this.idInput = this.id ? this.id : `textarea-default-${this._uid}`;
  },
  methods: {
    handleInput(e) {
      this.$emit("input", e.target.value);
    },
  },
};
</script>

<style></style>
