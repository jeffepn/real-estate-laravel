<template>
  <div class="container">
    <div class="card my-5">
      <div
        class="
          card-header
          d-flex
          flex-wrap
          justify-content-between
          align-items-center
        "
      >
        <h2>Projetos</h2>
        <div class="d-flex flex-wrap">
          <div class="input-search input-group">
            <input
              type="text"
              class="form-control"
              placeholder="Procurar"
              aria-label="Procurar"
              aria-describedby="basic-addon2"
              v-model="search"
            />
            <span class="input-group-text" id="basic-addon2">
              <i class="fas fa-search"></i>
            </span>
          </div>
          <a :href="urlCreate" class="btn btn-outline-primary ms-2">
            <i class="fas fa-plus"></i> Novo
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="content-table">
          <div
            class="d-flex justify-content-center text-center p-4"
            v-if="projectsIsEmpty || loadingProjects"
          >
            <div v-if="projectsIsEmpty && !loadingProjects">
              <ph-table :size="80" weight="thin" />
              <p>
                Não encontramos projetos, com os termos de busca.
              </p>
            </div>
            <div v-if="loadingProjects">
              <ph-circle-notch :size="80" weight="thin">
                <animateTransform
                  attributeName="transform"
                  attributeType="XML"
                  type="rotate"
                  dur="0.5s"
                  from="0 0 0"
                  to="360 0 0"
                  repeatCount="indefinite"
                />
              </ph-circle-notch>
              <p>
                Carregando...
              </p>
            </div>
          </div>
          <table
            class="table table-striped"
            v-if="projectsIsNotEmpty && !loadingProjects"
          >
            <thead>
              <tr>
                <th scope="col">Nome</th>
                <th scope="col">Responsável</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="project in projects" :key="project.id">
                <th>
                  <div class="actions">
                    <a
                      class="btn btn-primary btn-sm"
                      :href="generateUrlEdit(project.id)"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Editar"
                    >
                      <i class="fas fa-edit"></i>
                    </a>
                    <button
                      class="btn btn-danger btn-sm"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Excluir"
                      @click="openDelete(project.id)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                    <span class="ms-2">
                      {{ project.name }}
                    </span>
                  </div>
                </th>
                <td v-text="nameResponsible(project)"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer">
        <re-pagination
          :per-page="parseInt(meta.per_page)"
          :total="meta.total"
          :page="meta.current_page"
          @changePage="updatePage"
          @changePerPage="updatePerPage"
        ></re-pagination>
      </div>
    </div>
    <re-modal
      :show="showModalDelete"
      title="Atenção!"
      text-button-cancel="Cancelar"
      text-button-ok="Sim"
      @close="closeDelete"
      @ok="deleteProject"
    >
      <p>Tem certeza da exclusão do projeto?</p>
    </re-modal>
  </div>
</template>

<script>
import RePagination from "@/components/Controls/Pagination";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReModal from "@/components/Modals/Modal";
import { PhTable, PhCircleNotch } from "phosphor-vue";
export default {
  components: { RePagination, ReButton, ReModal, PhTable, PhCircleNotch },
  data() {
    return {
      search: null,
      data: [],
      included: [],
      meta: {
        per_page: 10,
        current_page: 1,
        total: 0,
      },
      pagination: {
        perPage: 10,
        page: 1,
      },
      showModalDelete: false,
      idDelete: null,
      debounceSearch: null,
      timeoutDebounce: 500,
      loadingProjects: true,
    };
  },
  watch: {
    search() {
      clearTimeout(this.debounceSearch);
      this.meta.current_page = 1;
      this.debounceSearch = setTimeout(() => {
        this.getProjects();
      }, this.timeoutDebounce);
    },
  },
  computed: {
    projects() {
      return this.data.reduce((acumulator, currentValue) => {
        let { id, attributes, relationships } = currentValue;

        let project = Object.assign({}, { id }, attributes);
        project.responsible = this.included.find(
          (el) =>
            el.type === "person" && el.id === relationships.responsible.data.id,
        );

        project.responsible.typePerson = this.included.find(
          (el) =>
            el.type === "type_person" &&
            el.id === project.responsible.relationships.type.data.id,
        );

        acumulator.push(project);
        return acumulator;
      }, []);
    },
    projectsIsEmpty() {
      return !this.projectsIsNotEmpty;
    },
    projectsIsNotEmpty() {
      return this.projects.length;
    },
    searchIsNotEmpty() {
      return this.search && this.search.trim().length;
    },
    urlCreate() {
      return reroute("jp_realestate.project.create");
    },
  },
  methods: {
    getProjects() {
      this.loadingProjects = true;
      const params = {
        paginate: this.meta.per_page,
        page: this.meta.current_page,
        with: "responsible,responsible.type",
      };
      if (this.searchIsNotEmpty) params.search = this.search;

      reaxios
        .get(reroute("jp_realestate.api.project.index"), {
          params,
        })
        .then((response) => {
          this.data = response.data.data;
          this.included = response.data.included;
          this.meta = response.data.meta;
        })
        .catch(({ response }) => {
          if (!response) return;

          this.$toast.message({
            type: "danger",
            message: response.data.message,
          });
        })
        .finally(() => (this.loadingProjects = false));
    },
    updatePage(page) {
      this.meta.current_page = page;
      this.getProjects();
    },
    updatePerPage(perPage) {
      this.meta.current_page = 1;
      this.meta.per_page = perPage;
      this.getProjects();
    },
    openDelete(id) {
      this.idDelete = id;
      this.showModalDelete = true;
    },
    closeDelete() {
      this.idDelete = null;
      this.showModalDelete = false;
    },
    deleteProject() {
      reaxios
        .delete(reroute("jp_realestate.api.project.destroy", [this.idDelete]))
        .then(() => {
          this.getProjects();
          this.idDelete = null;
        })
        .catch(({ response }) => {
          if (!response) return;

          this.$toast.message({
            type: "danger",
            message: response.data.message,
          });
        })
        .finally(() => (this.showModalDelete = false));
    },
    generateUrlEdit(projectId) {
      return reroute("jp_realestate.project.edit", [projectId]);
    },
    nameResponsible(project) {
      const nameProject = project.responsible.attributes.name;
      const nameTypeResponsible = ` - ${project.responsible.typePerson.attributes.name}`;

      return `${nameProject} ${nameTypeResponsible}`;
    },
  },
  async beforeMount() {
    await this.getProjects();
  },
};
</script>

<style lang="scss" scoped>
.content-table {
  max-width: 100%;
  overflow-x: auto;
}
.actions {
  display: flex;
  flex-wrap: nowrap;
  a {
    margin: 0 0.25rem;
  }
  span {
    white-space: nowrap;
  }
}
.input-search {
  max-width: 250px;
}
</style>
