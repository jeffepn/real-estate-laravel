<template>
  <div>
    <div class="square-upload">
      <img v-if="existWatterMark" :src="imageViewWatterMark" />
      <input
        type="file"
        name="fileWatterMark"
        id="fileWatterMark"
        ref="fileWatterMark"
        accept="image"
        @change="setWatterMark"
      />
      <label
        v-show="!existWatterMark"
        class="square-upload-add"
        for="fileWatterMark"
        @click="setOptionFunctionUpload('add')"
      >
        <i class="fas fa-plus"></i>
      </label>
      <label
        for="fileWatterMark"
        class="square-upload-edit"
        v-show="existWatterMark"
        @click="setOptionFunctionUpload('edit')"
      >
        <i class="fas fa-pen"></i>
      </label>
      <label
        class="square-upload-delete"
        v-show="existWatterMark"
        @click="destroyWatterMark"
      >
        <i class="fas fa-trash"></i>
      </label>
    </div>
    <p class="text-danger" v-text="this.form.firstError('image_watter')"></p>
  </div>
</template>

<script>
import Form from "@/supports/form.js";
import { WATTERMARK_IMAGE_PROPERTY } from "@/enums/app_settings.js";
export default {
  computed: {
    existWatterMark() {
      return this.watterMark ? true : false;
    },
    edit() {
      return this.functionUpload === "edit";
    },
    imageViewWatterMark() {
      return this.existWatterMark
        ? this.watterMark.attributes.value.image
        : null;
    },
    urlPostImage() {
      return this.edit
        ? reroute("jp_realestate.app_setting.update", [
            WATTERMARK_IMAGE_PROPERTY,
          ])
        : reroute("jp_realestate.app_setting.store");
    },
  },
  data: () => ({
    watterMark: null,
    functionUpload: "add",
    headers: {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    },
    form: new Form({}),
  }),
  methods: {
    upload() {},
    setOptionFunctionUpload(option = "add") {
      this.functionUpload = option;
    },
    async setWatterMark(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.form.errors = [];
      await reaxios
        .post(this.urlPostImage, this.dataRequest(files[0]), this.headers)
        .then((response) => {
          this.watterMark = response.data.data;
        })
        .catch(({ response }) => {
          if (response && response.status === 422) {
            this.form.errors = response.data.errors;
          }
        });
    },
    dataRequest(file) {
      let dataForm = new FormData();
      dataForm.append("image_watter", file);
      dataForm.append("name", WATTERMARK_IMAGE_PROPERTY);
      if (this.edit) {
        dataForm.append("_method", "PATCH");
      }
      return dataForm;
    },
    async destroyWatterMark() {
      await reaxios
        .delete(
          reroute("jp_realestate.app_setting.destroy", [
            WATTERMARK_IMAGE_PROPERTY,
          ]),
        )
        .then(() => {
          this.watterMark = null;
        });
    },
    async getWatterMark() {
      await reaxios
        .get(
          reroute("jp_realestate.app_setting.show", [
            WATTERMARK_IMAGE_PROPERTY,
          ]),
        )
        .catch(({ response }) => {
          if (response && response.status !== 404)
            this.$toast.message({
              message: response.data.message,
              type: "danger",
            });
        })
        .then((response) => {
          if (response) this.watterMark = response.data.data;
        });
    },
  },
  created() {
    this.getWatterMark();
  },
};
</script>

<style lang="scss" scoped>
$sizeSquareWatterMark: 80px;
.square-upload {
  min-width: $sizeSquareWatterMark;
  min-height: $sizeSquareWatterMark;
  max-width: 150px;
  max-height: 300px;
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
  input[name="fileWatterMark"] {
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
