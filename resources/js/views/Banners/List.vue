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
        <h2>Banners</h2>
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
          <a href="#" class="btn btn-outline-primary ms-2">
            <i class="fas fa-plus"></i> Novo
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="content-table">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Ações</th>
                <th scope="col">Imagem</th>
                <th scope="col">Título</th>
                <th scope="col">Conteúdo</th>
                <th scope="col">Link</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="banner in banners" :key="banner.id">
                <th>
                  <div class="actions">
                    <a
                      class="btn btn-primary btn-sm"
                      :href="$route('jp_realestate.banner.edit', [banner.id])"
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
                      type="button"
                      @click="openDelete(banner.id)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </th>
                <td>
                  <img height="60" :src="banner.wayUrl" alt="" />
                </td>
                <td v-text="banner.title"></td>
                <td v-html="banner.content"></td>
                <td v-text="banner.link"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer">
        <re-pagination
          :per-page="pagination.perPage"
          :total="total"
          :page="pagination.page"
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
      @close="idDelete = null"
      @cancel="idDelete = null"
      @ok="deleteBanner"
    >
      <p>Tem certeza da exclusão do banner?</p>
    </re-modal>
  </div>
</template>

<script>
import RePagination from "@/components/Controls/Pagination";
import ReButton from "@/components/Controls/Buttons/ButtonDefault";
import ReModal from "@components/Modal";
export default {
  components: { RePagination, ReButton, ReModal },
  data() {
    return {
      search: null,
      data: [],
      included: [],
      pagination: {
        perPage: 10,
        page: 1,
      },
      showModalDelete: false,
      idDelete: null,
    };
  },
  computed: {
    banners() {
      const initial = (this.pagination.page - 1) * this.pagination.perPage;
      const final = initial + this.pagination.perPage;
      return this.filteredBanners.slice(initial, final);
    },
    filteredBanners() {
      if (this.searchIsEmpty) {
        return this.originalBanners;
      }

      const result = this.originalBanners.filter(
        (item) =>
          item.title.search(
            new RegExp(this.search.replaceAll(" ", ".*"), "i"),
          ) !== -1 ||
          item.content.search(
            new RegExp(this.search.replaceAll(" ", ".*"), "i"),
          ) !== -1,
      );
      this.resetPage();
      return result;
    },
    originalBanners() {
      return this.data.reduce((acumulator, currentValue) => {
        let { id, attributes } = currentValue;

        let banner = Object.assign({}, { id }, attributes);
        acumulator.push(banner);
        return acumulator;
      }, []);
    },
    total() {
      return this.filteredBanners.length;
    },
    searchIsEmpty() {
      return !(this.search && this.search.trim().length);
    },
  },
  methods: {
    getBanners() {
      this.$axios
        .get(this.$route("jp_realestate.banner.index"))
        .then((response) => {
          this.data = response.data.data;
          this.included = response.data.included;
        })
        .catch((error) => {
          this.$toast.message(error.message, true);
        });
    },
    updatePage(page) {
      this.pagination.page = page;
    },
    updatePerPage(perPage) {
      this.pagination.perPage = perPage;
    },
    resetPage() {
      this.updatePage(1);
    },
    openDelete(id) {
      this.idDelete = id;
      this.showModalDelete = true;
    },
    deleteBanner() {
      this.$axios
        .delete(this.$route("jp_realestate.banner.destroy", [this.idDelete]))
        .then((response) => {
          this.data = this.data.filter(
            (element) => element.id !== this.idDelete,
          );
          this.idDelete = null;
          this.showModalDelete = false;
        })
        .catch((error) => {
          this.$toast.message(error.message, true);
        });
    },
  },
  async beforeMount() {
    await this.getBanners();
  },
  mounted() {
    window.tooltip();
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
