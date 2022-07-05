<template>
  <div class="d-flex align-items-start">
    <div class="col">
      <re-select
        v-model="selected"
        :data="typePeople"
        :select-first="true"
        placeholder="Tipo de Pessoa"
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
        title="Caso não encontre um Tipo de Pessoa, cadastre outro."
      >
        <i class="fas fa-plus"></i>
      </button>
    </div>
    <re-modal
      title="Adicionar novo Tipo Pessoa "
      :show="showModal"
      @close="closeModalAddPerson"
      :footer="false"
    >
      <re-add-type-person @submitSuccess="submitSuccess" />
    </re-modal>
  </div>
</template>

<script>
import ReSelect from "@/components/Controls/Inputs/Select";
import ReModal from "@/components/Modal";
import ReAddTypePerson from "@/components/Forms/AddTypePerson";
export default {
  name: "ChooseTypePerson",
  components: {
    ReSelect,
    ReModal,
    ReAddTypePerson,
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
    typePeople() {
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
      await reaxios(reroute("jp_realestate.api.type_person.index")).then(
        ({ data }) => {
          this.data = data.data;
        },
      );
    },
    submitSuccess() {
      this.getData();
    },
    closeModalAddPerson() {
      this.showModal = false;
      window.eventBus.$emit("clear-add-type-person");
    },
  },
  async beforeMount() {
    await this.getData();
  },
  mounted() {
    window.eventBus.$on("reload-add-type-person", () => {
      this.getData();
    });
    window.retooltip();
  },
};
</script>

<style></style>
