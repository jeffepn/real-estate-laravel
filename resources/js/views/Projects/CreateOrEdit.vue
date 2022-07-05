<template>
  <div class="container">
    <div v-show="!loadingMaster" class="card my-5">
      <div
        class="
          card-header
          d-flex
          flex-wrap
          justify-content-between
          align-items-center
        "
      >
        <h2 v-text="title"></h2>
        <div class="d-flex flex-wrap">
          <a :href="urlBack" class="btn btn-outline-secondary ms-2">
            <span aria-hidden="true">&laquo;</span> Voltar
          </a>
        </div>
      </div>
      <div class="card-body px-0">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="border-start-0 nav-link active"
              id="data-tab"
              data-bs-target="#data"
              type="button"
              role="tab"
              aria-controls="data"
              aria-selected="true"
              @click="next('data')"
            >
              Dados
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="media-tab"
              data-bs-target="#media"
              type="button"
              role="tab"
              aria-controls="media"
              aria-selected="false"
              @click="next('media')"
            >
              Media
            </button>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active px-2" id="data" role="tabpanel">
            <div class="row mt-3">
              <div class="col-sm-6 col-md-auto mb-2">
                <re-choose-type-person
                  v-model="type_person_id"
                  :create="true"
                />
              </div>
              <div class="col-sm-6 col-md-auto mb-2">
                <re-choose-person
                  :type-person-id="type_person_id"
                  v-model="form.data.person_id"
                  :create="true"
                  :error="form.hasError('person_id')"
                  :error-message="form.firstError('person_id')"
                ></re-choose-person>
              </div>
            </div>
            <div class="col-sm-6 col-md-auto mb-2">
              <re-input
                placeholder="Nome*"
                v-model="form.data.name"
                :error="form.hasError('name')"
                :error-message="form.firstError('name')"
                @pressEnter="$emit('pressEnter')"
              ></re-input>
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
            <div class="mb-2 col-12 text-end">
              <re-button :loading="loadingNext" @click="next('media')">
                Próximo <span aria-hidden="true">&raquo;</span>
              </re-button>
            </div>
          </div>
          <div class="tab-pane fade px-2" id="media" role="tabpanel">
            <re-media-project
              v-if="!loadingMaster"
              :form="form"
            ></re-media-project>
            <div class="mb-2 col-12 d-flex flex-wrap justify-content-between">
              <div class="mb-2 col-auto text-start">
                <button class="btn btn-outline-secondary" @click="back('data')">
                  <span aria-hidden="true">&laquo;</span> Voltar
                </button>
              </div>
              <div class="col-auto text-end">
                <re-button
                  class="mb-2"
                  :loading="loadingNext"
                  @click="save('media')"
                >
                  Salvar <i class="fas fa-save"></i>
                </re-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "@/supports/form.js";
import CKEditor from "@ckeditor/ckeditor5-vue2";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import ReChoosePerson from "@/components/Entities/Choose/Person";
import ReChooseTypePerson from "@/components/Entities/Choose/TypePerson";
import ReMediaProject from "@/components/Forms/Project/MediaProject.vue";
import ReInput from "@/components/Controls/Inputs/Input";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReLoading from "@/components/Loading";
export default {
  components: {
    ckeditor: CKEditor.component,
    ReChoosePerson,
    ReChooseTypePerson,
    ReMediaProject,
    ReInput,
    ReButton,
    ReLoading,
  },
  props: {
    id: {
      type: String,
      default: null,
    },
    tab: {
      type: String,
      default: null,
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
      type_person_id: null,

      loadingNext: false,
      loadingBack: false,
      loadingPublish: false,
      loadingMaster: true,
      idProject: this.id,
      project: null,
      form: this.initialiseInitialForm(),
      tabDetails: null,
      tabData: null,
      tabBusinesses: null,
      tabMedia: null,
    };
  },
  watch: {
    project() {
      this.initialiseProject();
    },
    type_person_id() {
      this.form.data.person_id = null;
    },
  },
  computed: {
    edit() {
      return this.idProject !== null;
    },
    projectIsActive() {
      return this.project && this.project.active;
    },
    title() {
      return this.edit ? "Editar Projeto" : "Novo Projeto";
    },
    urlBack() {
      return reroute("jp_realestate.project.index");
    },
  },
  methods: {
    initialiseInitialForm() {
      return new Form({
        person_id: null,
        name: null,
        content: null,
      });
    },
    setTabShow(tab) {
      switch (tab) {
        case "data":
          this.tabData.show();
          break;
        case "media":
          this.tabMedia.show();
          break;
      }
      let url = new URL(location.href);
      url.searchParams.set("tab", tab);
      this.setUrlHistory(url.href);
    },
    setTabWithErrors() {
      let keyError = this.form.firstKeyErrorAny();
      switch (keyError) {
        case "person_id":
        case "name":
        case "content":
          this.setTabShow("data");
          break;
        default:
          this.setTabShow("media");
          break;
      }
    },
    setUrlHistory(url) {
      history.pushState({}, null, url);
    },
    back(tab) {
      this.loadingBack = true;
      this.setTabShow(tab);
      this.loadingBack = false;
    },
    next(tab) {
      this.loadingNext = true;
      this.form.clearErrors();
      this.submit(this.form.data, tab);
    },
    save() {
      this.loadingNext = true;
      this.form.clearErrors();
      this.submit(this.form.data);
    },
    submit(data, tab = null) {
      delete data["active"];
      data.with = "responsible,responsible.type";
      let request = this.edit
        ? reaxios.patch(
            reroute("jp_realestate.api.project.update", [this.idProject]),
            data,
          )
        : reaxios.post(reroute("jp_realestate.api.project.store"), data);
      request
        .then((response) => {
          if (!this.edit) {
            this.setProject(response.data);
          }
          if (!tab) {
            location.href = reroute("jp_realestate.project.index");
          }
          this.setTabShow(tab);
        })
        .catch(({ response }) => {
          if (response && response.status === 422) {
            this.form.errors = response.data.errors;
            return this.setTabWithErrors();
          }
          if (response) {
            this.$toast.message({
              type: "danger",
              message: response.data.message,
            });
          }
        })
        .finally(() => (this.loadingNext = false));
    },
    initialiseProject() {
      this.idProject = this.project.id;
      this.setUrlHistory(
        reroute("jp_realestate.project.edit", [this.idProject]),
      );
      this.setDataBaseProject();
    },
    setProject({ data, included }) {
      const person = included.find(
        (el) =>
          el.type === "person" &&
          el.id === data.relationships.responsible.data.id,
      );
      this.project = {
        id: data.id,
        slug: data.attributes.slug,
        name: data.attributes.name,
        person_id: person.id,
        content: data.attributes.content,
        responsible: person,
      };
      this.type_person_id = person.relationships.type.data.id;
    },
    async getProject() {
      await reaxios
        .get(reroute("jp_realestate.api.project.show", [this.id]), {
          params: {
            with: "responsible,responsible.type",
          },
        })
        .then((response) => this.setProject(response.data));
    },
    initialiseTabs() {
      let someTabTriggerEl = document.querySelector("#data-tab");
      this.tabData = new bootstrap.Tab(someTabTriggerEl);
      someTabTriggerEl = document.querySelector("#media-tab");
      this.tabMedia = new bootstrap.Tab(someTabTriggerEl);
      if (this.tab) {
        this.setTabShow(this.tab);
      }
    },
    setDataBaseProject() {
      this.form.data = this.project;
    },
  },
  async mounted() {
    if (this.id) {
      await this.getProject();
    }
    this.initialiseTabs();
    this.loadingMaster = false;
  },
};
</script>

<style>
select {
  min-width: 200px;
}
</style>
