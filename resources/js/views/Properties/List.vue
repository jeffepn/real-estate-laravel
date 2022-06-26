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
        <h2>Imóveis</h2>
        <div class="d-flex flex-wrap">
          <div class="input-search input-group">
            <input
              type="text"
              class="form-control"
              placeholder="Procurar"
              aria-label="Procurar"
              aria-describedby="basic-addon2"
              v-model="search"
            />
            <span class="input-group-text" id="basic-addon2">
              <i class="fas fa-search"></i>
            </span>
          </div>
          <a :href="urlCreate" class="btn btn-outline-primary ms-2">
            <i class="fas fa-plus"></i> Novo
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="content-table">
          <div
            class="d-flex justify-content-center text-center p-4"
            v-if="propertiesIsEmpty || loadingProperties"
          >
            <div v-if="propertiesIsEmpty && !loadingProperties">
              <ph-table :size="80" weight="thin" />
              <p>
                Não encontramos imóveis, com os termos de busca.
              </p>
            </div>
            <div v-if="loadingProperties">
              <ph-circle-notch :size="80" weight="thin">
                <animateTransform
                  attributeName="transform"
                  attributeType="XML"
                  type="rotate"
                  dur="0.5s"
                  from="0 0 0"
                  to="360 0 0"
                  repeatCount="indefinite"
                />
              </ph-circle-notch>
              <p>
                Carregando...
              </p>
            </div>
          </div>
          <table
            class="table table-striped"
            v-if="propertiesIsNotEmpty && !loadingProperties"
          >
            <thead>
              <tr>
                <th scope="col">Código</th>
                <th scope="col">Endereço</th>
                <th scope="col">Negócio</th>
                <th scope="col">Tipo - Subtipo</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="property in properties" :key="property.id">
                <th>
                  <div class="actions">
                    <span v-text="`${property.code} - `"></span>
                    <re-button
                      classes="btn btn-success btn-sm ms-2"
                      v-if="property.active"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Imóvel publicado. Clique para arquivar..."
                      @click="setActive(property, false)"
                    >
                      <i class="fas fa-globe"></i>
                    </re-button>
                    <re-button
                      v-else
                      classes="btn btn-primary btn-sm ms-2"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Imóvel arquivado. Clique para publicar..."
                      @click="setActive(property, true)"
                    >
                      <i class="fas fa-archive"></i>
                    </re-button>
                    <a
                      class="btn btn-primary btn-sm"
                      :href="generateUrlEdit(property.id)"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Editar"
                    >
                      <i class="fas fa-edit"></i>
                    </a>
                    <button
                      class="btn btn-danger btn-sm"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Excluir"
                      @click="openDelete(property.id)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </th>
                <td v-text="formateAddress(property)"></td>
                <td v-text="businessesOfProperty(property)"></td>
                <td
                  v-text="`${property.type.name} - ${property.sub_type.name}`"
                ></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer">
        <re-pagination
          :per-page="parseInt(meta.per_page)"
          :total="meta.total"
          :page="meta.current_page"
          @changePage="updatePage"
          @changePerPage="updatePerPage"
        ></re-pagination>
      </div>
    </div>
    <re-modal
      :show="showModalDelete"
      title="Atenção!"
      text-button-cancel="Cancelar"
      text-button-ok="Sim"
      @close="idDelete = null"
      @cancel="idDelete = null"
      @ok="deleteProperty"
    >
      <p>Tem certeza da exclusão do imóvel?</p>
    </re-modal>
  </div>
</template>

<script>
import { active } from "@/supports/property.js";
import RePagination from "@/components/Controls/Pagination";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReModal from "@/components/Modal";
import { PhTable, PhCircleNotch } from "phosphor-vue";
export default {
  components: { RePagination, ReButton, ReModal, PhTable, PhCircleNotch },
  data() {
    return {
      search: null,
      data: [],
      included: [],
      meta: {
        per_page: 10,
        current_page: 1,
        total: 0,
      },
      pagination: {
        perPage: 10,
        page: 1,
      },
      showModalDelete: false,
      idDelete: null,
      debounceSearch: null,
      timeoutDebounce: 500,
      loadingProperties: true,
    };
  },
  watch: {
    search() {
      clearTimeout(this.debounceSearch);
      this.debounceSearch = setTimeout(() => {
        this.getProperties();
      }, this.timeoutDebounce);
    },
  },
  computed: {
    properties() {
      return this.data.reduce((acumulator, currentValue) => {
        let { id, attributes, relationships } = currentValue;

        let property = Object.assign({}, { id }, attributes);
        property.businesses = this.extractBusiness(relationships);
        property.sub_type = this.extractSubType(relationships);
        property.type = this.extractType(relationships);
        property.address = this.extractAdress(relationships);
        acumulator.push(property);
        return acumulator;
      }, []);
    },
    propertiesIsEmpty() {
      return !this.propertiesIsNotEmpty;
    },
    propertiesIsNotEmpty() {
      return this.properties.length;
    },
    searchIsNotEmpty() {
      return this.search && this.search.trim().length;
    },
    urlCreate() {
      return window.reroute("jp_realestate.property.create");
    },
  },
  methods: {
    active,
    setActive(property, active = false) {
      this.active(property.id, active)
        .then(() => {
          this.data = this.data.map((element) => {
            if (element.id === property.id) {
              element.attributes.active = active;
            }
            return element;
          });
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
    getProperties() {
      this.loadingProperties = true;
      const params = {
        paginate: this.meta.per_page,
        page: this.meta.current_page,
      };
      if (this.searchIsNotEmpty) params.search = this.search;

      reaxios
        .get(window.reroute("jp_realestate.property.index"), {
          params,
        })
        .then((response) => {
          this.data = response.data.data;
          console.log("data", this.data);
          this.included = response.data.included;
          this.meta = response.data.meta;
        })
        .catch(({ response }) => {
          if (!response) return;

          this.$toast.message({
            type: "danger",
            message: response.data.message,
          });
        })
        .finally(() => (this.loadingProperties = false));
    },
    updatePage(page) {
      this.meta.current_page = page;
      this.getProperties();
    },
    updatePerPage(perPage) {
      this.meta.current_page = 1;
      this.meta.per_page = perPage;
      this.getProperties();
    },
    formateAddress(property) {
      return `${property.address.neighborhood}, ${property.address.city} - ${property.address.initials}`;
    },
    businessesOfProperty(property) {
      return property.businesses.reduce((acumulator, currentValue) => {
        acumulator +=
          acumulator.trim().length === 0
            ? ` ${currentValue.name} `
            : ` | ${currentValue.name} `;
        return acumulator;
      }, "");
    },
    extractBusiness(relationships) {
      let businessesProperty = relationships.businesses;
      let included = this.included;
      return businessesProperty.map(function(businessProperty) {
        let businessPropertyFind = included.find(
          (element) =>
            element.type === "business_property" &&
            element.id === businessProperty.data.id,
        );
        let businessFind = included.find(
          (element) =>
            element.type === "business" &&
            element.id === businessPropertyFind.attributes.business_id,
        );
        return {
          id: businessFind.id,
          name: businessFind.attributes.name,
        };
      });
    },
    extractSubType(relationships) {
      let subType = this.included.find((element) => {
        return (
          element.type === "sub_type" &&
          element.id === relationships.sub_type.data.id
        );
      });
      return Object.assign({}, { id: subType.id }, subType.attributes);
    },
    extractType(relationships) {
      let subType = this.included.find((element) => {
        return (
          element.type === "sub_type" &&
          element.id === relationships.sub_type.data.id
        );
      });
      let type = this.included.find((element) => {
        return (
          element.type === "type" &&
          element.id === subType.relationships.type.data.id
        );
      });
      return Object.assign({}, { id: type.id }, type.attributes);
    },
    extractAdress(relationships) {
      let address = this.included.find((element) => {
        return (
          element.type === "address" &&
          element.id === relationships.address.data.id
        );
      });
      let neighborhood = this.included.find((element) => {
        return (
          element.type === "neighborhood" &&
          element.id === address.relationships.neighborhood.data.id
        );
      });
      let city = this.included.find((element) => {
        return (
          element.type === "city" &&
          element.id === neighborhood.relationships.city.data.id
        );
      });
      let state = this.included.find((element) => {
        return (
          element.type === "state" &&
          element.id === city.relationships.state.data.id
        );
      });
      let result = Object.assign({}, { id: address.id }, address.attributes);
      result.neighborhood = neighborhood.attributes.name;
      result.city = city.attributes.name;
      result.state = state.attributes.name;
      result.initials = state.attributes.initials;
      return result;
    },
    openDelete(id) {
      this.idDelete = id;
      this.showModalDelete = true;
    },
    deleteProperty() {
      reaxios
        .delete(
          window.reroute("jp_realestate.property.destroy", [this.idDelete]),
        )
        .then((response) => {
          this.data = this.data.filter(
            (element) => element.id !== this.idDelete,
          );
          this.idDelete = null;
        })
        .catch(({ response }) => {
          if (!response) return;

          this.$toast.message({
            type: "danger",
            message: response.data.message,
          });
        })
        .finally(() => (this.showModalDelete = false));
    },
    generateUrlEdit(propertyId) {
      return window.reroute("jp_realestate.property.edit", [propertyId]);
    },
  },
  async beforeMount() {
    await this.getProperties();
  },
  mounted() {
    window.retooltip();
  },
};
</script>

<style lang="scss" scoped>
.content-table {
  max-width: 100%;
  overflow-x: auto;
}
.actions {
  display: flex;
  flex-wrap: nowrap;
  a {
    margin: 0 0.25rem;
  }
  span {
    white-space: nowrap;
  }
}
.input-search {
  max-width: 250px;
}
</style>
