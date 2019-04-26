<template>
  <card title="Premier League Table">

  </card>
</template>

<script>
export default {
  layout: 'default',

  metaInfo () {
    return { title: this.$t('Premier League') }
  },

  data: () => ({
    title: window.config.appName
  })
}
</script>
