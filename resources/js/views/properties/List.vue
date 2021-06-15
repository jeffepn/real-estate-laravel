<template>
  <div class="container">
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
</template>

<script>
export default {
  data() {
    return {
      data: [],
      included: [],
    };
  },
  computed: {
    properties() {
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
    formateAddress(property) {
      console.log(property);
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
</style>
