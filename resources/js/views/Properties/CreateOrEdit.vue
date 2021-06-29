<template>
  <div class="container">
    <div class="card my-5">
      <div
        class="
          card-header
          d-flex
          flex-wrap
          justify-content-between
          align-items-center
        "
      >
        <h2 v-text="title"></h2>
        <div class="d-flex flex-wrap">
          <a
            :href="$route('jp_realestate.property.list')"
            class="btn btn-outline-secondary ms-2"
          >
            <span aria-hidden="true">&laquo;</span> Voltar
          </a>
        </div>
      </div>
      <div class="card-body px-0">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="border-start-0 nav-link active"
              id="data-tab"
              data-bs-target="#data"
              type="button"
              role="tab"
              aria-controls="data"
              aria-selected="true"
            >
              Dados
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="details-tab"
              data-bs-target="#details"
              type="button"
              role="tab"
              aria-controls="details"
              aria-selected="false"
            >
              Detalhes
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="businesses-tab"
              data-bs-target="#businesses"
              type="button"
              role="tab"
              aria-controls="businesses"
              aria-selected="false"
            >
              Negócios
            </button>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active px-2" id="data" role="tabpanel">
            <re-form-data-property
              :form="form"
              :type-id="this.type_id"
            ></re-form-data-property>
            <div class="mb-2 col-12 text-end">
              <re-button :loading="loadingNext" @click="next('details')">
                Próximo <span aria-hidden="true">&raquo;</span>
              </re-button>
            </div>
          </div>
          <div class="tab-pane fade px-2" id="details" role="tabpanel">
            <div class="row collapse" :class="{ show: showPrices }">
              <div class="col-sm-6 col-md-auto" v-show="form.data.rent">
                <re-input
                  type="number"
                  placeholder="Preço de aluguel"
                  prefix="R$ "
                  :masked="false"
                ></re-input>
              </div>
              <div class="col-sm-6 col-md-auto" v-show="form.data.sale">
                <re-input
                  type="number"
                  placeholder="Preço de venda"
                  prefix="R$ "
                  :masked="false"
                ></re-input>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-sm-6 col-md-3 mb-2">
                <div class="form-floating">
                  <input
                    type="tel"
                    v-money="decimal"
                    class="form-control"
                    id="floatingInputBuildingArea"
                    v-model="form.building_area"
                  />
                  <label for="floatingInputBuildingArea">Área construída</label>
                </div>
              </div>
              <div class="col-sm-6 col-md-3 mb-2">
                <div class="form-floating">
                  <input
                    type="tel"
                    v-money="decimal"
                    class="form-control"
                    id="floatingInputTotalArea"
                    v-model="form.total_area"
                  />
                  <label for="floatingInputTotalArea">Área total</label>
                </div>
              </div>
            </div>

            <div class="row mt-3 flex-wrap">
              <div
                class="
                  mb-2
                  col-sm-6 col-md-4 col-lg-3 col-xl-2
                  d-flex
                  flex-column
                "
              >
                <div class="col-12">Dormitórios</div>
                <div class="d-flex mt-2">
                  <div class="form-floating col-5 pe-1">
                    <input
                      type="tel"
                      v-money="integer"
                      class="form-control"
                      step="1"
                      id="floatingInputMinDormitory"
                      v-model="form.min_dormitory"
                    />
                    <label for="floatingInputMinDormitory">Min.</label>
                  </div>
                  <div class="col-auto px-0 d-flex align-items-center">
                    <i class="fas fa-arrows-alt-h"></i>
                  </div>
                  <div class="form-floating col-5 ps-1">
                    <input
                      type="tel"
                      v-money="integer"
                      class="form-control"
                      step="1"
                      id="floatingInputMaxDormitory"
                      v-model="form.max_dormitory"
                    />
                    <label for="floatingInputMaxDormitory">Max</label>
                  </div>
                </div>
              </div>
              <div
                class="
                  mb-2
                  col-sm-6 col-md-4 col-lg-3 col-xl-2
                  d-flex
                  flex-column
                "
              >
                <div class="col-12">Suítes</div>

                <div class="d-flex mt-2">
                  <div class="form-floating col-5 pe-1">
                    <input
                      type="tel"
                      v-money="integer"
                      class="form-control"
                      step="1"
                      id="floatingInputMinSuite"
                      v-model="form.min_suite"
                    />
                    <label for="floatingInputMinSuite">Min.</label>
                  </div>
                  <div class="col-auto px-0 d-flex align-items-center">
                    <i class="fas fa-arrows-alt-h"></i>
                  </div>
                  <div class="form-floating col-5 ps-1">
                    <input
                      type="tel"
                      v-money="integer"
                      class="form-control"
                      step="1"
                      id="floatingInputMaxSuite"
                      v-model="form.max_suite"
                    />
                    <label for="floatingInputMaxSuite">Max</label>
                  </div>
                </div>
              </div>
              <div
                class="
                  mb-2
                  col-sm-6 col-md-4 col-lg-3 col-xl-2
                  d-flex
                  flex-column
                "
              >
                <div class="col-12">Banheiros</div>

                <div class="d-flex mt-2">
                  <div class="form-floating col-5 pe-1">
                    <input
                      type="tel"
                      v-money="integer"
                      class="form-control"
                      step="1"
                      id="floatingInputMinBathroom"
                      v-model="form.min_bathroom"
                    />
                    <label for="floatingInputMinBathroom">Min.</label>
                  </div>
                  <div class="col-auto px-0 d-flex align-items-center">
                    <i class="fas fa-arrows-alt-h"></i>
                  </div>
                  <div class="form-floating col-5 ps-1">
                    <input
                      type="tel"
                      v-money="integer"
                      class="form-control"
                      step="1"
                      id="floatingInputMaxBathroom"
                      v-model="form.max_bathroom"
                    />
                    <label for="floatingInputMaxBathroom">Max</label>
                  </div>
                </div>
              </div>
              <div
                class="
                  mb-2
                  col-sm-6 col-md-4 col-lg-3 col-xl-2
                  d-flex
                  flex-column
                "
              >
                <div class="col-12">Garagens</div>
                <div class="d-flex mt-2">
                  <div class="form-floating col-5 pe-1">
                    <input
                      type="tel"
                      v-money="integer"
                      class="form-control"
                      step="1"
                      id="floatingInputMinGarage"
                      v-model="form.min_garage"
                    />
                    <label for="floatingInputMinGarage">Min.</label>
                  </div>
                  <div class="col-auto px-0 d-flex align-items-center">
                    <i class="fas fa-arrows-alt-h"></i>
                  </div>
                  <div class="form-floating col-5 ps-1">
                    <input
                      type="tel"
                      v-money="integer"
                      class="form-control"
                      step="1"
                      id="floatingInputMaxGarage"
                      v-model="form.max_garage"
                    />
                    <label for="floatingInputMaxGarage">Max</label>
                  </div>
                </div>
              </div>
              <div class="mb-2 col-12">
                <div class="form-floating">
                  <textarea
                    class="form-control"
                    placeholder="Comece a escrever..."
                    id="floatingTextareaMinDescription"
                    style="height: 100px"
                    v-model="form.min_description"
                  ></textarea>
                  <label for="floatingTextareaMinDescription">
                    Descrição para SEO. (sinopse do imóvel)
                  </label>
                </div>
              </div>
              <div class="mb-2 col-12">
                <ckeditor
                  :editor="editor"
                  v-model="form.content"
                  :config="editorConfig"
                ></ckeditor>
              </div>
              <div class="mb-2 col-12 d-flex justify-content-between">
                <button class="btn btn-outline-secondary" @click="back('data')">
                  <span aria-hidden="true">&laquo;</span> Voltar
                </button>

                <div class="mb-2 col-12 text-end">
                  <re-button :loading="loadingNext" @click="next('business')">
                    Próximo <span aria-hidden="true">&raquo;</span>
                  </re-button>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade px-2" id="businesses" role="tabpanel">
            <re-add-businesses class="col-md-12 mb-2"></re-add-businesses>
            <div class="mb-2 col-12 d-flex justify-content-between">
              <button
                class="btn btn-outline-secondary"
                @click="back('details ')"
              >
                <span aria-hidden="true">&laquo;</span> Voltar
              </button>

              <div class="mb-2 col-12 text-end">
                <re-button :loading="loadingNext" @click="next">
                  Próximo <span aria-hidden="true">&raquo;</span>
                </re-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CKEditor from "@ckeditor/ckeditor5-vue2";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import Form from "@/supports/form.js";
import { VMoney } from "v-money";
import { mask } from "vue-the-mask";
import ReFormDataProperty from "@/components/Forms/DataProperty.vue";
import ReAddBusinesses from "@/components/Forms/Property/AddBusinesses";
import ReInput from "@/components/Controls/Inputs/Input";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
export default {
  directives: {
    mask,
    money: VMoney,
  },
  components: {
    ckeditor: CKEditor.component,
    ReFormDataProperty,
    ReAddBusinesses,
    ReInput,
    ReButton,
  },
  props: {
    id: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      editor: ClassicEditor,
      editorConfig: {
        allowedContent: true,
        removePlugins: ["CKFinder"],
        toolbar: {
          removeItems: "uploadImage|mediaEmbed",
        },
      },
      loadingNext: false,
      decimal: {
        decimal: ",",
        thousands: ".",
        prefix: "",
        precision: 2,
        masked: false,
      },
      integer: {
        decimal: "",
        thousands: ".",
        prefix: "",
        precision: 0,
        masked: false,
      },
      idProperty: this.id,
      property: null,
      form: new Form({
        sub_type_id: null,
        min_dormitory: 0,
        max_dormitory: 0,
        min_suite: 0,
        max_suite: 0,
        min_bathroom: 0,
        max_bathroom: 0,
        min_garage: 0,
        max_garage: 0,
        min_description: "",
        building_area: 0,
        total_area: 0,
        content: null,
        address: null,
        neighborhood: null,
        city: null,
        initials: null,
        number: null,
        not_number: null,
        cep: null,
        complement: null,
      }),
      originalTypes: [],
      originalTypesIncluded: [],
      type_id: null,
      tabDetails: null,
      tabData: null,
      tabBusinesses: null,
    };
  },
  watch: {
    property() {
      this.initialiseProperty();
    },
  },
  computed: {
    showPrices() {
      return this.form.data.rent || this.form.data.sale;
    },
    edit() {
      return this.idProperty !== null;
    },
    title() {
      return this.edit ? "Editar Imóvel" : "Novo Imóvel";
    },
    request() {
      return this.edit
        ? this.$axios.patch(
            this.$route("jp_realestate.property.update", [this.idProperty]),
            this.form.data,
          )
        : this.$axios.post(
            this.$route("jp_realestate.property.store"),
            this.form.data,
          );
    },
    types() {
      const types = this.originalTypes.reduce((acumulator, currentValue) => {
        acumulator.push({
          id: currentValue.id,
          label: currentValue.attributes.name,
        });
        return acumulator;
      }, []);
      if (types.length > 0) {
        this.type_id = types[0].id;
      }
      return types;
    },
    subTypes() {
      const subTypes = this.originalTypesIncluded.reduce(
        (acumulator, currentValue) => {
          if (
            currentValue.type === "sub_type" &&
            currentValue.relationships.type.data.id === this.type_id
          ) {
            acumulator.push({
              id: currentValue.id,
              label: currentValue.attributes.name,
            });
          }
          return acumulator;
        },
        [],
      );
      if (subTypes.length > 0) {
        this.form.sub_type_id = subTypes[0].id;
      }
      return subTypes;
    },
  },
  methods: {
    getTypes() {
      this.$axios
        .get(this.$route("jp_realestate.type.index"))
        .then((response) => {
          this.originalTypes = response.data.data;
          this.originalTypesIncluded = response.data.included;
        });
    },
    setTabShow(tab) {
      switch (tab) {
        case "data":
          this.tabData.show();
          break;
        case "details":
          this.tabDetails.show();
          break;
        case "businesses":
          this.tabBusinesses.show();
          break;

        default:
          break;
      }
    },
    next(tab) {
      console.log(this.form.data);
      this.loadingNext = true;
      this.form.clearErrors();
      this.request
        .then((response) => {
          if (!this.edit) {
            this.setProperty(response.data);
          }
          this.setTabShow(tab);
        })
        .catch((error) => {
          const { response } = error;
          if (response && response.status === 422) {
            return (this.form.errors = response.data.errors);
          }
          console.log(error);
          this.$toast.message({
            type: "danger",
            message: response.data.message,
          });
        })
        .finally(() => (this.loadingNext = false));
    },
    submit() {},
    initialiseProperty() {
      this.idProperty = this.property.id;
      history.pushState(
        {},
        null,
        this.$route("jp_realestate.property.edit", [this.id]),
      );
      this.setDataBaseProperty();
      console.log("Property", this.property);
    },
    setProperty({ data, included }) {
      let subType = included.find((element) => element.type === "sub_type");
      let address = included.find((element) => element.type === "address");
      let neighborhood = included.find(
        (element) => element.type === "neighborhood",
      );
      let city = included.find((element) => element.type === "city");
      let state = included.find((element) => element.type === "state");
      this.property = {
        id: data.id,
        slug: data.attributes.slug,
        sub_type_id: subType.id,
        code: data.attributes.code,
        building_area: data.attributes.building_area,
        total_area: data.attributes.total_area,
        min_description: data.attributes.min_description,
        content: data.attributes.content,
        items: data.attributes.items,
        min_dormitory: data.attributes.min_dormitory,
        max_dormitory: data.attributes.max_dormitory,
        min_bathroom: data.attributes.min_bathroom,
        max_bathroom: data.attributes.max_bathroom,
        min_suite: data.attributes.min_suite,
        max_suite: data.attributes.max_suite,
        min_garage: data.attributes.min_garage,
        max_garage: data.attributes.max_garage,
        address: address.attributes.address,
        number: address.attributes.number,
        not_number: address.attributes.not_number,
        complement: address.attributes.complement,
        neighborhood: neighborhood.attributes.name,
        city: city.attributes.name,
        initials: state.attributes.initials,
      };
      this.type_id = subType.relationships.type.data.id;
    },
    async getProperty() {
      console.log("Agor busco");
      await this.$axios
        .get(this.$route("jp_realestate.property.show", [this.id]))
        .then((response) => this.setProperty(response.data));
    },
    initialiseTabs() {
      var someTabTriggerEl = document.querySelector("#details-tab");
      this.tabDetails = new bootstrap.Tab(someTabTriggerEl);
      someTabTriggerEl = document.querySelector("#data-tab");
      this.tabData = new bootstrap.Tab(someTabTriggerEl);
      someTabTriggerEl = document.querySelector("#businesses-tab");
      this.tabBusinesses = new bootstrap.Tab(someTabTriggerEl);
    },
    setDataBaseProperty() {
      this.form.data = this.property;
    },
  },
  beforeMount() {},
  mounted() {
    this.getTypes();
    this.initialiseTabs();
    console.log("Id initial", this.id);
    if (this.id) {
      this.getProperty();
    }
  },
};
</script>

<style>
select {
  min-width: 200px;
}
</style>
