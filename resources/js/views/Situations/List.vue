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
          gap-3
        "
      >
        <h2>Situações</h2>
        <div class="d-flex flex-wrap gap-2">
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
          </div>
          <div class="d-flex">
            <button
              @click.prevent="showModalAddSituation = true"
              class="btn-new btn btn-outline-primary"
            >
              <i class="fas fa-plus"></i> Novo
            </button>
          </div>
        </div>
        <div class="col-12">
            <small>
              <i class="fas fa-exclamation-triangle text-danger"></i>
              <i>
                Não é possível excluir uma situação que tenha <b>imóveis</b> vinculados a ela.
              </i>
            </small>
        </div>
      </div>
      <div class="card-body">
        <div class="content-table">
          <div
            class="d-flex justify-content-center text-center p-4"
            v-if="situationsIsEmpty || loadingSituations"
          >
            <div v-if="situationsIsEmpty && !loadingSituations">
              <ph-table :size="80" weight="thin" />
              <p>
                Não encontramos situações, com os termos de busca.
              </p>
            </div>
            <div v-if="loadingSituations">
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
            v-if="situationsIsNotEmpty && !loadingSituations"
          >
            <thead>
              <tr>
                <th scope="col">Nome</th>
                <th scope="col">Qtd. Imóveis</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="situation in situations" :key="situation.id">
                <th>
                  <div class="actions">
                    <button
                      @click="openModalEditSituation(situation.id)"
                      class="btn btn-primary btn-sm"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Editar"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button                    
                      v-if="situation.number_linked_properties < 1"
                      class="btn btn-danger btn-sm"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Excluir"
                      @click="openDelete(situation.id)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                    <span class="ms-2">
                      {{ situation.name }}
                    </span>
                  </div>
                </th>
                <th>
                    {{ situation.number_linked_properties }}
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
      @ok="deleteSituation"
    >
      <p>Tem certeza da exclusão da situação?</p>
    </re-modal>
    <re-modal
      title="Adicionar nova Situação"
      :show="showModalAddSituation"
      @close="closeModalAddSituation"
      :footer="false"
    >
      <re-add-situation @submitSuccess="submitSuccessAddSituation"> </re-add-situation>
    </re-modal>
    <re-modal
      title="Editar Situação"
      :show="showModalEditSituation"
      @close="closeModalEditSituation"
      :footer="false"
    >
      <re-edit-situation
        v-if="!!currentEditSituationId"
        :id="currentEditSituationId"
        @submitSuccess="submitSuccessEditSituation"
      >
      </re-edit-situation>
    </re-modal>
  </div>
</template>

<script>
import RePagination from "@/components/Controls/Pagination";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReModal from "@/components/Modals/Modal";
import ReAddSituation from "@/components/Forms/Situation/AddSituation";
import ReEditSituation from "@/components/Forms/Situation/EditSituation";
import { PhTable, PhCircleNotch } from "phosphor-vue";
export default {
  components: {
    RePagination,
    ReButton,
    ReModal,
    PhTable,
    PhCircleNotch,
    ReAddSituation,
    ReEditSituation,
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
      showModalAddSituation: false,
      showModalEditSituation: false,
      idDelete: null,
      debounceSearch: null,
      timeoutDebounce: 500,
      loadingSituations: true,
      currentEditSituationId: null,
    };
  },
  watch: {
    search() {
      clearTimeout(this.debounceSearch);
      this.meta.current_page = 1;
      this.debounceSearch = setTimeout(() => {
        this.getSituations();
      }, this.timeoutDebounce);
    },
  },
  computed: {
    situations() {
      return this.data.reduce((acumulator, currentValue) => {
        let { id, attributes, relationships } = currentValue;

        let situation = Object.assign({}, { id, relationships }, attributes);
        acumulator.push(situation);
        return acumulator;
      }, []);
    },
    situationsIsEmpty() {
      return !this.situationsIsNotEmpty;
    },
    situationsIsNotEmpty() {
      return this.situations.length;
    },
    searchIsNotEmpty() {
      return this.search && this.search.trim().length;
    },
  },
  methods: {
    getSituations() {
      this.loadingSituations = true;
      const params = {
        paginate: this.meta.per_page,
        page: this.meta.current_page,
      };
      if (this.searchIsNotEmpty) params.search = this.search;

      reaxios
        .get(reroute("jp_realestate.api.situation.index"), {
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
        .finally(() => (this.loadingSituations = false));
    },
    updatePage(page) {
      this.meta.current_page = page;
      this.getSituations();
    },
    updatePerPage(perPage) {
      this.meta.current_page = 1;
      this.meta.per_page = perPage;
      this.getSituations();
    },
    openDelete(id) {
      this.idDelete = id;
      this.showModalDelete = true;
    },
    closeDelete() {
      this.idDelete = null;
      this.showModalDelete = false;
    },
    deleteSituation() {
      reaxios
        .delete(reroute("jp_realestate.api.situation.destroy", [this.idDelete]))
        .then(() => {
          this.getSituations();
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
    closeModalAddSituation() {
      this.showModalAddSituation = false;
    },
    submitSuccessAddSituation() {
      this.getSituations();
    },
    openModalEditSituation(id) {
      this.currentEditSituationId = id;
      this.showModalEditSituation = true;
    },
    closeModalEditSituation() {
      this.currentEditSituationId = null;
      this.showModalEditSituation = false;
    },
    submitSuccessEditSituation() {
       this.getSituations();
    },
  },
  async beforeMount() {
    await this.getSituations();
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
