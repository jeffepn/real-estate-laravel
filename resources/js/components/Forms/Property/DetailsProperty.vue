<template>
  <div class="mt-2">
    <div class="row">
      <div class="col-sm-6 col-md-3 mb-2">
        <re-input
          type="number"
          v-model="form.data.building_area"
          placeholder="Área construída"
          :error="form.hasError('building_area')"
          :error-message="form.firstError('building_area')"
        ></re-input>
      </div>
      <div class="col-sm-6 col-md-3 mb-2">
        <re-input
          type="number"
          v-model="form.data.useful_area"
          placeholder="Área útil"
          :error="form.hasError('useful_area')"
          :error-message="form.firstError('useful_area')"
        ></re-input>
      </div>
      <div class="col-sm-6 col-md-3 mb-2">
        <re-input
          type="number"
          v-model="form.data.total_area"
          placeholder="Área total"
          :error="form.hasError('total_area')"
          :error-message="form.firstError('total_area')"
        ></re-input>
      </div>
    </div>
    <div class="row">
      <div class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex flex-column">
        <div class="col-12">Dormitórios</div>
        <div class="d-flex mt-2">
          <re-input
            class="col-5 pe-1"
            type="number"
            :precision="0"
            v-model="form.data.min_dormitory"
            placeholder="Min."
            :error="form.hasError('min_dormitory')"
            :error-message="form.firstError('min_dormitory')"
          ></re-input>
          <div class="col-auto px-0 d-flex align-items-center">
            <i class="fas fa-arrows-alt-h"></i>
          </div>
          <re-input
            class="col-5 ps-1"
            type="number"
            :precision="0"
            v-model="form.data.max_dormitory"
            placeholder="Máx."
            :error="form.hasError('max_dormitory')"
            :error-message="form.firstError('max_dormitory')"
          ></re-input>
        </div>
      </div>
      <div class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex flex-column">
        <div class="col-12">Suítes</div>

        <div class="d-flex mt-2">
          <re-input
            class="col-5 pe-1"
            type="number"
            :precision="0"
            v-model="form.data.min_suite"
            placeholder="Min."
            :error="form.hasError('min_suite')"
            :error-message="form.firstError('min_suite')"
          ></re-input>
          <div class="col-auto px-0 d-flex align-items-center">
            <i class="fas fa-arrows-alt-h"></i>
          </div>
          <re-input
            class="col-5 ps-1"
            type="number"
            :precision="0"
            v-model="form.data.max_suite"
            placeholder="Máx."
            :error="form.hasError('max_suite')"
            :error-message="form.firstError('max_suite')"
          ></re-input>
        </div>
      </div>
      <div class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex flex-column">
        <div class="col-12">Banheiros</div>
        <div class="d-flex mt-2">
          <re-input
            class="col-5 pe-1"
            type="number"
            :precision="0"
            v-model="form.data.min_bathroom"
            placeholder="Min."
            :error="form.hasError('min_bathroom')"
            :error-message="form.firstError('min_bathroom')"
          ></re-input>
          <div class="col-auto px-0 d-flex align-items-center">
            <i class="fas fa-arrows-alt-h"></i>
          </div>
          <re-input
            class="col-5 ps-1"
            type="number"
            :precision="0"
            v-model="form.data.max_bathroom"
            placeholder="Máx."
            :error="form.hasError('max_bathroom')"
            :error-message="form.firstError('max_bathroom')"
          ></re-input>
        </div>
      </div>
      <div class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex flex-column">
        <div class="col-12">Garagens</div>
        <div class="d-flex mt-2">
          <re-input
            class="col-5 pe-1"
            type="number"
            :precision="0"
            v-model="form.data.min_garage"
            placeholder="Min."
            :error="form.hasError('min_garage')"
            :error-message="form.firstError('min_garage')"
          ></re-input>
          <div class="col-auto px-0 d-flex align-items-center">
            <i class="fas fa-arrows-alt-h"></i>
          </div>
          <re-input
            class="col-5 ps-1"
            type="number"
            :precision="0"
            v-model="form.data.max_garage"
            placeholder="Máx."
            :error="form.hasError('max_garage')"
            :error-message="form.firstError('max_garage')"
          ></re-input>
        </div>
      </div>
    </div>
    <div class="mb-2 col-12">
      <re-textarea
        :max-length="200"
        v-model="form.data.min_description"
        placeholder="Descrição para SEO. (sinopse do imóvel)"
        :error="form.hasError('min_description')"
        :error-message="form.firstError('min_description')"
      ></re-textarea>
    </div>
    <div class="mb-2 col-12">
      <ckeditor
        :editor="editor"
        v-model="form.data.content"
        :config="editorConfig"
      ></ckeditor>
      <p
        class="mt-2 text-danger"
        v-if="form.hasError('content')"
        v-text="form.firstError('content')"
      ></p>
    </div>
  </div>
</template>

<script>
import CKEditor from "@ckeditor/ckeditor5-vue2";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import ReInput from "@/components/Controls/Inputs/Input";
import ReTextarea from "@/components/Controls/Inputs/Textarea";
export default {
  components: {
    ckeditor: CKEditor.component,
    ReInput,
    ReTextarea,
  },
  props: {
    form: {
      type: Object,
      require: true,
    },
  },
  data() {
    return {
      editor: ClassicEditor,
      editorConfig: {
        allowedContent: true,
        removePlugins: ["CKFinder"],
        toolbar: {
          removeItems: "uploadImage|mediaEmbed",
        },
      },
    };
  },
};
</script>

<style></style>
