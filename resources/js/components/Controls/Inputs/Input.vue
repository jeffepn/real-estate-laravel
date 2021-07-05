<template>
  <div class="form-floating">
    <money
      v-if="isNumber"
      :id="idInput"
      class="form-control"
      @input="handleInputNumber"
      :value="value || 0"
      v-bind="optionsNumber"
      @keydown="$event.key === '-' ? $event.preventDefault() : null"
    ></money>
    <input
      v-else
      class="form-control"
      :class="{ 'is-invalid': error }"
      @input="handleInput"
      :value="value"
      type="text"
      :maxlength="maxLength ? maxLength : 524288"
      :id="idInput"
    />
    <label :for="idInput" v-text="placeholderFormated"></label>
    <div v-if="error" class="text-danger" v-text="errorMessage"></div>
  </div>
</template>

<script>
import { Money } from "v-money";
import { VMoney } from "v-money";
export default {
  components: {
    Money,
  },
  directives: {
    money: VMoney,
  },
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
    type: {
      type: String,
      default: "text",
    },
    placeholder: {
      type: String,
      default: null,
    },
    maxLength: {
      type: Number,
      default: 0,
    },
    decimal: {
      type: String,
      default: ",",
    },
    thousands: {
      type: String,
      default: ".",
    },
    prefix: {
      type: String,
      default: "",
    },
    precision: {
      type: Number,
      default: 2,
    },
    masked: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      idInput: null,
      optionsNumber: {
        decimal: this.decimal,
        thousands: this.thousands,
        prefix: this.prefix,
        precision: this.precision,
        masked: this.masked,
      },
    };
  },
  computed: {
    isNumber() {
      return this.type === "number";
    },
    placeholderFormated() {
      if (this.isNumber || !this.maxLength) {
        return this.placeholder;
      }
      let lengthInput = this.value ? this.value.length : 0;
      return `${this.placeholder} ${lengthInput}/${this.maxLength}`;
    },
  },
  beforeMount() {
    this.idInput = this.id ? this.id : `input-default-${this._uid}`;
  },
  methods: {
    handleInput(e) {
      this.$emit("input", e.target.value);
    },
    handleInputNumber(value) {
      this.$emit("input", value);
    },
  },
};
</script>

<style></style>
