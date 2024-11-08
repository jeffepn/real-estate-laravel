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
        <h2>Tipos</h2>
        <div class="d-flex">
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
          <button
            @click.prevent="showModalAddType = true"
            class="btn-new btn btn-outline-primary ms-2"
          >
            <i class="fas fa-plus"></i> Novo
          </button>
        </div>
        <div class="col-12">
            <small>
              <i class="fas fa-exclamation-triangle text-danger"></i>
              <i>
                Não é possível excluir um tipo que tenha <b>sub tipos</b> vinculados a ele.
              </i>
            </small>
        </div>
      </div>
      <div class="card-body">
        <div class="content-table">
          <div
            class="d-flex justify-content-center text-center p-4"
            v-if="typesIsEmpty || loadingTypes"
          >
            <div v-if="typesIsEmpty && !loadingTypes">
              <ph-table :size="80" weight="thin" />
              <p>
                Não encontramos tipos, com os termos de busca.
              </p>
            </div>
            <div v-if="loadingTypes">
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
            v-if="typesIsNotEmpty && !loadingTypes"
          >
            <thead>
              <tr>
                <th scope="col">Nome</th>
                <th scope="col">Total de sub-tipos</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="type in types" :key="type.id">
                <th>
                  <div class="actions">
                    <button
                      @click="openModalEditType(type.id)"
                      class="btn btn-primary btn-sm"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Editar"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button                    
                      v-if="type.relationships.sub_types.data.length < 1"
                      class="btn btn-danger btn-sm"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Excluir"
                      @click="openDelete(type.id)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                    <span class="ms-2">
                      {{ type.name }}
                    </span>
                  </div>
                </th>
                <th>
                    {{ type.relationships.sub_types.data.length }}
                </th>
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
      @ok="deleteType"
    >
      <p>Tem certeza da exclusão do tipo?</p>
    </re-modal>
    <re-modal
      title="Adicionar novo Tipo"
      :show="showModalAddType"
      @close="closeModalAddType"
      :footer="false"
    >
      <re-add-type @submitSuccess="submitSuccessAddType"> </re-add-type>
    </re-modal>
    <re-modal
      title="Editar Tipo"
      :show="showModalEditType"
      @close="closeModalEditType"
      :footer="false"
    >
      <re-edit-type
        v-if="!!currentEditTypeId"
        :id="currentEditTypeId"
        @submitSuccess="submitSuccessEditType"
      >
      </re-edit-type>
    </re-modal>
  </div>
</template>

<script>
import RePagination from "@/components/Controls/Pagination";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReModal from "@/components/Modal";
import ReAddType from "@/components/Forms/Type/AddType";
import ReEditType from "@/components/Forms/Type/EditType";
import { PhTable, PhCircleNotch } from "phosphor-vue";
export default {
  components: {
    RePagination,
    ReButton,
    ReModal,
    PhTable,
    PhCircleNotch,
    ReAddType,
    ReEditType,
  },
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
      showModalAddType: false,
      showModalEditType: false,
      idDelete: null,
      debounceSearch: null,
      timeoutDebounce: 500,
      loadingTypes: true,
      currentEditTypeId: null,
    };
  },
  watch: {
    search() {
      clearTimeout(this.debounceSearch);
      this.meta.current_page = 1;
      this.debounceSearch = setTimeout(() => {
        this.getTypes();
      }, this.timeoutDebounce);
    },
  },
  computed: {
    types() {
      return this.data.reduce((acumulator, currentValue) => {
        let { id, attributes, relationships } = currentValue;

        let type = Object.assign({}, { id, relationships }, attributes);
        acumulator.push(type);
        return acumulator;
      }, []);
    },
    typesIsEmpty() {
      return !this.typesIsNotEmpty;
    },
    typesIsNotEmpty() {
      return this.types.length;
    },
    searchIsNotEmpty() {
      return this.search && this.search.trim().length;
    },
  },
  methods: {
    getTypes() {
      this.loadingTypes = true;
      const params = {
        paginate: this.meta.per_page,
        page: this.meta.current_page,
      };
      if (this.searchIsNotEmpty) params.search = this.search;

      reaxios
        .get(reroute("jp_realestate.api.type.index"), {
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
        .finally(() => (this.loadingTypes = false));
    },
    updatePage(page) {
      this.meta.current_page = page;
      this.getTypes();
    },
    updatePerPage(perPage) {
      this.meta.current_page = 1;
      this.meta.per_page = perPage;
      this.getTypes();
    },
    openDelete(id) {
      this.idDelete = id;
      this.showModalDelete = true;
    },
    closeDelete() {
      this.idDelete = null;
      this.showModalDelete = false;
    },
    deleteType() {
      reaxios
        .delete(reroute("jp_realestate.api.type.destroy", [this.idDelete]))
        .then(() => {
          this.getTypes();
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
    closeModalAddType() {
      this.showModalAddType = false;
    },
    submitSuccessAddType() {
      this.getTypes();
    },
    openModalEditType(id) {
      this.currentEditTypeId = id;
      this.showModalEditType = true;
    },
    closeModalEditType() {
      this.currentEditTypeId = null;
      this.showModalEditType = false;
    },
    submitSuccessEditType() {
       this.getTypes();
    },
  },
  async beforeMount() {
    await this.getTypes();
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
  gap: 10px;
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
.btn-new {
  white-space: nowrap;
}
</style>
