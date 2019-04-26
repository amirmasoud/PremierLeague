<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
      <router-link :to="{ name: 'welcome' }" class="navbar-brand">{{ appName }}</router-link>

      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <v-button @click.native="resetData()" type="danger" :loading="resetingData">Reset Data</v-button>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import axios from "axios";

export default {
  data: () => ({
    appName: window.config.appName,
    resetingData: false
  }),

  methods: {
    async resetData() {
      this.resetingData = true;
      await axios.get("/api/reset-data");
      this.$root.$emit("data-reset");
      this.resetingData = false;
    }
  }
};
</script>
