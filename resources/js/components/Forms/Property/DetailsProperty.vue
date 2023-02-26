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
          @pressEnter="$emit('pressEnter')"
        ></re-input>
      </div>
      <div class="col-sm-6 col-md-3 mb-2">
        <re-input
          type="number"
          v-model="form.data.useful_area"
          placeholder="Área útil"
          :error="form.hasError('useful_area')"
          :error-message="form.firstError('useful_area')"
          @pressEnter="$emit('pressEnter')"
        ></re-input>
      </div>
      <div class="col-sm-6 col-md-3 mb-2">
        <re-input
          type="number"
          v-model="form.data.total_area"
          placeholder="Área total"
          :error="form.hasError('total_area')"
          :error-message="form.firstError('total_area')"
          @pressEnter="$emit('pressEnter')"
        ></re-input>
      </div>
      <div class="col-sm-6 col-md-3 mb-2">
        <re-input
          type="number"
          v-model="form.data.ground_area"
          placeholder="Área do terreno"
          :error="form.hasError('ground_area')"
          :error-message="form.firstError('ground_area')"
          @pressEnter="$emit('pressEnter')"
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
            @pressEnter="$emit('pressEnter')"
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
            @pressEnter="$emit('pressEnter')"
          ></re-input>
        </div>
        <p
          class="text-danger"
          v-if="form.hasError('min_dormitory')"
          v-text="form.firstError('min_dormitory')"
        />
        <p
          class="text-danger"
          v-if="form.hasError('max_dormitory')"
          v-text="form.firstError('max_dormitory')"
        />
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
            @pressEnter="$emit('pressEnter')"
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
            @pressEnter="$emit('pressEnter')"
          ></re-input>
        </div>
        <p
          class="text-danger"
          v-if="form.hasError('min_suite')"
          v-text="form.firstError('min_suite')"
        />
        <p
          class="text-danger"
          v-if="form.hasError('max_suite')"
          v-text="form.firstError('max_suite')"
        />
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
            @pressEnter="$emit('pressEnter')"
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
            @pressEnter="$emit('pressEnter')"
          ></re-input>
        </div>
        <p
          class="text-danger"
          v-if="form.hasError('min_bathroom')"
          v-text="form.firstError('min_bathroom')"
        />
        <p
          class="text-danger"
          v-if="form.hasError('max_bathroom')"
          v-text="form.firstError('max_bathroom')"
        />
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
            @pressEnter="$emit('pressEnter')"
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
            @pressEnter="$emit('pressEnter')"
          ></re-input>
        </div>
        <p
          class="text-danger"
          v-if="form.hasError('min_garage')"
          v-text="form.firstError('min_garage')"
        />
        <p
          class="text-danger"
          v-if="form.hasError('max_garage')"
          v-text="form.firstError('max_garage')"
        />
      </div>
      <div class="mb-2 col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex flex-column">
        <div class="col-12">Lavabos</div>
        <div class="d-flex mt-2">
          <re-input
            class="col-5 pe-1"
            type="number"
            :precision="0"
            v-model="form.data.min_restroom"
            placeholder="Min."
            @pressEnter="$emit('pressEnter')"
          ></re-input>
          <div class="col-auto px-0 d-flex align-items-center">
            <i class="fas fa-arrows-alt-h"></i>
          </div>
          <re-input
            class="col-5 ps-1"
            type="number"
            :precision="0"
            v-model="form.data.max_restroom"
            placeholder="Máx."
            @pressEnter="$emit('pressEnter')"
          ></re-input>
        </div>
        <p
          class="text-danger"
          v-if="form.hasError('min_restroom')"
          v-text="form.firstError('min_restroom')"
        />
        <p
          class="text-danger"
          v-if="form.hasError('max_restroom')"
          v-text="form.firstError('max_restroom')"
        />
      </div>
    </div>
    <div class="border-bottom mt-4 mb-3">
      <h4 class="mt-0">Mais detalhes</h4>
    </div>
    <div class="form-check form-switch">
      <ReSwitch
        v-model="form.data.has_plate"
        label="O imóvel possui placa no lugar"
      />
        <p
          class="text-danger"
          v-if="form.hasError('has_plate')"
          v-text="form.firstError('has_plate')"
        />
    </div>
    <div class="border-bottom mt-4 mb-3">
      <h4 class="mt-0">Conteúdo</h4>
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
import ReSwitch from "@/components/Controls/Inputs/Switch";
import ReTextarea from "@/components/Controls/Inputs/Textarea";
export default {
  components: {
    ckeditor: CKEditor.component,
    ReInput,
    ReTextarea,
    ReSwitch,
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
