<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
      <router-link :to="{ name: user ? 'home' : 'welcome' }" class="navbar-brand">
        {{ appName }}
      </router-link>
    </div>
  </nav>
</template>

<script>
export default {
  data: () => ({
    appName: window.config.appName
  })
}
</script>
