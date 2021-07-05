<template>
  <div class="mt-2">
    <div class="col-12 px-0 mb-4">
      <re-button
        class="btn btn-primary btn-sm ms-2"
        @click="showModal = true"
        data-bs-toggle="tooltip"
        data-bs-placement="bottom"
        title="Caso não encontre um Negócio, cadastre outro."
      >
        Adicionar Negócio
        <i class="fas fa-plus"></i>
      </re-button>
    </div>
    <re-modal
      title="Adicionar novo Negócio"
      :show="showModal"
      @close="closeModalAddBusiness"
      :footer="false"
    >
      <re-add-business @submitSuccess="submitSuccess"> </re-add-business>
    </re-modal>
    <div class="row">
      <div
        class="col-12 mb-2"
        v-for="business in businesses"
        :key="business.id"
      >
        <re-checkbox
          class="mb-2"
          :label="business.label"
          v-model="business.checked"
          @input="handleInput"
        ></re-checkbox>
        <div class="d-inline-flex" v-if="business.checked">
          <re-input
            :placeholder="`Valor do(a) ${business.label}`"
            prefix="R$ "
            type="number"
            v-model="business.value"
            @input="handleInput"
          ></re-input>
        </div>
      </div>
    </div>
    <div class="col-12">
      <p class="text-danger" v-text="errorBusinesses"></p>
    </div>
  </div>
</template>

<script>
import ReCheckbox from "@/components/Controls/Inputs/Checkbox";
import ReInput from "@/components/Controls/Inputs/Input";
import ReModal from "@/components/Modal";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReAddBusiness from "@/components/Forms/AddBusiness";
export default {
  components: {
    ReCheckbox,
    ReInput,
    ReModal,
    ReButton,
    ReAddBusiness,
  },
  props: {
    form: {
      type: Object,
      require: true,
    },
  },
  data() {
    return {
      showModal: false,
      originalBusinesses: [],
      businesses: {},
    };
  },
  methods: {
    async getBusinesses() {
      await this.$axios
        .get(this.$route("jp_realestate.business.index"))
        .then((response) => {
          this.originalBusinesses = response.data.data;
          this.formatBusinesses();
        });
    },
    formatBusinesses() {
      this.businesses = this.originalBusinesses.reduce(
        (acumulator, currentValue) => {
          if (this.businesses.hasOwnProperty(currentValue.id)) {
            acumulator[currentValue.id] = this.businesses[currentValue.id];
            return acumulator;
          }
          acumulator[currentValue.id] = {
            id: currentValue.id,
            label: currentValue.attributes.name,
            checked: false,
            value: 0,
          };
          return acumulator;
        },
        {},
      );
    },
    closeModalAddBusiness() {
      this.showModal = false;
      this.$eventBus.$emit("clear-add-business");
    },
    submitSuccess() {
      this.getBusinesses();
    },
    handleInput() {
      this.form.data.businesses = Object.keys(this.businesses).reduce(
        (acumulator, key) => {
          if (this.businesses[key].checked) {
            acumulator.push({ id: key, value: this.businesses[key].value });
          }
          return acumulator;
        },
        [],
      );
    },
  },
  computed: {
    showPrices() {
      return true;
    },
    errorBusinesses() {
      let keys = Object.keys(this.form.errors).filter((key) =>
        /businesses/.test(key),
      );
      return keys.length ? this.form.errors[keys[0]][0] : "";
    },
  },
  async mounted() {
    await this.getBusinesses();
    this.form.data.businesses.map((element) => {
      if (this.businesses.hasOwnProperty(element.id)) {
        this.businesses[element.id].value = element.value;
        this.businesses[element.id].checked = true;
      }
    });
  },
};
</script>

<style></style>
