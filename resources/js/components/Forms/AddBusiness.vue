<template>
  <div>
    <div class="mb-2">
      <re-input
        placeholder="Nome*"
        :max-length="30"
        v-model.lazy="form.data.name"
        :error="form.hasError('name')"
        :error-message="form.firstError('name')"
        @pressEnter="$emit('pressEnter')"
      ></re-input>
    </div>
    <div class="mb-2">
      <re-input
        placeholder="Nome que representa o negócio finalizado*"
        :max-length="30"
        v-model.lazy="form.data.name_completed"
        :error="form.hasError('name_completed')"
        :error-message="form.firstError('name_completed')"
        @pressEnter="$emit('pressEnter')"
      ></re-input>
    </div>
    <div class="text-end">
      <button type="button" class="btn btn-primary" @click="submit">
        Salvar
      </button>
    </div>
  </div>
</template>

<script>
import Form from "@/supports/form.js";
import ReInput from "@/components/Controls/Inputs/Input";
export default {
  name: "AddBusiness",
  components: {
    ReInput,
  },
  data() {
    return {
      form: new Form({ name: null, name_completed: null }),
      business: null,
    };
  },
  props: {
    businessId: null,
  },
  computed: {
    isEdit() {
      return this.businessId ? true : false;
    },
  },
  watch: {
    businessId(newValue) {
      if (newValue) {
        this.getBusiness();
      } else {
        this.business = null;
      }
    },
  },
  methods: {
    submit() {
      this.form.clearErrors();
      const request = this.isEdit
        ? reaxios.patch(
            reroute("jp_realestate.api.business.update", this.businessId),
            this.form.data,
          )
        : reaxios.post(
            reroute("jp_realestate.api.business.store"),
            this.form.data,
          );
      request
        .then((response) => {
          this.$toast.message({
            message: response.data.message,
            type: "success",
          });
          this.successSubmit(response.data.data);
        })
        .catch((error) => {
          const { response } = error;
          if (response && response.status == 422) {
            this.form.errors = response.data.errors;
            return;
          }
          this.$toast.message({
            message: response.data.message,
            type: "danger",
          });
        });
    },
    successSubmit(data) {
      this.$emit("submitSuccess", data);
      if (!this.isEdit) this.form.clearFields();
    },
    getBusiness() {
      reaxios
        .get(reroute("jp_realestate.api.business.show", this.businessId))
        .then((response) => {
          const { id, attributes } = response.data.data;
          this.business = Object.assign({ id }, attributes);
          this.form.data.name = this.business.name;
          this.form.data.name_completed = this.business.name_completed;
        })
        .catch((error) => {
          const { response } = error;
          this.$toast.message({
            message: response.data.message,
            type: "danger",
          });
        });
    },
  },
  mounted() {
    eventBus.$on("clear-add-business", () => {
      this.form.clearFields();
      this.form.clearErrors();
    });
  },
};
</script>
