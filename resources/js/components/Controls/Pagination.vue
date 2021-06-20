<template>
  <div>
    <p class="g-pagination-label">
      {{ paginationLabel }}
    </p>
    <div class="d-flex flex-wrap">
      <nav class=" me-3">
        <ul class="pagination flex-wrap mb-0 mt-2">
          <li class="page-item" :class="{ disabled: disabledPrevious }">
            <a class="page-link" href="#" @click.prevent="prev">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>
          <li
            class="page-item"
            v-for="n in parseInt(pages)"
            :key="n"
            :class="{ active: page === n }"
          >
            <a
              class="page-link"
              href="#"
              @click.prevent="updatePage(n)"
              v-text="n"
            ></a>
          </li>
          <li class="page-item" :class="{ disabled: disabledNext }">
            <a class="page-link" href="#" @click.prevent="next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
      <div class="block-select-per-page mt-2">
        <select
          class="form-select me-3"
          aria-label="Selecione uma quantidade de imóveis por página"
          v-model="selectPerPage"
        >
          <option
            v-for="option in options"
            :key="option.id"
            :value="option.id"
            v-text="option.value"
          ></option>
        </select>
        <span>
          Itens por página
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    perPage: {
      type: Number,
      require: true,
    },
    total: {
      type: Number,
      require: true,
    },
    page: {
      type: Number,
      require: true,
    },
  },
  data() {
    return {
      selectPerPage: this.perPage,
      options: [
        { id: 10, value: 10 },
        { id: 20, value: 20 },
        { id: 25, value: 25 },
        { id: 50, value: 50 },
      ],
    };
  },

  computed: {
    disabledPrevious() {
      return this.page <= 1;
    },
    disabledNext() {
      return this.page >= this.pages;
    },
    pages() {
      return Math.ceil(this.total / this.selectPerPage);
    },
    minFromPage() {
      const result = (this.page - 1) * this.selectPerPage + 1;
      if (this.total < 1) {
        return 0;
      }
      return result;
    },
    maxFromPage() {
      const result = this.page * this.selectPerPage;
      if (result <= 0) {
        return 0;
      }
      return result <= this.total ? result : this.total;
    },
    paginationLabel() {
      let totalFromPage = this.total >= 0 ? this.total : 0;
      return `Mostrando ${this.minFromPage} - ${this.maxFromPage} de ${totalFromPage}`;
    },
  },
  watch: {
    selectPerPage(newValue) {
      this.$emit("changePage", 1);
      this.$emit("changePerPage", parseInt(newValue));
    },
  },
  methods: {
    updatePage(page) {
      this.$emit("changePage", parseInt(page));
    },
    prev() {
      if (!this.disabledPrevious) {
        this.$emit("changePage", parseInt(this.page - 1));
      }
    },
    next() {
      if (!this.disabledNext) {
        this.$emit("changePage", parseInt(this.page + 1));
      }
    },
  },
  beforeMount() {
    if (!this.options.find((element) => element.id === this.perPage)) {
      this.options.push({ id: this.perPage, value: this.perPage });
      this.options.sort((a, b) => {
        return a.id - b.id;
      });
    }
  },
};
</script>

<style lang="scss" scoped>
.block-select-per-page {
  display: flex;
  align-items: center;
  select {
    max-width: 70px;
  }
  span {
    white-space: nowrap;
  }
}
</style>
