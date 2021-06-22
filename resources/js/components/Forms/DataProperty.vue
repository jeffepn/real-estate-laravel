<template>
  <div class="row mt-2">
    <div class="col-sm-6 col-md-auto mb-2">
      <re-choose-type v-model="type_id" :create="true"></re-choose-type>
    </div>
    <div class="col-sm-6 col-md-auto mb-2">
      <re-choose-sub-type
        :type-id="type_id"
        v-model="form.data.sub_type_id"
        :create="true"
      ></re-choose-sub-type>
    </div>
    <div class="col-12 mt-2">
      <h6>Endereço</h6>
    </div>
    <re-address :form="form"></re-address>
  </div>
</template>

<script>
import { VMoney } from "v-money";
import ReAddress from "@/components/Forms/Address";
import ReChooseType from "@/components/Entities/Choose/Type";
import ReChooseSubType from "@/components/Entities/Choose/SubType";
import ReInput from "@/components/Controls/Inputs/Input";
export default {
  directives: { money: VMoney },
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
      //Config v-money - money Brazil
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "R$ ",
        precision: 2,
        masked: false,
      },
      request: {
        sub_type_id: null,
      },
      type_id: null,
      //   type_id: "8254d908-3c38-4637-b6d9-ffa18e4c434b",
    };
  },
  watch: {
    type_id(newValue) {},
  },
};
</script>

<style></style>
