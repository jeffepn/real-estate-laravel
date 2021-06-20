<template>
  <div class="row">
    <div class="row align-items-center">
      <div class="col mb-2">
        <div class="form-floating">
          <input
            type="tel"
            v-mask="'#####-###'"
            class="form-control"
            id="floatingInputCep"
            v-model="formData.cep"
          />
          <label for="floatingInputCep">Cep</label>
        </div>
        <small
          class="text-danger"
          v-if="form.hasError('cep')"
          v-text="form.firstError('cep')"
        ></small>
      </div>
      <div class="col-auto mb-2 px-0">
        <button class="btn btn-outline-primary" type="button" @click="cep">
          Buscar
        </button>
      </div>
    </div>
    <div class="col-sm-6 mb-2">
      <div class="form-floating">
        <input
          type="text"
          class="form-control"
          id="floatingInputAddress"
          v-model="formData.address"
        />
        <label for="floatingInputAddress">Endereço</label>
      </div>
    </div>
    <div class="col-sm-6 mb-2 d-flex align-items-center">
      <div class="form-floating col">
        <input
          type="number"
          class="form-control"
          step="1"
          id="floatingInputNumber"
          v-model="formData.number"
        />
        <label for="floatingInputNumber">Nº</label>
      </div>

      <div class="form-check form-check-inline col-auto ms-2">
        <input
          class="form-check-input"
          type="checkbox"
          id="inlineCheckboxRent"
          :checked="notNumber"
        />
        <label class="form-check-label" for="inlineCheckboxRent">S/N</label>
      </div>
    </div>
    <div class="col-sm-6 mb-2">
      <div class="form-floating">
        <input
          type="text"
          class="form-control"
          id="floatingInputComplement"
          v-model="formData.complement"
        />
        <label for="floatingInputComplement">Complemento</label>
      </div>
    </div>
    <div class="col-sm-6 mb-2">
      <div class="form-floating">
        <input
          type="text"
          class="form-control"
          id="floatingInputNeighborhood"
          v-model="formData.neighborhood"
        />
        <label for="floatingInputNeighborhood">Bairro*</label>
      </div>
    </div>
    <div class="col-sm-6 mb-2">
      <div class="form-floating">
        <input
          type="text"
          class="form-control"
          id="floatingInputCity"
          v-model="formData.city"
        />
        <label for="floatingInputCity">Cidade*</label>
      </div>
    </div>
    <div class="col-sm-6 mb-2">
      <div class="form-floating">
        <input
          type="text"
          class="form-control"
          id="floatingInputState"
          v-model="formData.state"
        />
        <label for="floatingInputState">Estado*</label>
      </div>
    </div>
  </div>
</template>

<script>
import { getCep } from "@/supports/cep.js";
import { mask } from "vue-the-mask";
export default {
  directives: { mask },
  props: {
    form: {
      type: Object,
      require: true,
    },
  },
  data() {
    return {
      formData: {
        address: null,
        number: null,
        complement: null,
        neighborhood: null,
        city: null,
        state: null,
        cep: "37701524",
      },
      state: null,
      city: null,
    };
  },
  computed: {
    notNumber() {
      return this.formData.number ? false : true;
    },
  },
  methods: {
    async cep() {
      this.form.clearError("cep");
      let result = await getCep(this.formData.cep);
      if (result.error) {
        return this.form.setError("cep", result.message);
      }
      this.formData.address = result.data.logradouro;
      this.formData.neighborhood = result.data.bairro;
      this.formData.city = result.data.localidade;
      this.formData.state = result.data.uf;
    },
  },
  mounted() {},
};
</script>

<style></style>
