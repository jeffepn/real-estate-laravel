<template>
  <div>
    <div class="mb-3">
      <label for="formFileImage" class="form-label"> Imagem </label>
      <img class="mb-3" v-if="image" :src="image" alt="" height="60" />
      <input
        class="form-control"
        type="file"
        id="formFileImage"
        @change="onFileChange"
      />
      <div
        v-if="form.hasError('image')"
        class="text-danger"
        v-text="form.firstError('image')"
      ></div>
    </div>
    <re-input
      class="mb-2"
      placeholder="Título"
      :max-length="150"
      v-model.lazy="form.data.title"
      :error="form.hasError('title')"
      :error-message="form.firstError('title')"
    ></re-input>
    <re-input
      class="mb-2"
      placeholder="Sub Título"
      :max-length="250"
      v-model.lazy="form.data.content"
      :error="form.hasError('content')"
      :error-message="form.firstError('content')"
    ></re-input>
    <re-input
      class="mb-2"
      placeholder="Link para redirecionamento"
      :max-length="250"
      v-model.lazy="form.data.link"
      :error="form.hasError('link')"
      :error-message="form.firstError('link')"
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
  name: "FormBanner",
  props: {
    banner: {
      type: Object,
      default: null,
    },
  },
  components: {
    ReInput,
  },
  data() {
    return {
      form: new Form({
        title: null,
        content: null,
        link: null,
        image: null,
      }),
    };
  },
  watch: {
    banner(newValue) {
      this.initialiseData();
      this.form.clearErrors();
    },
  },
  computed: {
    edit() {
      return this.banner ? true : false;
    },
    image() {
      if (this.form.data.image) {
        return URL.createObjectURL(this.form.data.image);
      }
      if (this.banner && this.banner.way) {
        return this.banner.wayUrl;
      }
      return null;
    },
  },
  methods: {
    submit() {
      this.form.clearErrors();
      let request = this.request();
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
    request() {
      let data = Object.assign({}, this.form.data);
      let formData = new FormData();
      Object.keys(data).forEach((key) =>
        formData.append(key, data[key] ? data[key] : ""),
      );
      if (this.edit) {
        formData.append("_method", "PUT");
      }
      return this.edit
        ? this.$axios.post(
            this.$route("jp_realestate.banner.update", [this.banner.id]),
            formData,
          )
        : this.$axios.post(this.$route("jp_realestate.banner.store"), formData);
    },
    successSubmit(data) {
      this.$emit("submitSuccess", data);
      if (!this.edit) {
        this.form.clearFields();
      }
    },
    async onFileChange(e) {
      this.errors = [];
      if (!e.target.files.length) {
        return;
      }
      this.form.data.image = e.target.files[0];
    },
    initialiseData() {
      if (this.banner) {
        this.form.data.title = this.banner.title;
        this.form.data.content = this.banner.content;
        this.form.data.link = this.banner.link;
      } else {
        this.form.data.image = null;
        this.form.data.title = null;
        this.form.data.content = null;
        this.form.data.link = null;
      }
    },
  },
  mounted() {
    this.$eventBus.$on("clear-add-business", () => {
      this.form.clearFields();
      this.form.clearErrors();
    });
  },
};
</script>

<style></style>
