<template>
  <div class="d-flex align-items-start">
    <div class="col">
      <re-select
        v-model="selected"
        :data="situations"
        :select-first="true"
        placeholder="Situação"
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
        title="Caso não encontre uma Situação, cadastre outra."
      >
        <i class="fas fa-plus"></i>
      </button>
    </div>
    <re-modal
      title="Adicionar nova situação"
      :show="showModal"
      @close="closeModalAddType"
      :footer="false"
    >
      <re-add-situation @submitSuccess="submitSuccess"> </re-add-situation>
    </re-modal>
  </div>
</template>

<script>
import ReSelect from "@/components/Controls/Inputs/Select";
import ReModal from "@/components/Modals/Modal";
import ReAddSituation from "@/components/Forms/Situation/AddSituation";
export default {
  name: "ChooseSituation",
  components: {
    ReSelect,
    ReModal,
    ReAddSituation,
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
    situations() {
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
      await reaxios(reroute("jp_realestate.api.situation.index")).then(
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
     eventBus.$emit("clear-add-situation");
    },
  },
  async beforeMount() {
    await this.getData();
  },
  mounted() {
   eventBus.$on("reload-add-situation", () => {
      this.getData();
    });
  },
};
</script>

<style></style>
