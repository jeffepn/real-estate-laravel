<template>
  <div>
    <re-checkbox
      v-for="(business, id) in businesses"
      :key="id"
      :label="business.label"
      v-model="business.checked"
      @input="handleInput"
    ></re-checkbox>
  </div>
</template>

<script>
import ReCheckbox from "@/components/Controls/Inputs/Checkbox";
export default {
  components: {
    ReCheckbox,
  },
  props: {
    form: {
      type: Object,
      require: true,
    },
  },
  data() {
    return {
      originalBusinesses: [],
    };
  },
  methods: {
    async getBusinesses() {
      await this.$axios
        .get(this.$route("jp_realestate.business.index"))
        .then((response) => {
          this.originalBusinesses = response.data.data;
        });
    },
    handleInput(value) {
      console.log("Business = ", this.businesses);
    },
  },
  watch: {
    businesses(newValue) {
      console.log("Novo valor kkk = ", newValue);
      console.log("Businesses", Object.keys(newValue));
    },
  },
  computed: {
    businesses() {
      return this.originalBusinesses.reduce((acumulator, currentValue) => {
        acumulator[currentValue.id] = {
          label: currentValue.attributes.name,
          checked: false,
          value: 0,
        };
        return acumulator;
      }, {});
    },
  },
  mounted() {
    this.getBusinesses();
  },
};
</script>

<style></style>
