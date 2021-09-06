<template>
  <div class="d-flex align-items-start">
    <div class="col">
      <re-select
        v-model="selected"
        :data="subTypes"
        :select-first="true"
        placeholder="Sub Tipo"
        :error="error"
        :error-message="errorMessage"
      ></re-select>
    </div>
    <div class="col-auto">
      <button
        type="button"
        class="btn btn-primary btn-circle btn-sm ms-2"
        data-bs-toggle="tooltip"
        data-bs-placement="bottom"
        title="Caso não encontre um Sub Tipo, cadastre outro."
        @click.prevent="showModal = true"
        v-if="create"
      >
        <i class="fas fa-plus"></i>
      </button>
    </div>
    <re-modal
      title="Adicionar novo Sub Tipo"
      :show="showModal"
      @close="closeModalAddSubType"
      :footer="false"
    >
      <re-add-sub-type :show="showModal" @submitSuccess="submitSuccess">
      </re-add-sub-type>
    </re-modal>
  </div>
</template>

<script>
import ReSelect from "@/components/Controls/Inputs/Select";
import ReModal from "@/components/Modal";
import ReAddSubType from "@/components/Forms/AddSubType";
export default {
  components: {
    ReSelect,
    ReModal,
    ReAddSubType,
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
    typeId: {
      type: String,
      default: null,
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
    subTypes() {
      return this.data.reduce((acumulator, currentValue) => {
        if (currentValue.relationships.type.data.id === this.typeId) {
          acumulator.push({
            value: currentValue.id,
            label: currentValue.attributes.name,
          });
        }
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
      await this.$axios(this.$route("jp_realestate.sub-type.index")).then(
        ({ data }) => {
          this.data = data.data;
        },
      );
    },
    submitSuccess() {
      this.getData();
    },
    closeModalAddSubType() {
      this.showModal = false;
      this.$eventBus.$emit("clear-add-sub-type");
    },
  },
  async created() {
    await this.getData();
    if (this.value) {
      this.selected = this.value;
    }
  },
  mounted() {
    window.tooltip();
  },
};
</script>

<style></style>
