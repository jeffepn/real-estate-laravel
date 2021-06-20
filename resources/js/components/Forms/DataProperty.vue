<template>
  <div class="row mt-2">
    <div class="col-sm-6 col-md-auto mb-2">
      <re-choose-type v-model="type_id" :create="true"></re-choose-type>
    </div>
    <div class="col-sm-6 col-md-auto mb-2">
      <re-choose-sub-type
        :type-id.sync="type_id"
        v-model="request.sub_type_id"
        :create="true"
      ></re-choose-sub-type>
    </div>
    <div class="col-12 mt-2">
      <h6>Negócio*</h6>
    </div>
    <div class="col-md-12 mb-2">
      <div class="form-check form-check-inline">
        <input
          class="form-check-input"
          type="checkbox"
          id="inlineCheckboxRent"
          v-model="form.rent"
        />
        <label class="form-check-label" for="inlineCheckboxRent">Aluguel</label>
      </div>
      <div class="form-check form-check-inline">
        <input
          class="form-check-input"
          type="checkbox"
          id="inlineCheckboxSale"
          v-model="form.sale"
        />
        <label class="form-check-label" for="inlineCheckboxSale">Venda</label>
      </div>
    </div>
    <div class="row collapse" :class="{ show: showPrices }">
      <div class="col-sm-6 col-md-auto" v-show="form.rent">
        <div class="form-floating mb-3">
          <input
            class="form-control"
            v-model="form.price_rent"
            type="tel"
            v-money="money"
            id="floatingPriceRent"
          />
          <label for="floatingPriceRent">Preço de aluguel</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-auto" v-show="form.sale">
        <div class="form-floating mb-3">
          <input
            class="form-control"
            v-model="form.price_sale"
            type="tel"
            v-money="money"
            id="floatingPriceSale"
          />
          <label for="floatingPriceSale">Preço de venda</label>
        </div>
      </div>
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
export default {
  directives: { money: VMoney },
  components: {
    ReAddress,
    ReChooseType,
    ReChooseSubType,
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
  computed: {
    showPrices() {
      return this.form.rent || this.form.sale;
    },
  },
  watch: {
    type_id(newValue) {},
  },
};
</script>

<style></style>
