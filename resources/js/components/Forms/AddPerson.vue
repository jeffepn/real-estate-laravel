<template>
  <div>
    <div class="col-12 mb-2">
      <re-choose-type-person
        v-model="form.data.type_person_id"
        :error="form.hasError('type_person_id')"
        :error-message="form.firstError('type_person_id')"
      />
    </div>
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
import ReChooseTypePerson from "@/components/Entities/Choose/TypePerson";
export default {
  name: "AddPerson",
  components: {
    ReInput,
    ReChooseTypePerson,
  },
  data() {
    return {
      form: new Form({
        type_person_id: null,
        name: null,
      }),
    };
  },
  methods: {
    submit() {
      this.form.clearErrors();
      reaxios
        .post(window.reroute("jp_realestate.api.person.store"), this.form.data)
        .then((response) => {
          this.$toast.message({
            message: response.data.message,
            type: "success",
          });
          console.log("Then", response);
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
      this.form.clearFields();
    },
  },
  mounted() {
    window.eventBus.$on("clear-add-type-person", () => {
      this.form.clearFields();
      this.form.clearErrors();
    });
  },
};
</script>

<style></style>
