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
          Marque essa opção para adicionar uma marca <strong>d'água</strong> automática a suas imagens.<br />
          Será necessário carregar a marca d'água previamente, antes da escolha de suas imagens.                    
        </small>
      </div>
      <div v-if="useWatterMark" class="col-13 mb-3 d-flex flex-column gap-3">
        <div>
          <i>
            <b class="text-danger"> Obs:</b> Existe suporte a posicionamento da imagem,
            escolha a <strong>posição</strong> pretendida para aplicar nas imagens a serem carregadas.            
          </i>
        </div>
        <radio-button-position-watermark v-model="watermarkPosition" />
      </div>
      <div class="col-auto mb-3">
        <re-edit-watter-mark 
          v-on:load-image-wattermark="(imgWatermarkSetting) => this.imageWatermark = {
              url: imgWatermarkSetting.attributes.value.image
          }"
        />
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
            :disabled="loadingFiles"
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
      <div v-if="loadingFiles" class="section_loading_files">
        <div>
          <i class="fas fa-spinner fa-pulse fa-3x me-2"></i>
        </div>
        <div>
          <h4>Carregando arquivos</h4>
        </div>
      </div>
      <div v-for="(message, index) in Object.keys(errors)" :key="index">
        <b>Imagem(ns) com erro:</b> {{ errors[message].join(" | ") }}
        <p class="text-danger" v-text="message"></p>
      </div>
    </div>
    <div class="row justify-content-between align-content-center mt-2 mb-4">
      <div
        class="col-sm-8 d-flex justify-content-center flex-column text-start"
      >
        <div class="w-100 ">
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="checkAll"
              id="flexCheckAll"
              :disabled="loadingFiles"
            />
            <label
              class="form-check-label"
              for="flexCheckAll"
              v-text="checkAll ? 'Desmarcar tudo' : 'Marcar tudo'"
            >
            </label>
          </div>
        </div>
        <small
          class="text-danger mt-2"
          v-if="errorBulk"
          v-text="messageBulk"
        ></small>
      </div>
      <div class="col-sm-4 text-end">
        <re-button-float
          :actions="actionsBulk"
          v-on:bulk-event="bulkEvent"
          data-bs-toggle="tooltip"
          data-bs-placement="left"
          data-bs-title="Ações em massa"
          :disabled="loadingFiles"
        />
      </div>
    </div>
    <div
      class="col-12 position-relative py-3"
      :class="{ 'loading-row-files': loadingFiles }"
    >
      <div class="row">
        <div
          class="color-item col-auto mb-2 position-relative"
          v-for="(image, index) in images"
          :key="index"
          v-dragging="{ item: image, list: images, group: 'image' }"
        >
          <re-item-image-media
            :ref="`${prefixImage}${image.id}`"
            :image="image"
            :haveWatermark="haveWatermark"
            v-on:deleted-image="(id)=> removeImageOfContext([id])"
            v-on:add-image-wattermark="intChangeImageWatermark"
          />
        </div>
      </div>
    </div>
    <re-modal-image-watermark 
      id="modal-image-watermark-property" 
      :show="showModalImageWatermark"
      :image="imageToWatermark"
      :imageWatermark="imageWatermark"
      v-on:ok="finishChangeImageWatermark"      
      v-on:close="closeChangeImageWatermark"
    />
  </div>
</template>

<script>
import ReInput from "@/components/Controls/Inputs/Input";
import ReCheckbox from "@/components/Controls/Inputs/Checkbox";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReEditWatterMark from "@/components/Entities/AppSettings/WatterMark/Edit";
import ReButtonFloat from "@/components/Buttons/ButtonFloat";
import ReItemImageMedia from "@/components/Images/ItemImageMedia";
import ReSelect from "@/components/Controls/Inputs/Select";
import ReModalImageWatermark from "@/components/Modals/ModalImageWatermark";
import RadioButtonPositionWatermark from '@/components/Controls/RadioButtons/RadioButtonPositionWatermark.vue';

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
    ReButtonFloat,
    ReItemImageMedia,
    ReSelect,
    ReModalImageWatermark,
    RadioButtonPositionWatermark,
  },
  watch: {
    checkAll(newValue) {
      this.updateCheckAllImages(newValue);
    },
  },
  computed: {
    haveWatermark() {
      return !!this.imageWatermark;
    }
  },
  data() {
    return {
      images: [],
      loadingFiles: false,
      useWatterMark: false,
      errors: {},
      prefixImage: "item-image-media-",
      selectedActionBulk: null,
      errorBulk: false,
      messageBulk: "Selecione pelo menos um item para aplicar a ação.",
      actionsBulk: [
        {
          label: "Download",
          slug: "download",
          icon: '<i class="fas fa-download"></i>',
        },
        {
          label: "Excluir",
          slug: "delete",
          icon: '<i class="fas fa-trash"></i>',
        },
      ],
      checkAll: false,
      showModalImageWatermark: false,
      imageToWatermark: null,
      imageWatermark: null,
      watermarkPosition: "center",
    };
  },
  methods: {
    getImages() {
      if (!this.form.data.id) {
        return;
      }
      reaxios
        .get(reroute("jp_realestate.api.image_property.index"), {
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
                thumbnail: currentValue.attributes.thumbnail,
                alt: currentValue.attributes.alt,
              });
              return acumulator;
            },
            [],
          );
        });
    },
    async onFileChange(e) {
      this.errors = {};
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.loadingFiles = true;
      await this.setImages(files);
      this.loadingFiles = false;
    },
    async setImages(files) {
      const errors = {};
      for (var index = 0; index < files.length; index++) {
        const result = await this.uploadImage(files[index], index + 1);

        if (result.image) {
          this.images.push(result.image);
          continue;
        }

        if (!result.error) {
          continue;
        }

        result.error.errors.forEach((item) => {
          if (errors.hasOwnProperty(item)) {
            errors[item].push(result.error.name);
          } else {
            errors[item] = [result.error.name];
          }
        });
      }

      if (Object.keys(errors).length > 0) {
        this.errors = errors;
        this.$toast.message({
          message: "Confira os erros do upload das imagens.",
          type: "danger",
        });
      }
    },
    async uploadImage(file, index) {
      let dataForm = new FormData();
      dataForm.append("property_id", this.form.data.id);
      dataForm.append("use_watter_mark", this.useWatterMark);
      dataForm.append("watermark_position", this.watermarkPosition);
      dataForm.append("images[]", file);

      let result = { error: null, image: null };
      await reaxios
        .post(reroute("jp_realestate.api.image_property.store"), dataForm, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          response.data.data.forEach((image) => {
            result.image = {
              id: image.id,
              way: image.attributes.way,
              thumbnail: image.attributes.thumbnail,
              alt: image.attributes.alt,
            };
          });
        })
        .catch(({ response }) => {
          let message = "Algo deu errado no upload das imagens.";
          if (response && response.status === 422) {
            let keysErrors = Object.keys(response.data.errors);
            result.error = {
              name: file.name,
              errors: response.data.errors[keysErrors[0]],
            };
            return;
          }
          if (response && response.status === 413) {
            message = "Verifique o tamanho total do arquivos, muito grandes.";
          }

          this.$toast.message({
            message,
            type: "danger",
          });
        });
      return result;
    },
    updateOrderOfImages(data) {
      reaxios.patch(reroute("jp_realestate.api.image_property.update_order"), {
        orders: data,
      });
    },
    base64ToFile(base64String, nameBaseFile) {
      const [metadata, base64Data] = base64String.split(',');
      const mime = metadata.match(/:(.*?);/)[1];

      const extension = mime.split('/')[1];
      const nameFile = `${nameBaseFile}.${extension}`;

      const byteCharacters = atob(base64Data);
      const byteNumbers = Array.from(byteCharacters, char => char.charCodeAt(0));
      const byteArray = new Uint8Array(byteNumbers);

      return new File([byteArray], nameFile, { type: mime });
    }, 
    updateImage(image) {
      const file = this.base64ToFile(image.image, 'teste');
      let dataForm = new FormData();
      dataForm.append("image", file);
      dataForm.append("_method", 'PATCH');
      reaxios.post(
        reroute("jp_realestate.api.image_property.update", image.id), 
        dataForm,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );
    },
    removeImageOfContext(ids) {
      this.images = this.images.filter((element) => !ids.includes(element.id));
    },
    bulkEvent(action) {
      this.errorBulk = false;
      const ids = [];
			this.images.forEach((image) => {
				const ref = `${this.prefixImage}${image.id}`;
				if (this.$refs[ref][0] && this.$refs[ref][0].checked) {
					ids.push(image.id);
				}
			});

			if (!ids.length) {
				this.errorBulk = true;
				return;
			}

      switch (action.slug) {
        case "download":
          this.bulkDownload(ids);
          break;
        case "delete":
          this.bulkDelete(ids);
          break;
      }
    },
    bulkDownload(ids) {
      let url = `${reroute("jp_realestate.image_property.bulk_download")}?`;

      ids.forEach((el) => (url += `ids[]=${el}&`));

      window.location.assign(url.toString());
    },
    bulkDelete(ids) {
			reaxios
				.delete(
					reroute("jp_realestate.api.image_property.bulk_destroy", { ids: ids })
				)
				.then(({ data: { ids: currentIds } }) => {
					this.removeImageOfContext(currentIds);
				});
			this.updateCheckAllImages(false);
		},
		updateCheckAllImages(checked) {
			this.images.forEach((image) => {
				const ref = `${this.prefixImage}${image.id}`;
				if (this.$refs[ref][0]) {
					this.$refs[ref][0].checked = checked;
				}
			});
      if(!checked) {
        this.checkAll = false;
      }
		},    
    intChangeImageWatermark(image) {
      this.showModalImageWatermark = true;
      this.imageToWatermark = image;
    },
    closeChangeImageWatermark() {
      this.imageToWatermark = null;
      this.showModalImageWatermark = false;
    },
    finishChangeImageWatermark(image) {
      this.images = this.images.map((img) => {
        if(img.id === image.id) {
          img.way = image.image;
          img.thumbnail = image.image;
        }
        return img;
      })
      this.imageToWatermark = null;
      this.showModalImageWatermark = false;
      this.updateImage(image);
    }
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
    retooltip();
  },
};
</script>

<style lang="scss" scoped>
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
  input[name="fileImage"]:disabled + .square-upload-add {
    background: #adadad;
    i {
      color: #5f5d5d;
    }
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
.section_loading_files {
  display: flex;
  flex-direction: column;
  gap: 20px;
  align-items: center;
  i {
    color: gray;
  }
}
.loading-row-files {
  &::after {
    content: "";
    background-color: white;
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    opacity: 0.7;
  }
}
</style>
