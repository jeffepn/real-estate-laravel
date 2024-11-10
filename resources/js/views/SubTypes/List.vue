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
        <h2>Sub Tipos</h2>
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
            @click.prevent="showModalAddSubType = true"
            class="btn-new btn btn-outline-primary ms-2"
          >
            <i class="fas fa-plus"></i> Novo
          </button>
        </div>
        <div class="col-12">
            <small>
              <i class="fas fa-exclamation-triangle text-danger"></i>
              <i>
                Não é possível excluir um sub tipo que tenha <b>imóveis</b> vinculados a ele.
              </i>
            </small>
        </div>
      </div>
      <div class="card-body">
        <div class="content-table">
          <div
            class="d-flex justify-content-center text-center p-4"
            v-if="subTypesIsEmpty || loadingSubTypes"
          >
            <div v-if="subTypesIsEmpty && !loadingSubTypes">
              <ph-table :size="80" weight="thin" />
              <p>
                Não encontramos tipos, com os termos de busca.
              </p>
            </div>
            <div v-if="loadingSubTypes">
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
            v-if="subTypesIsNotEmpty && !loadingSubTypes"
          >
            <thead>
              <tr>
                <th scope="col">Nome</th>
                <th scope="col">Tipo</th>
                <th scope="col">Qtd. Imóveis</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="subType in subTypes" :key="subType.id">
                <th>
                  <div class="actions">
                    <button
                      @click="openModalEditSubType(subType.id)"
                      class="btn btn-primary btn-sm"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Editar"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      v-if="subType.number_linked_properties < 1"
                      class="btn btn-danger btn-sm"
                      data-bs-toggle="tooltip"
                      data-bs-placement="bottom"
                      title="Excluir"
                      @click="openDelete(subType.id)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                    <span class="ms-2">
                      {{ subType.name }}
                    </span>
                  </div>
                </th>
                <th>
                  {{ getNameType(subType) }}
                </th>
                <th>
                  {{ subType.number_linked_properties }}
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
      @ok="deleteSubType"
    >
      <p>Tem certeza da exclusão do tipo?</p>
    </re-modal>
    <re-modal
      title="Adicionar novo Tipo"
      v-if="showModalAddSubType"
      :show="showModalAddSubType"
      @close="closeModalAddSubType"
      :footer="false"
    >
      <re-add-sub-type @submitSuccess="submitSuccessAddSubType"> </re-add-sub-type>
    </re-modal>
    <re-modal
      title="Editar Tipo"
       v-if="showModalEditSubType"
      :show="showModalEditSubType"
      @close="closeModalEditSubType"
      :footer="false"
    >
      <re-edit-sub-type
        v-if="!!currentEditSubTypeId"
        :id="currentEditSubTypeId"
        @submitSuccess="submitSuccessEditSubType"
      >
      </re-edit-sub-type>
    </re-modal>
  </div>
</template>

<script>
import RePagination from "@/components/Controls/Pagination";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReModal from "@/components/Modal";
import ReAddSubType from "@/components/Forms/SubType/AddSubType";
import ReEditSubType from "@/components/Forms/SubType/EditSubType";
import { PhTable, PhCircleNotch } from "phosphor-vue";
export default {
  components: {
    RePagination,
    ReButton,
    ReModal,
    PhTable,
    PhCircleNotch,
    ReAddSubType,
    ReEditSubType,
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
      showModalAddSubType: false,
      showModalEditSubType: false,
      idDelete: null,
      debounceSearch: null,
      timeoutDebounce: 500,
      loadingSubTypes: true,
      currentEditSubTypeId: null,
    };
  },
  watch: {
    search() {
      clearTimeout(this.debounceSearch);
      this.meta.current_page = 1;
      this.debounceSearch = setTimeout(() => {
        this.getSubTypes();
      }, this.timeoutDebounce);
    },
  },
  computed: {
    subTypes() {
      return this.data.reduce((acumulator, currentValue) => {
        let { id, attributes, relationships } = currentValue;

        let subType = Object.assign({}, { id, relationships }, attributes);
        acumulator.push(subType);
        return acumulator;
      }, []);
    },
    subTypesIsEmpty() {
      return !this.subTypesIsNotEmpty;
    },
    subTypesIsNotEmpty() {
      return this.subTypes.length;
    },
    searchIsNotEmpty() {
      return this.search && this.search.trim().length;
    },
  },
  methods: {
    getSubTypes() {
      this.loadingSubTypes = true;
      const params = {
        paginate: this.meta.per_page,
        page: this.meta.current_page,
      };
      if (this.searchIsNotEmpty) params.search = this.search;

      reaxios
        .get(reroute("jp_realestate.api.sub_type.index"), {
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
        .finally(() => (this.loadingSubTypes = false));
    },
    getNameType(currentSubType) {
      const typeId = currentSubType?.relationships?.type?.data?.id;
      const type =  this.included?.find((el) => el.id === typeId);
      return type?.attributes?.name ?? '';
    },
    updatePage(page) {
      this.meta.current_page = page;
      this.getSubTypes();
    },
    updatePerPage(perPage) {
      this.meta.current_page = 1;
      this.meta.per_page = perPage;
      this.getSubTypes();
    },
    openDelete(id) {
      this.idDelete = id;
      this.showModalDelete = true;
    },
    closeDelete() {
      this.idDelete = null;
      this.showModalDelete = false;
    },
    deleteSubType() {
      reaxios
        .delete(reroute("jp_realestate.api.sub_type.destroy", [this.idDelete]))
        .then(() => {
          this.getSubTypes();
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
    closeModalAddSubType() {
      this.showModalAddSubType = false;
    },
    submitSuccessAddSubType() {
      this.getSubTypes();
    },
    openModalEditSubType(id) {
      this.currentEditSubTypeId = id;
      this.showModalEditSubType = true;
    },
    closeModalEditSubType() {
      this.currentEditSubTypeId = null;
      this.showModalEditSubType = false;
    },
    submitSuccessEditSubType() {
       this.getSubTypes();
    },
  },
  async beforeMount() {
    await this.getSubTypes();
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
