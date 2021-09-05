<template>
  <div class="mt-2">
    <div class="col-12 mb-2">
      <re-input
        type="url"
        v-model="form.data.embed"
        placeholder="Url para vídeo"
        :error="form.hasError('embed')"
        :error-message="form.firstError('embed')"
      ></re-input>
    </div>
    <div class="col-12 mb-2">
      <strong>Imagens</strong>
    </div>
    <div class="col-12 mb-2">
      <div class="mb-3">
        <label for="formFileImage" class="form-label">
          Adicionar imagens
        </label>
        <input
          class="form-control"
          type="file"
          id="formFileImage"
          multiple
          @change="onFileChange"
        />
      </div>
    </div>
    <div class="col-12">
      <p v-if="loadingFiles">
        <i class="fas fa-spinner fa-pulse me-2"></i> Carregando arquivos...
      </p>
      <p class="text-danger" v-if="errors.length" v-text="errors[0]"></p>
    </div>
    <div class="row">
      <div
        class="color-item col-auto mb-2 position-relative"
        v-for="(image, index) in images"
        :key="index"
        v-dragging="{ item: image, list: images, group: 'image' }"
      >
        <div
          class="item-image"
          :style="`background-image: url(${image.way})`"
        ></div>
        <re-button
          classes="btn btn-danger btn-circle button-trash"
          @click="removeImage(image.id)"
        >
          <i class="fas fa-trash"></i>
        </re-button>
      </div>
    </div>
  </div>
</template>

<script>
import ReInput from "@/components/Controls/Inputs/Input";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";

export default {
  name: "MediaProperty",
  props: {
    form: {
      type: Object,
      required: true,
    },
  },
  components: {
    ReInput,
    ReButton,
  },
  data() {
    return {
      images: [],
      loadingFiles: false,
      errors: [],
    };
  },
  methods: {
    getImages() {
      if (!this.form.data.id) {
        return;
      }
      this.$axios
        .get(
          this.$route("jp_realestate.property.index-image_property", [
            this.form.data.id,
          ]),
        )
        .then((response) => {
          this.images = response.data.data.reduce(
            (acumulator, currentValue) => {
              acumulator.push({
                id: currentValue.id,
                way: currentValue.attributes.way,
                alt: currentValue.attributes.alt,
              });
              return acumulator;
            },
            [],
          );
        });
    },
    async onFileChange(e) {
      this.errors = [];
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.loadingFiles = true;
      for await (let file of files) {
        this.setImage(file);
      }
      this.loadingFiles = false;
    },
    async setImage(file) {
      let dataForm = new FormData();
      dataForm.append("image", file);
      dataForm.append("property_id", this.form.data.id);
      await this.$axios
        .post(this.$route("jp_realestate.image_property.store"), dataForm, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          setTimeout(() => {}, 2000);
          this.images.push({
            id: response.data.data.id,
            way: response.data.data.attributes.way,
            alt: response.data.data.attributes.alt,
          });
        })
        .catch(({ response }) => {
          if (response && response.status === 422) {
            let keysErrors = Object.keys(response.data.errors);
            this.errors = response.data.errors[keysErrors[0]];
          }
        });
    },
    removeImage(id) {
      this.$axios
        .delete(this.$route("jp_realestate.image_property.destroy", [id]))
        .then(() => {
          this.images = this.images.filter((element) => element.id !== id);
        });
    },
    updateOrderOfImages(data) {
      this.$axios.patch(
        this.$route("jp_realestate.image_property.update_order"),
        {
          orders: data,
        },
      );
    },
  },
  mounted() {
    this.getImages();
    this.$dragging.$on("dragend", () => {
      let data = this.images.map((image, index) => {
        return {
          id: image.id,
          order: index,
        };
      });
      this.updateOrderOfImages(data);
    });
  },
};
</script>

<style lang="scss" scoped>
.button-trash {
  position: absolute;
  right: 0;
  top: -5px;
}
.item-image {
  width: 150px;
  height: 150px;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
}
</style>
