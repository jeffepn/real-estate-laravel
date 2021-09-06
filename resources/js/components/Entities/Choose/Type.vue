<template>
  <div class="d-flex align-items-start">
    <div class="col">
      <re-select
        v-model="selected"
        :data="types"
        :select-first="true"
        placeholder="Tipo"
        :error="error"
        :error-message="errorMessage"
      ></re-select>
    </div>
    <div class="col-auto">
      <button
        type="button"
        class="btn btn-primary btn-circle btn-sm ms-2"
        @click.prevent="showModal = true"
        v-if="create"
        data-bs-toggle="tooltip"
        data-bs-placement="bottom"
        title="Caso não encontre um Tipo, cadastre outro."
      >
        <i class="fas fa-plus"></i>
      </button>
    </div>
    <re-modal
      title="Adicionar novo Tipo"
      :show="showModal"
      @close="closeModalAddType"
      :footer="false"
    >
      <re-add-type @submitSuccess="submitSuccess"> </re-add-type>
    </re-modal>
  </div>
</template>

<script>
import ReSelect from "@/components/Controls/Inputs/Select";
import ReModal from "@/components/Modal";
import ReAddType from "@/components/Forms/AddType";
export default {
  components: {
    ReSelect,
    ReModal,
    ReAddType,
  },
  props: {
    value: {
      type: String,
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
    create: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      selected: this.value,
      data: [],
      showModal: false,
    };
  },
  computed: {
    types() {
      return this.data.reduce((acumulator, currentValue) => {
        acumulator.push({
          value: currentValue.id,
          label: currentValue.attributes.name,
        });
        return acumulator;
      }, []);
    },
  },
  watch: {
    selected(newValue) {
      this.$emit("input", newValue);
    },
    value(newValue) {
      this.selected = newValue;
    },
  },
  methods: {
    async getData() {
      await this.$axios(this.$route("jp_realestate.type.index")).then(
        ({ data }) => {
          this.data = data.data;
        },
      );
    },
    submitSuccess() {
      this.getData();
    },
    closeModalAddType() {
      this.showModal = false;
      this.$eventBus.$emit("clear-add-type");
    },
  },
  async beforeMount() {
    await this.getData();
  },
  mounted() {
    this.$eventBus.$on("reload-add-type", () => {
      this.getData();
    });
    window.tooltip();
  },
};
</script>

<style></style>
