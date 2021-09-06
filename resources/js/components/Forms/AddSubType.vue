<template>
  <div class="d-flex flex-wrap justify-content-center">
    <div class="col-12 mb-2">
      <re-choose-type
        v-model="form.data.type_id"
        :error="form.hasError('type_id')"
        :error-message="form.firstError('type_id')"
      ></re-choose-type>
    </div>
    <div class="col-12 mb-2">
      <re-input
        placeholder="Nome*"
        :max-length="30"
        v-model.lazy="form.data.name"
        :error="form.hasError('name')"
        :error-message="form.firstError('name')"
      ></re-input>
    </div>
    <div class="mt-2 col-12 text-end">
      <button type="button" class="btn btn-primary" @click="submit">
        Salvar
      </button>
    </div>
  </div>
</template>
<script>
import Form from "@/supports/form.js";
import ReInput from "@/components/Controls/Inputs/Input";
import ReChooseType from "@/components/Entities/Choose/Type";
export default {
  name: "AddSubType",
  components: {
    ReInput,
    ReChooseType,
  },
  props: {
    show: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      form: new Form({
        type_id: null,
        name: null,
      }),
    };
  },
  watch: {
    show() {
      if (this.show) {
        this.$eventBus.$emit("reload-add-type");
      }
    },
  },
  methods: {
    submit() {
      this.form.clearErrors();
      this.$axios
        .post(this.$route("jp_realestate.sub-type.store"), this.form.data)
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
      this.form.clearErrors();
      this.form.clearFields();
    },
  },
  mounted() {
    this.$eventBus.$on("clear-add-sub-type", () => {
      this.form.clearFields();
      this.form.clearErrors();
    });
  },
};
</script>

<style></style>
