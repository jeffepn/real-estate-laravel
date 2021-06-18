<template>
  <div class="container">
    <div class="card my-5">
      <div
        class="card-header d-flex flex-wrap justify-content-between align-items-center"
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
              class="nav-link "
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
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active px-2" id="data" role="tabpanel">
            <div class="row mt-2">
              <div class="col-sm-6 col-md-auto mb-2">
                <div class="form-floating">
                  <select
                    class="form-select"
                    id="floatingSelectType"
                    v-model="type_id"
                  >
                    <option
                      v-for="type in types"
                      :key="type.id"
                      :value="type.id"
                      v-text="type.label"
                    ></option>
                  </select>
                  <label for="floatingSelectType">Tipo</label>
                </div>
              </div>
              <div class="col-sm-6 col-md-auto mb-2">
                <div class="form-floating">
                  <select
                    class="form-select"
                    id="floatingSelectSubType"
                    v-model="form.sub_type_id"
                  >
                    <option
                      v-for="subType in subTypes"
                      :key="subType.id"
                      :value="subType.id"
                      v-text="subType.label"
                    ></option>
                  </select>
                  <label for="floatingSelectSubType">Sub Tipo</label>
                </div>
              </div>
            </div>
            <h6 class="mt-2">Negócio</h6>
            <div class="row">
              <div class="col-md-12 mb-2">
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    id="inlineCheckboxRent"
                    v-model="form.rent"
                  />
                  <label class="form-check-label" for="inlineCheckboxRent"
                    >Aluguel</label
                  >
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    id="inlineCheckboxSale"
                    v-model="form.sale"
                  />
                  <label class="form-check-label" for="inlineCheckboxSale"
                    >Venda</label
                  >
                </div>
              </div>
              <div class="row collapse" :class="{ show: showPrices }">
                <div class="col-sm-6 col-md-auto" v-show="form.rent">
                  <div class="form-floating mb-3">
                    <input
                      class="form-control"
                      v-model="form.price_rent"
                      type="tel"
                      v-money="money"
                      id="floatingPriceRent"
                    />
                    <label for="floatingPriceRent">Preço de aluguel</label>
                  </div>
                </div>
                <div class="col-sm-6 col-md-auto" v-show="form.sale">
                  <div class="form-floating mb-3">
                    <input
                      class="form-control"
                      v-model="form.price_sale"
                      type="tel"
                      v-money="money"
                      id="floatingPriceSale"
                    />
                    <label for="floatingPriceSale">Preço de venda</label>
                  </div>
                </div>
              </div>
            </div>
            <h6 class="mt-2">Endereço</h6>
            <re-address :form="formCreateEdit"></re-address>

            <div class="mb-2 col-12 text-end">
              <button class="btn btn-primary" @click="next">
                Próximo <span aria-hidden="true">&raquo;</span>
              </button>
            </div>
          </div>
          <div class="tab-pane fade px-2" id="details" role="tabpanel">
            <div class="row mt-2 ">
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
                class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex flex-column"
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
                class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2  d-flex flex-column"
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
                class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex flex-column"
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
                class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2  d-flex flex-column"
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
                <button class="btn btn-outline-secondary" @click="back">
                  <span aria-hidden="true">&laquo;</span> Voltar
                </button>
                <button class="btn btn-primary" @click="submit">Salvar</button>
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
import ReAddress from "@/components/forms/Address";
import Form from "@/supports/form.js";
import { VMoney } from "v-money";
import { mask } from "vue-the-mask";
export default {
  directives: { mask, money: VMoney },
  components: {
    ckeditor: CKEditor.component,
    ReAddress,
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
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "R$ ",
        precision: 2,
        masked: false,
      },
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
      formCreateEdit: new Form(),
      form: {
        sub_type_id: null,
        sale: false,
        rent: false,
        price_sale: 0,
        price_rent: 0,
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
        content: "<p>Content of the editor.</p>",
      },
      originalTypes: [],
      originalTypesIncluded: [],
      type_id: null,
    };
  },
  watch: {
    "form.content"(newValue) {
      console.log(newValue);
    },
  },
  computed: {
    edit() {
      return this.id !== null;
    },
    title() {
      return this.edit ? "Editar Imóvel" : "Novo Imóvel";
    },
    showPrices() {
      return this.form.rent || this.form.sale;
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
          console.log(response.data);
          this.originalTypes = response.data.data;
          this.originalTypesIncluded = response.data.included;
        });
    },
    back() {
      var someTabTriggerEl = document.querySelector("#data-tab");
      var tab = new bootstrap.Tab(someTabTriggerEl);
      tab.show();
    },
    next() {
      var someTabTriggerEl = document.querySelector("#details-tab");
      var tab = new bootstrap.Tab(someTabTriggerEl);
      tab.show();
    },
    submit() {},
  },
  mounted() {
    this.getTypes();
  },
};
</script>

<style>
select {
  min-width: 200px;
}
</style>
