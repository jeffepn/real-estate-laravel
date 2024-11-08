<template>
  <div class="d-flex flex-wrap justify-content-center">
    <div class="col-12 mb-2">
      <re-choose-type
        v-model.lazy="form.data.type_id"
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
        @pressEnter="submit"
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
  name: "EditSubType",
  components: {
    ReInput,
    ReChooseType,
  },
  props: {
    id: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      form: new Form({
        type_id: null,
        name: null,
      }),
      subType: null,
    };
  },
  methods: {
    submit() {
      this.form.clearErrors();
      reaxios
        .patch(
          reroute("jp_realestate.api.sub_type.update", this.id), 
          this.form.data
        )
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
    },
    async getSubType() {
      await reaxios(reroute("jp_realestate.api.sub_type.show", this.id)).then(
        ({ data }) => {
          console.log("SUB TYPE", data);
          this.subType = data.data;
          this.form.data.name = this.subType.attributes.name;
          this.form.data.type_id = this.subType.relationships.type.data.id;
        },
      );
    },
  },
  mounted() {
   this.getSubType();
  },
};
</script>

<style></style>
