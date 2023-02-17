<template>
  <div>
    <div
      :class="classImage"
      :style="`background-image: url(${image.way})`"
      @click="checked = !checked"
    ></div>
    <ReButton
      classes="btn btn-danger btn-circle button-trash"
      @click="removeImage(image.id)"
    >
      <i class="fas fa-trash"></i>
    </ReButton>
  </div>
</template>

<script>
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
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
  },
  components: {
    ReButton,
  },
  data: () => ({
    checked: false,
    idCheck: null,
  }),
  computed: {
    classImage() {
      return `item-image ${this.checked ? "border border-info" : ""}`;
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
  },
  beforeMount() {
    this.idCheck = this.id ? this.id : `item-image-media-default-${this._uid}`;
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
  cursor: pointer;
  &-selected {
    border: 1px solid blue;
  }
}
</style>
