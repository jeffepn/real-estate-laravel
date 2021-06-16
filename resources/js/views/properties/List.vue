<template>
  <div class="container">
    <div class="card my-5">
      <div
        class="card-header d-flex flex-wrap justify-content-between align-items-center"
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
                    <a class="btn btn-success btn-sm" href="#" role="button">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a class="btn btn-primary btn-sm" href="#" role="button">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a class="btn btn-danger btn-sm" href="#" role="button">
                      <i class="fas fa-trash"></i>
                    </a>
                  </div>
                </th>
                <td v-text="formateAddress(property)"></td>
                <td v-text="property.business.name"></td>
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
  </div>
</template>

<script>
import RePagination from "@/components/Controls/Pagination";
export default {
  components: { RePagination },
  data() {
    return {
      search: null,
      data: [],
      included: [],
      pagination: {
        perPage: 10,
        page: 1,
      },
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
          item.business.name.search(
            new RegExp(this.search.replaceAll(" ", ".*"), "i"),
          ) !== -1 ||
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
        property.business = this.extractBusiness(relationships);
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
  mounted() {
    this.getProperties();
  },
  methods: {
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
      return `${property.address.neighborhood}, ${property.address.city} - ${property.address.state}`;
    },
    extractBusiness(relationships) {
      let business = this.included.find((element) => {
        return (
          element.type === "business" &&
          element.id === relationships.business.data.id
        );
      });
      return Object.assign({}, { id: business.id }, business.attributes);
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
      return result;
    },
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
