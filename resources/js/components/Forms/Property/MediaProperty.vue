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
        <re-checkbox
          label="Usar Marca D'água"
          v-model="useWatterMark"
        ></re-checkbox>
        <small>
          Caso seja necessário a incorporação de uma
          <strong> marca d'água</strong> a suas imagens, será necessário
          carregá-la antes da escolha de suas imagens.
          <br />
          <i>
            <b class="text-danger"> Obs:</b> No momento, somente existe suporte
            para a <strong>centralização</strong> da marca d'água.
          </i>
        </small>
      </div>
      <div class="col-auto mb-3" v-show="useWatterMark">
        <re-edit-watter-mark></re-edit-watter-mark>
      </div>
    </div>

    <div class="col-12 mb-2">
      <strong> Adicionar imagens </strong>
    </div>
    <div class="col-12 mb-2">
      <div class="mb-3">
        <div class="square-upload">
          <input
            type="file"
            name="fileImage"
            id="fileImage"
            ref="fileImage"
            accept="image/png, image/jpeg"
            multiple
            @change="onFileChange"
          />
          <label class="square-upload-add" for="fileImage">
            <i class="fas fa-upload"></i>
          </label>
        </div>
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
import ReCheckbox from "@/components/Controls/Inputs/Checkbox";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReEditWatterMark from "@/components/Entities/AppSettings/WatterMark/Edit";

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
    ReCheckbox,
    ReEditWatterMark,
  },
  data() {
    return {
      images: [],
      loadingFiles: false,
      useWatterMark: false,
      errors: [],
    };
  },
  methods: {
    getImages() {
      if (!this.form.data.id) {
        return;
      }
      reaxios
        .get(window.reroute("jp_realestate.image_property.index"), {
          params: {
            property_id: this.form.data.id,
          },
        })
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
      //   const filesUpload = [];
      //   for await (let file of files) {
      //     this.setImage(file);
      //     // filesUpload.push(file);
      //   }
      //   this.setImage(filesUpload);
      this.setImage(files);
      this.loadingFiles = false;
    },
    async setImage(files) {
      let dataForm = new FormData();
      for (var index = 0; index < files.length; index++) {
        dataForm.append("images[]", files[index]);
      }
      //   dataForm.append("images", files);
      //   dataForm.append("images", files[0]);
      //   dataForm.append("images[]", files);
      dataForm.append("property_id", this.form.data.id);
      dataForm.append("use_watter_mark", this.useWatterMark);
      await reaxios
        .post(window.reroute("jp_realestate.image_property.store"), dataForm, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          response.data.data.forEach((image) => {
            this.images.push({
              id: image.id,
              way: image.attributes.way,
              alt: image.attributes.alt,
            });
          });
        })
        .catch(({ response }) => {
          let message = "Algo deu errado no upload das imagens.";
          if (response && response.status === 422) {
            let keysErrors = Object.keys(response.data.errors);
            this.errors = response.data.errors[keysErrors[0]];
            message = "Confira os erros do upload das imagens.";
          }
          if (response && response.status === 413) {
            message = "Verifique o tamanho total do arquivos, muito grandes.";
          }

          this.$toast.message({
            message,
            type: "danger",
          });
        });
    },
    removeImage(id) {
      reaxios
        .delete(window.reroute("jp_realestate.image_property.destroy", [id]))
        .then(() => {
          this.images = this.images.filter((element) => element.id !== id);
        });
    },
    updateOrderOfImages(data) {
      reaxios.patch(
        window.reroute("jp_realestate.image_property.update_order"),
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
$sizeSquareWatterMark: 50px;
.square-upload {
  width: $sizeSquareWatterMark;
  height: $sizeSquareWatterMark;
  background: gray;
  color: #000;
  position: relative;
  display: flex;
  img {
    width: auto;
    height: auto;
    max-width: 150px;
    max-height: 300px;
  }
  input[name="fileImage"] {
    display: none;
  }
  &-add {
    width: 100%;
    min-height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }
  &-buttons-actions {
    background: inherit;
    color: inherit;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    position: absolute;
    border-radius: 50%;
    cursor: pointer;
  }
  &-edit {
    @extend .square-upload-buttons-actions;
    $position: -5px;
    right: $position;
    top: $position;
    background: blue;
    color: #fff;
  }
  &-delete {
    @extend .square-upload-buttons-actions;
    $position: -5px;
    right: $position;
    bottom: $position;
    background: red;
    color: #fff;
  }
}
</style>
