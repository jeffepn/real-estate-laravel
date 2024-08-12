<template>
  <div>
    <re-input
      placeholder="Nome*"
      :max-length="30"
      v-model.lazy="form.data.name"
      :error="form.hasError('name')"
      :error-message="form.firstError('name')"
      @pressEnter="submit"
    ></re-input>
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
  name: "EditType",
  components: {
    ReInput,
  },
  props: {
    id: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      form: new Form({ name: null }),
      type: null,
    };
  },
  methods: {
    submit() {
      this.form.clearErrors();
      reaxios
        .patch(
          reroute("jp_realestate.api.type.update", this.id),
          this.form.data,
        )
        .then((response) => {
          this.$toast.message({
            message: response.data.message,
            type: "success",
          });
          this.successSubmit();
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
    successSubmit() {
      this.$emit("submitSuccess");
    },
    async getType() {
      await reaxios(reroute("jp_realestate.api.type.show", this.id)).then(
        ({ data }) => {
          this.type = data.data;
          this.form.data.name = this.type.attributes.name;
        },
      );
    },
  },
  mounted() {
    this.getType();
  },
};
</script>

<style></style>
