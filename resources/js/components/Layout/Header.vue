<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" :href="urlHome">Painel geral</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li v-for="(item, index) in itemsMenu" :key="index">
            <a
              class="nav-link"
              :class="{ active: item.label === 'Home' }"
              :href="item.url"
              v-text="item.label"
            ></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "ReSidebar",
  props: {
    homeMaster: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      userName: "Jefferson",
      userImg: "https://github.com/mdo.png",
      sidebar: "no-compress",
      itemsMenu: [
        {
          label: "Home",
          icon: " <i class='fas fa-boxes'></i>",
          url: this.$route("jp_realestate.dashboard"),
        },

        {
          label: "Imóveis",
          icon: "<i class='fas fa-building'></i>",
          url: this.$route("jp_realestate.property.list"),
        },
        {
          label: "Banners",
          icon: "<i class='fas fa-building'></i>",
          url: this.$route("jp_realestate.banner.list"),
        },
      ],
      urlHome: null,
    };
  },
  computed: {
    sidebarIsCompress() {
      return this.sidebar === "compress";
    },
    classSidebar() {
      let classSidebar = "";
      if (this.sidebarIsCompress) {
        classSidebar += "sidebar-compress ";
      }
      return classSidebar;
    },
  },
  methods: {
    compressSidebar() {
      this.sidebar = "compress";
    },
    expandSidebar() {
      this.sidebar = "no-compress";
    },
  },
  mounted() {
    this.urlHome = this.homeMaster
      ? this.homeMaster
      : this.$route("jp_realestate.dashboard");
  },
};
</script>

<style lang="scss" scoped>
$sizeSidebar: 280px;

.link-dark {
  color: #212529;
}
.sidebar-master {
  width: $sizeSidebar;
  transition: width linear 150ms;
}

@media screen and (min-width: 576px) {
  .sidebar-compress {
    width: 5rem;
    .text-item-sidebar {
      span {
        opacity: 0;
      }
      .title-system {
        display: none;
      }
    }
  }
}
</style>
