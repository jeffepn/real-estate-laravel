<template>
  <div>
    <div class="col-12 mb-2">
      <re-input
        placeholder="Nome*"
        :max-length="30"
        v-model.lazy="form.data.name"
        :error="form.hasError('name')"
        :error-message="form.firstError('name')"
        @pressEnter="submit"
      />
    </div>
    <div class="mt-2 text-end">
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
  name: "AddTypePerson",
  components: {
    ReInput,
  },
  data() {
    return {
      form: new Form({ name: null }),
    };
  },
  methods: {
    submit() {
      this.form.clearErrors();
      reaxios
        .post(reroute("jp_realestate.api.type_person.store"), this.form.data)
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
     eventBus.$emit("new-type-person");
      this.form.clearFields();
    },
  },
  mounted() {
   eventBus.$on("clear-add-type", () => {
      this.form.clearFields();
      this.form.clearErrors();
    });
  },
};
</script>

<style></style>
