<template>
  <div class="form-floating">
    <select
      class="form-select"
      :class="{ 'is-invalid': error }"
      :id="idSelect"
      v-model="selected"
    >
      <option
        v-for="item in data"
        :key="item.value"
        :value="item.value"
        v-text="item.label"
      ></option>
    </select>
    <label :for="idSelect" v-text="placeholder"></label>
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
      type: [String, Object],
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
    data: {
      type: Array,
      require: true,
    },
    placeholder: {
      type: String,
      default: "Escolha um item",
    },
    selectFirst: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      idSelect: null,
      selected: this.value,
    };
  },
  watch: {
    selected(newValue) {
      this.$emit("input", newValue);
    },
    value(newValue) {
      this.selected = newValue;
    },
    data() {
      this.checkDefault();
    },
  },
  methods: {
    handleChange(e) {
      this.$emit("input", e.target.value);
    },
    checkDefault() {
      if (this.selectFirst && this.data.length > 0 && this.value === null) {
        return (this.selected = this.data[0].value);
      }
      this.selected = this.value;
    },
    verifyIfExistValueInData() {
      return this.data.find((element) => element.value === this.value)
        ? true
        : false;
    },
  },
  beforeMount() {
    this.idSelect = this.id ? this.id : `select-default-${this._uid}`;
  },
  mounted() {
    this.checkDefault();
  },
};
</script>

<style></style>
