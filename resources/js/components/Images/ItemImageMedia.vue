<template>
  <div>
    <div
      :class="classImage"
      :style="
        `background-image: url(${
          image.thumbnail ? image.thumbnail : image.way
        })`
      "
      @click="checked = !checked"
    ></div>
    <ReButton
      classes="btn btn-danger btn-circle button-trash"
      @click="removeImage(image.id)"
    >
      <i class="fas fa-trash"></i>
    </ReButton>
    <ReButton
      v-if="haveWatermark"
      classes="btn btn-primary btn-circle button-edit"
      @click="addImageWatermark"
    >
      <i class="far fa-bookmark"></i>
    </ReButton>
    <ReButton
      classes="btn btn-success btn-circle button-view"
      @click="showModalImage = true"
    >
      <i class="far fa-eye"></i>
    </ReButton> 
    <re-modal
      :id="id"
      title="Visualização completa da imagem"
      :show="showModalImage"
      @close="showModalImage = false"
      :footer="false"
    >
      <div class="preview-image">
        <img :src="image.way">
      </div>
    </re-modal>
  </div>
</template>

<script>
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReModal from "@/components/Modals/Modal";
export default {
  name: "ItemImageMedia",
  props: {
    image: {
      type: Object,
      require: true,
    },
    id: {
      type: String,
      default: null,
    },    
    haveWatermark: {
      type: Boolean,
      require: true,
    },
  },
  components: {
    ReButton,
    ReModal,
  },
  data: () => ({
    checked: false,
    idCheck: null,
    showModalImage: false,
  }),
  computed: {
    classImage() {
      return `item-image ${this.checked ? "border border-info border-3" : ""}`;
    },
  },
  methods: {
    checkImage(check) {
      this.checked = check;
    },
    removeImage(id) {
      reaxios
        .delete(reroute("jp_realestate.api.image_property.destroy", [id]))
        .then(() => {
          this.$emit("deleted-image", id);
        });
    },
    addImageWatermark() {
      this.$emit(
        'add-image-wattermark', 
        {id: this.image.id, url: this.image.way}
      );
    }
  },
  beforeMount() {
    this.idCheck = this.id ? this.id : `item-image-media-default-${this._uid}`;
  },
};
</script>

<style lang="scss" scoped>
.button-trash {
  position: absolute;
  left: 8px;
  top: -5px;
}
.button-edit {
  position: absolute;
  right: 0;
  top: -5px;
}
.button-view {
  position: absolute;
  right: 0;
  top: 35px;
}
.item-image {
  width: 150px;
  height: 150px;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  &-selected {
    border: 1px solid blue;
  }
}
.preview-image {
	max-height: 100%;
	height: 300px;
	width: 100%;
	display: flex;
	justify-content: center;
	align-items: center;
  img {
    max-width: 100%;
    max-height: 100%;
  }
}
</style>
