<template>
  <div class="row">
    <div class="d-flex align-items-center">
      <div class="col pe-3 mb-2">
        <div class="form-floating">
          <input
            type="tel"
            v-mask="'#####-###'"
            class="form-control"
            id="floatingInputCep"
            v-model="form.data.cep"
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
        <re-button
          :loading="loadingSearchCep"
          classes="btn btn-outline-primary"
          type="button"
          @click="cep"
        >
          Buscar
        </re-button>
      </div>
    </div>
    <div class="col-sm-6 mb-2">
      <re-input
        :max-length="100"
        v-model="form.data.address"
        placeholder="Endereço"
        :error="form.hasError('address')"
        :error-message="form.firstError('address')"
      ></re-input>
    </div>
    <div class="col-sm-6 mb-2 d-flex align-items-center">
      <re-input
        class="col"
        v-model="form.data.number"
        placeholder="Nº"
        :error="form.hasError('number')"
        :error-message="form.firstError('number')"
        type="number"
        :precision="0"
        :masked="false"
      ></re-input>

      <div class="form-check form-check-inline col-auto ms-2">
        <input
          class="form-check-input"
          type="checkbox"
          id="inlineCheckboxRent"
          disabled
          :checked="notNumber"
        />
        <label class="form-check-label" for="inlineCheckboxRent">S/N</label>
      </div>
    </div>
    <div class="col-sm-6 mb-2">
      <re-input
        :max-length="15"
        v-model="form.data.complement"
        placeholder="Complemento"
        :error="form.hasError('complement')"
        :error-message="form.firstError('complement')"
      ></re-input>
    </div>
    <div class="col-sm-6 mb-2">
      <re-input
        :max-length="100"
        v-model="form.data.neighborhood"
        placeholder="Bairro*"
        :error="form.hasError('neighborhood')"
        :error-message="form.firstError('neighborhood')"
      ></re-input>
    </div>
    <div class="col-sm-6 mb-2">
      <re-input
        :max-length="100"
        v-model="form.data.city"
        placeholder="Cidade*"
        :error="form.hasError('city')"
        :error-message="form.firstError('city')"
      ></re-input>
    </div>
    <div class="col-sm-6 mb-2">
      <re-input
        :max-length="2"
        v-model="form.data.initials"
        placeholder="Estado*"
        :error="form.hasError('initials')"
        :error-message="form.firstError('initials')"
      ></re-input>
    </div>
  </div>
</template>

<script>
import { getCep } from "@/supports/cep.js";
import { mask } from "vue-the-mask";
import ReInput from "@/components/Controls/Inputs/Input";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
export default {
  directives: { mask },
  components: {
    ReInput,
    ReButton,
  },
  props: {
    form: {
      type: Object,
      require: true,
    },
  },
  data() {
    return {
      loadingSearchCep: false,
    };
  },
  computed: {
    notNumber() {
      return this.form.data.number ? false : true;
    },
  },
  watch: {
    notNumber(newValue) {
      this.form.data.not_number = newValue;
    },
  },
  methods: {
    async cep() {
      this.loadingSearchCep = true;
      this.form.clearError("cep");
      let result = await getCep(this.form.data.cep);
      this.loadingSearchCep = false;
      if (result.error) {
        return this.form.setError("cep", result.message);
      }
      this.form.data.address = result.data.logradouro;
      this.form.data.neighborhood = result.data.bairro;
      this.form.data.city = result.data.localidade;
      this.form.data.initials = result.data.uf;
    },
  },
  mounted() {
    this.form.data.not_number = this.form.data.number ? false : true;
  },
};
</script>

<style></style>
