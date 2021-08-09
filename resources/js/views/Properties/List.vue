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
          <a
            :href="$route('jp_realestate.property.create')"
            class="btn btn-outline-primary ms-2"
          >
            <i class="fas fa-plus"></i> Novo
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="content-table">
          <table class="table table-striped">
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
                      @click="active(property, false)"
                    >
                      <i class="fas fa-globe"></i>
                    </re-button>
                    <re-button
                      v-else
                      classes="btn btn-primary btn-sm ms-2"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Imóvel arquivado. Clique para publicar..."
                      @click="active(property, true)"
                    >
                      <i class="fas fa-archive"></i>
                    </re-button>
                    <a
                      class="btn btn-primary btn-sm"
                      :href="
                        $route('jp_realestate.property.edit', [property.id])
                      "
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
          :per-page="pagination.perPage"
          :total="total"
          :page="pagination.page"
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
      @ok="deleteBanner"
    >
      <p>Tem certeza da exclusão do banner?</p>
    </re-modal>
  </div>
</template>

<script>
import RePagination from "@/components/Controls/Pagination";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReModal from "@/components/Modal";
export default {
  components: { RePagination, ReButton, ReModal },
  data() {
    return {
      search: null,
      data: [],
      included: [],
      pagination: {
        perPage: 10,
        page: 1,
      },
      showModalDelete: false,
      idDelete: null,
    };
  },
  computed: {
    properties() {
      const initial = (this.pagination.page - 1) * this.pagination.perPage;
      const final = initial + this.pagination.perPage;
      return this.filteredProperties.slice(initial, final);
    },
    filteredProperties() {
      if (this.searchIsEmpty) {
        return this.originalProperties;
      }

      const result = this.originalProperties.filter(
        (item) =>
          //   item.business.name.search(
          //     new RegExp(this.search.replaceAll(" ", ".*"), "i"),
          //   ) !== -1 ||
          item.type.name.search(
            new RegExp(this.search.replaceAll(" ", ".*"), "i"),
          ) !== -1 ||
          item.sub_type.name.search(
            new RegExp(this.search.replaceAll(" ", ".*"), "i"),
          ) !== -1 ||
          item.slug.search(
            new RegExp(this.search.replaceAll(" ", ".*"), "i"),
          ) !== -1,
      );
      this.resetPage();
      return result;
    },
    originalProperties() {
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
    total() {
      return this.filteredProperties.length;
    },
    searchIsEmpty() {
      return !(this.search && this.search.trim().length);
    },
  },
  methods: {
    active(property, active = false) {
      this.$axios
        .patch(this.$route("jp_realestate.property.update", [property.id]), {
          active,
        })
        .then((response) => {
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
      this.$axios
        .get(this.$route("jp_realestate.property.index"))
        .then((response) => {
          this.data = response.data.data;
          this.included = response.data.included;
        })
        .catch((error) => {
          this.$toast.message(error.message, true);
        });
    },
    updatePage(page) {
      this.pagination.page = page;
    },
    updatePerPage(perPage) {
      this.pagination.perPage = perPage;
    },
    resetPage() {
      this.updatePage(1);
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
      return businessesProperty.map(function (businessProperty) {
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
    deleteBanner() {
      this.$axios
        .delete(this.$route("jp_realestate.property.destroy", [this.idDelete]))
        .then((response) => {
          this.data = this.data.filter(
            (element) => element.id !== this.idDelete,
          );
          this.idDelete = null;
          this.showModalDelete = false;
        })
        .catch((error) => {
          this.$toast.message(error.message, true);
        });
    },
  },
  async beforeMount() {
    await this.getProperties();
  },
  mounted() {
    window.tooltip();
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
