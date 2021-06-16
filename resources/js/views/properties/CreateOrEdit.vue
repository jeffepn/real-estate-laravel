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
      <div class="card-body">
        <div class="row">
          <div class="col-6 col-md-3 mb-2">
            <div class="form-floating">
              <select
                class="form-select"
                id="floatingSelect"
                aria-label="Floating label select example"
              >
                <option selected>Open this select menu</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
              <label for="floatingSelect">Tipo</label>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-2">
            <div class="form-floating">
              <select
                class="form-select"
                id="floatingSelect"
                aria-label="Floating label select example"
              >
                <option selected>Open this select menu</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
              <label for="floatingSelect">Sub Tipo</label>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-2">
            <div class="form-floating">
              <input
                type="number"
                class="form-control"
                step="1"
                id="floatingInputBuildingArea"
                v-model="form.building_area"
              />
              <label for="floatingInputBuildingArea">Área construída</label>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-2">
            <div class="form-floating">
              <input
                type="number"
                class="form-control"
                step="1"
                id="floatingInputTotalArea"
                v-model="form.total_area"
              />
              <label for="floatingInputTotalArea">Área total</label>
            </div>
          </div>
        </div>
        <div class="card mt-3">
          <div class="card-body">
            <h6>Negócio</h6>
            <div class="row ">
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
              <div class="col-sm-6 col-md-auto" v-show="form.rent">
                <div class="form-floating mb-3">
                  <money
                    class="form-control"
                    v-model="form.price_rent"
                    v-bind="money"
                    id="floatingPriceRent"
                  ></money>
                  <label for="floatingPriceRent">Preço de aluguel</label>
                </div>
              </div>
              <div class="col-sm-6 col-md-auto" v-show="form.sale">
                <div class="form-floating mb-3">
                  <money
                    class="form-control"
                    v-model="form.price_sale"
                    v-bind="money"
                    id="floatingPriceSale"
                  ></money>
                  <label for="floatingPriceSale">Preço de venda</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-3 flex-wrap">
          <div class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2  row">
            <p class="col-12">Dormitórios</p>
            <div class="form-floating col-5 pe-1">
              <input
                type="number"
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
                type="number"
                class="form-control"
                step="1"
                id="floatingInputMaxDormitory"
                v-model="form.max_dormitory"
              />
              <label for="floatingInputMaxDormitory">Max</label>
            </div>
          </div>
          <div class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2  row">
            <p class="col-12">Suítes</p>
            <div class="form-floating col-5 pe-1">
              <input
                type="number"
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
                type="number"
                class="form-control"
                step="1"
                id="floatingInputMaxSuite"
                v-model="form.max_suite"
              />
              <label for="floatingInputMaxSuite">Max</label>
            </div>
          </div>
          <div class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2  row">
            <p class="col-12">Banheiros</p>
            <div class="form-floating col-5 pe-1">
              <input
                type="number"
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
                type="number"
                class="form-control"
                step="1"
                id="floatingInputMaxBathroom"
                v-model="form.max_bathroom"
              />
              <label for="floatingInputMaxBathroom">Max</label>
            </div>
          </div>
          <div class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2  row">
            <p class="col-12">Garagens</p>
            <div class="form-floating col-5 pe-1">
              <input
                type="number"
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
                type="number"
                class="form-control"
                step="1"
                id="floatingInputMaxGarage"
                v-model="form.max_garage"
              />
              <label for="floatingInputMaxGarage">Max</label>
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
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Money } from "v-money";
import CKEditor from "@ckeditor/ckeditor5-vue2";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
export default {
  components: {
    Money,
    ckeditor: CKEditor.component,
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
        // The configuration of the editor.
      },
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "R$ ",
        precision: 2,
        masked: false,
      },
      form: {
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
    };
  },
  watch: {
    "form.content"(newValue) {
      console.log(newValue);
    },
  },
  computed: {
    edit() {
      console.log("Id =", this.id);
      return this.id !== null;
    },
    title() {
      return this.edit ? "Editar Imóvel" : "Novo Imóvel";
    },
  },
  mounted() {
    this.editor
      .create(document.querySelector("#editor"), {
        removePlugins: ["CKFinder"],
        toolbar: [
          "bold",
          "italic",
          "bulletedList",
          "numberedList",
          "blockQuote",
        ],
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style></style>
