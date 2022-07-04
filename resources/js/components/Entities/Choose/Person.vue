<template>
  <div class="d-flex align-items-start">
    <div class="col">
      <re-select
        v-model="selected"
        :data="people"
        :select-first="true"
        placeholder="Pessoa"
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
        title="Caso não encontre uma Pessoa, cadastre outra."
      >
        <i class="fas fa-plus"></i>
      </button>
    </div>
    <re-modal
      title="Adicionar nova Pessoa "
      :show="showModal"
      @close="closeModalAddPerson"
      :footer="false"
    >
      <re-add-person @submitSuccess="submitSuccess" />
    </re-modal>
  </div>
</template>

<script>
import ReSelect from "@/components/Controls/Inputs/Select";
import ReModal from "@/components/Modal";
import ReAddPerson from "@/components/Forms/AddPerson";
export default {
  name: "ChoosePerson",
  components: {
    ReSelect,
    ReModal,
    ReAddPerson,
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
    typePersonId: {
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
    people() {
      return this.data.reduce((acumulator, currentValue) => {
        if (currentValue.relationships.type.data.id === this.typePersonId) {
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
      await reaxios(window.reroute("jp_realestate.api.person.index"), {
        params: {
          with: "type",
        },
      }).then(({ data }) => {
        this.data = data.data;
      });
    },
    submitSuccess() {
      this.getData();
    },
    closeModalAddPerson() {
      this.showModal = false;
      window.eventBus.$emit("clear-add-person");
    },
  },
  async beforeMount() {
    await this.getData();
  },
  mounted() {
    window.eventBus.$on("reload-add-person", () => {
      this.getData();
    });
    window.retooltip();
  },
};
</script>

<style></style>
