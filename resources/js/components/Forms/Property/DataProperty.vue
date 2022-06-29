<template>
  <div class="mt-2">
    <div class="row">
      <div class="col-sm-6 col-md-auto mb-2">
        <re-input
          placeholder="Código"
          v-model="form.data.code"
          :error="form.hasError('code')"
          :error-message="form.firstError('code')"
          @pressEnter="$emit('pressEnter')"
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
        <div class="col-sm-6 col-md-auto mb-2">
          <re-choose-situation
            :type-id="type_id"
            v-model="form.data.situation_id"
            :create="true"
            :error="form.hasError('situation_id')"
            :error-message="form.firstError('situation_id')"
          ></re-choose-situation>
        </div>
      </div>
    </div>
    <div class="col-12 mt-2">
      <h6>Endereço</h6>
    </div>
    <re-address :form="form" @pressEnter="$emit('pressEnter')"></re-address>
  </div>
</template>

<script>
import ReAddress from "@/components/Forms/Address";
import ReChooseType from "@/components/Entities/Choose/Type";
import ReChooseSubType from "@/components/Entities/Choose/SubType";
import ReChooseSituation from "@/components/Entities/Choose/Situation";
import ReInput from "@/components/Controls/Inputs/Input";
import ReSelect from "@/components/Controls/Inputs/Select";
export default {
  components: {
    ReAddress,
    ReChooseType,
    ReChooseSubType,
    ReChooseSituation,
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
      await reaxios(window.reroute("jp_realestate.api.situation.index")).then(
        ({ data }) => {
          this.situationsRequest = data.data;
        },
      );
    },
  },
};
</script>

<style></style>
