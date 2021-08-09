<template>
  <div class="mt-2">
    <div class="row">
      <div class="col-sm-6 col-md-auto mb-2">
        <re-input
          placeholder="Código"
          v-model="form.data.code"
          :error="form.hasError('code')"
          :error-message="form.firstError('code')"
        ></re-input>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 col-md-auto mb-2">
        <re-choose-type v-model="type_id" :create="true"></re-choose-type>
      </div>
      <div class="col-sm-6 col-md-auto mb-2">
        <re-choose-sub-type
          :type-id="type_id"
          v-model="form.data.sub_type_id"
          :create="true"
          :error="form.hasError('sub_type_id')"
          :error-message="form.firstError('sub_type_id')"
        ></re-choose-sub-type>
      </div>
      <div class="col-sm-6 col-md-auto mb-2">
        <re-select
          placeholder="Situação"
          :select-first="true"
          :data="situations"
          v-model="form.data.situation_id"
          :error="form.hasError('situation_id')"
          :error-message="form.firstError('situation_id')"
        ></re-select>
      </div>
    </div>
    <div class="col-12 mt-2">
      <h6>Endereço</h6>
    </div>
    <re-address :form="form"></re-address>
  </div>
</template>

<script>
import ReAddress from "@/components/Forms/Address";
import ReChooseType from "@/components/Entities/Choose/Type";
import ReChooseSubType from "@/components/Entities/Choose/SubType";
import ReInput from "@/components/Controls/Inputs/Input";
import ReSelect from "@/components/Controls/Inputs/Select";
export default {
  components: {
    ReAddress,
    ReChooseType,
    ReChooseSubType,
    ReInput,
    ReSelect,
  },
  props: {
    form: {
      type: Object,
      require: true,
    },
    typeId: {
      type: String,
      default: null,
    },
  },
  computed: {
    situations() {
      return this.situationsRequest.reduce((acumulator, currentValue) => {
        acumulator.push({
          value: currentValue.id,
          label: currentValue.attributes.name,
        });
        return acumulator;
      }, []);
    },
  },
  watch: {
    typeId(newValue) {
      this.type_id = newValue;
    },
  },
  data() {
    return {
      type_id: this.typeId,
      situationsRequest: [],
    };
  },
  created() {
    this.getSituations();
  },
  methods: {
    async getSituations() {
      await this.$axios(this.$route("jp_realestate.situation.index")).then(
        ({ data }) => {
          this.situationsRequest = data.data;
        },
      );
    },
  },
};
</script>

<style></style>
