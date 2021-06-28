<template>
  <div class="mt-2">
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
export default {
  components: {
    ReAddress,
    ReChooseType,
    ReChooseSubType,
    ReInput,
  },
  props: {
    form: {
      type: Object,
      require: true,
    },
  },
  data() {
    return {
      type_id: null,
    };
  },
};
</script>

<style></style>
