<template>
  <div class="container">
    <div v-show="!loadingMaster" class="card my-5">
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
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="media-tab"
              data-bs-target="#media"
              type="button"
              role="tab"
              aria-controls="media"
              aria-selected="false"
            >
              Media
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
            <re-form-details-property :form="form"></re-form-details-property>
            <div class="mb-2 col-12 d-flex justify-content-between">
              <div class="mb-2 col-auto text-start">
                <re-button
                  classes="btn btn-outline-secondary"
                  :loading="loadingBack"
                  @click="back('data')"
                >
                  <span aria-hidden="true">&laquo;</span> Voltar
                </re-button>
              </div>
              <div class="mb-2 col-auto text-end">
                <re-button :loading="loadingNext" @click="next('businesses')">
                  Próximo <span aria-hidden="true">&raquo;</span>
                </re-button>
              </div>
            </div>
          </div>
          <div class="tab-pane fade px-2" id="businesses" role="tabpanel">
            <re-business-property
              v-if="!loadingMaster"
              class="col-md-12 mb-2"
              :form="form"
            ></re-business-property>
            <div class="mb-2 col-12 d-flex justify-content-between">
              <div class="mb-2 col-auto text-start">
                <button
                  class="btn btn-outline-secondary"
                  @click="back('details')"
                >
                  <span aria-hidden="true">&laquo;</span> Voltar
                </button>
              </div>
              <div class="mb-2 col-auto text-end">
                <re-button :loading="loadingNext" @click="next('media')">
                  Próximo <span aria-hidden="true">&raquo;</span>
                </re-button>
              </div>
            </div>
          </div>
          <div class="tab-pane fade px-2" id="media" role="tabpanel">
            <re-media-property
              v-if="!loadingMaster"
              :form="form"
            ></re-media-property>
            <div class="mb-2 col-12 d-flex flex-wrap justify-content-between">
              <div class="mb-2 col-auto text-start">
                <button
                  class="btn btn-outline-secondary"
                  @click="back('businesses')"
                >
                  <span aria-hidden="true">&laquo;</span> Voltar
                </button>
              </div>
              <div class="col-auto text-end">
                <re-button
                  v-if="!form.data.active"
                  classes="btn btn-success mb-2 "
                  :loading="loadingNext"
                  @click="save('media')"
                >
                  Salvar Rascunho <i class="fas fa-save"></i>
                </re-button>
                <re-button
                  v-if="!form.data.active"
                  class="mb-2"
                  :loading="loadingNext"
                  @click="publish('media')"
                >
                  Publicar <i class="fas fa-globe"></i>
                </re-button>
                <re-button
                  v-if="form.data.active"
                  class="mb-2"
                  :loading="loadingNext"
                  @click="save('media')"
                >
                  Salvar <i class="fas fa-save"></i>
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
import Form from "@/supports/form.js";
import ReFormDataProperty from "@/components/Forms/Property/DataProperty.vue";
import ReFormDetailsProperty from "@/components/Forms/Property/DetailsProperty.vue";
import ReBusinessProperty from "@/components/Forms/Property/BusinessProperty.vue";
import ReMediaProperty from "@/components/Forms/Property/MediaProperty.vue";
import ReInput from "@/components/Controls/Inputs/Input";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReLoading from "@/components/Loading";
export default {
  components: {
    ReFormDataProperty,
    ReFormDetailsProperty,
    ReBusinessProperty,
    ReMediaProperty,
    ReInput,
    ReButton,
    ReLoading,
  },
  props: {
    id: {
      type: String,
      default: null,
    },
    tab: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      loadingNext: false,
      loadingBack: false,
      loadingMaster: true,
      idProperty: this.id,
      property: null,
      form: this.initialiseInitialForm(),
      tabDetails: null,
      tabData: null,
      tabBusinesses: null,
      tabMedia: null,
      type_id: null,
    };
  },
  watch: {
    property() {
      this.initialiseProperty();
    },
  },
  computed: {
    edit() {
      return this.idProperty !== null;
    },
    title() {
      return this.edit ? "Editar Imóvel" : "Novo Imóvel";
    },
    request() {},
  },
  methods: {
    initialiseInitialForm() {
      return new Form({
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
        useful_area: 0,
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
        businesses: [],
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
        case "media":
          this.tabMedia.show();
          break;
      }
      let url = new URL(location.href);
      url.searchParams.set("tab", tab);
      this.setUrlHistory(url.href);
    },
    setTabWithErrors() {
      let keyError = this.form.firstKeyErrorAny();
      switch (keyError) {
        case "sub_type_id":
        case "code":
        case "cep":
        case "address":
        case "number":
        case "complement":
        case "neighborhood":
        case "city":
        case "initials":
          this.setTabShow("data");
          break;
        case "building_area":
        case "total_area":
        case "min_dormitory":
        case "max_dormitory":
        case "min_suite":
        case "max_suite":
        case "min_bathroom":
        case "max_bathroom":
        case "min_garage":
        case "max_garage":
        case "min_description":
        case "content":
          this.setTabShow("details");
          break;
        case "embed":
          this.setTabShow("media");
          break;
        default:
          this.setTabShow("businesses");
          break;
      }
    },
    setUrlHistory(url) {
      history.pushState({}, null, url);
    },
    back(tab) {
      this.loadingBack = true;
      this.setTabShow(tab);
      this.loadingBack = false;
    },
    next(tab) {
      this.loadingNext = true;
      this.form.clearErrors();
      this.submit(this.form.data, tab);
    },
    save() {
      this.loadingNext = true;
      this.form.clearErrors();
      this.submit(this.form.data);
    },
    async publish() {
      this.loadingNext = true;
      this.form.clearErrors();
      let data = Object.assign({ active: true }, this.form.data);
      await this.submit(data, "media");
      this.active();
    },
    submit(data, tab = null) {
      let request = this.edit
        ? this.$axios.patch(
            this.$route("jp_realestate.property.update", [this.idProperty]),
            data,
          )
        : this.$axios.post(this.$route("jp_realestate.property.store"), data);
      request
        .then((response) => {
          if (!this.edit) {
            this.setProperty(response.data);
          }
          if (!tab) {
            location.href = this.$route("jp_realestate.property.list");
          }
          this.setTabShow(tab);
        })
        .catch(({ response }) => {
          if (response && response.status === 422) {
            this.form.errors = response.data.errors;
            return this.setTabWithErrors();
          }
          if (response) {
            this.$toast.message({
              type: "danger",
              message: response.data.message,
            });
          }
        })
        .finally(() => (this.loadingNext = false));
    },

    active() {
      this.$axios
        .patch(
          this.$route("jp_realestate.property.update", [this.idProperty]),
          {
            active: true,
          },
        )
        .then((response) => {
          location.href = this.$route("jp_realestate.property.list");
        })
        .catch(({ response }) => {
          if (response) {
            this.$toast.message({
              type: "danger",
              message: response.data.message,
            });
          }
        });
    },
    initialiseProperty() {
      this.idProperty = this.property.id;
      this.setUrlHistory(
        this.$route("jp_realestate.property.edit", [this.idProperty]),
      );
      this.setDataBaseProperty();
    },
    setProperty({ data, included }) {
      let situation = included.find((element) => element.type === "situation");
      let subType = included.find((element) => element.type === "sub_type");
      let address = included.find((element) => element.type === "address");
      let neighborhood = included.find(
        (element) => element.type === "neighborhood",
      );
      let city = included.find((element) => element.type === "city");
      let state = included.find((element) => element.type === "state");
      let businessesProperty = included.filter(
        (element) => element.type === "business_property",
      );
      this.property = {
        id: data.id,
        slug: data.attributes.slug,
        situation_id: situation.id,
        sub_type_id: subType.id,
        code: data.attributes.code,
        building_area: data.attributes.building_area,
        total_area: data.attributes.total_area,
        useful_area: data.attributes.useful_area,
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
        embed: data.attributes.embed,
        active: data.attributes.active,
        address: address.attributes.address,
        number: address.attributes.number,
        not_number: address.attributes.not_number,
        complement: address.attributes.complement,
        neighborhood: neighborhood.attributes.name,
        city: city.attributes.name,
        initials: state.attributes.initials,
        businesses: businessesProperty.length
          ? businessesProperty.map((element) => {
              return {
                id: element.attributes.business_id,
                value: element.attributes.value,
              };
            })
          : [],
      };
      this.type_id = subType.relationships.type.data.id;
    },
    async getProperty() {
      await this.$axios
        .get(this.$route("jp_realestate.property.show", [this.id]))
        .then((response) => this.setProperty(response.data));
    },
    initialiseTabs() {
      let someTabTriggerEl = document.querySelector("#details-tab");
      this.tabDetails = new bootstrap.Tab(someTabTriggerEl);
      someTabTriggerEl = document.querySelector("#data-tab");
      this.tabData = new bootstrap.Tab(someTabTriggerEl);
      someTabTriggerEl = document.querySelector("#businesses-tab");
      this.tabBusinesses = new bootstrap.Tab(someTabTriggerEl);
      someTabTriggerEl = document.querySelector("#media-tab");
      this.tabMedia = new bootstrap.Tab(someTabTriggerEl);
      if (this.tab) {
        this.setTabShow(this.tab);
      }
    },
    setDataBaseProperty() {
      this.form.data = this.property;
    },
  },
  async mounted() {
    if (this.id) {
      await this.getProperty();
    }
    this.initialiseTabs();
    this.loadingMaster = false;
  },
};
</script>

<style>
select {
  min-width: 200px;
}
</style>
