<template>
  <div class="mt-2">
    <div class="col-12 px-0 mb-4">
      <re-button
        classes="btn btn-primary btn-sm"
        @click="setLoadModalBusiness('create')"
        data-bs-toggle="tooltip"
        data-bs-placement="bottom"
        title="Caso não encontre um Negócio, cadastre outro."
      >
        Adicionar Negócio
        <i class="fas fa-plus"></i>
      </re-button>
    </div>
    <re-modal
      :title="labelModalBusiness"
      :show="showModal"
      @close="closeModalAddBusiness"
      :footer="false"
    >
      <re-add-business @submitSuccess="submitSuccess" :businessId="businessId">
      </re-add-business>
    </re-modal>
    <div class="row">
      <div class="col-12" v-for="business in businesses" :key="business.id">
        <div class="d-flex mb-2">
          <re-checkbox
            :label="business.label"
            v-model="business.checked"
            @input="handleInput"
          ></re-checkbox>
          <re-button
            classes="btn btn-outline-secondary btn-sm ms-2"
            @click="setLoadModalBusiness('edit', business.id)"
            data-bs-toggle="tooltip"
            data-bs-placement="bottom"
            title="Editar as informaçõs do negócio."
          >
            <i class="fas fa-pencil-alt"></i>
          </re-button>
        </div>
        <div class="row align-items-end" v-if="business.checked">
          <div class="col-12 col-md-6 col-lg-4">
            <re-input
              :placeholder="`Valor do(a) ${business.label}`"
              prefix="R$ "
              type="number"
              v-model="business.value"
              @input="handleInput"
              @pressEnter="$emit('pressEnter')"
            ></re-input>
          </div>
          <div class="col-12 col-md-6 col-lg-4">
            <re-checkbox
              class="mb-2"
              :label="
                `Este negócio já foi <b>${
                  business.has_situation
                    ? business.name_completed
                    : 'finalizado'
                }</b>?`
              "
              v-model="business.status_situation_checked"
              @input="handleInput"
            ></re-checkbox>
          </div>
          <div class="col-12">
            <hr />
          </div>
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
      businessId: null,
    };
  },
  methods: {
    async getBusinesses() {
      await reaxios
        .get(reroute("jp_realestate.api.business.index"))
        .then((response) => {
          this.originalBusinesses = response.data.data;
          this.formatBusinesses();
        });
    },
    formatBusinesses() {
      this.businesses = this.originalBusinesses.reduce(
        (acumulator, currentValue) => {
          const baseData = {
            id: currentValue.id,
            label: currentValue.attributes.name,
            name_completed: currentValue.attributes.name_completed,
            has_situation: currentValue.attributes.has_situation,
          };
          const t = this.businesses.hasOwnProperty(currentValue.id)
            ? this.businesses[currentValue.id]
            : {
                checked: false,
                value: 0,
                status_situation_checked: false,
              };
          acumulator[currentValue.id] = Object.assign(t, baseData);
          return acumulator;
        },
        {},
      );
    },
    closeModalAddBusiness() {
      this.showModal = false;
      eventBus.$emit("clear-add-business");
    },
    submitSuccess() {
      this.getBusinesses();
    },
    handleInput() {
      this.form.data.businesses = Object.keys(this.businesses).reduce(
        (acumulator, key) => {
          if (this.businesses[key].checked) {
            acumulator.push({
              id: key,
              value: this.businesses[key].value,
              status_situation: this.businesses[key].status_situation_checked
                ? 1
                : 0,
            });
          }
          return acumulator;
        },
        [],
      );
    },
    setLoadModalBusiness(type, businessId = null) {
      this.businessId = type === "create" ? null : businessId;
      this.showModal = true;
    },
  },
  watch: {
    showModal(newValue) {
      if (!newValue) {
        this.businessId = null;
      }
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
    labelModalBusiness() {
      return this.businessId ? "Editar Negócio" : "Adicionar Negócio";
    },
  },
  async mounted() {
    await this.getBusinesses();
    this.form.data.businesses.map((element) => {
      if (this.businesses.hasOwnProperty(element.id)) {
        this.businesses[element.id].value = element.value;
        this.businesses[
          element.id
        ].status_situation_checked = element.status_situation ? true : false;
        this.businesses[element.id].checked = true;
      }
    });
  },
};
</script>

<style></style>
