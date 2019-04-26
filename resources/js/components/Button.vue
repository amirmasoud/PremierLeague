<template>
  <button
    :type="nativeType"
    :disabled="loading || disabled"
    :class="{
      [`btn-${type}`]: true,
      'btn-block': block,
      'btn-lg': large,
      'btn-loading': loading,
      extraClasses: true
    }"
    class="btn"
  >
    <slot/>
  </button>
</template>

<script>
export default {
  name: "VButton",

  props: {
    type: {
      type: String,
      default: "primary"
    },

    nativeType: {
      type: String,
      default: "submit"
    },

    loading: {
      type: Boolean,
      default: false
    },

    block: {
      type: Boolean,
      default: false
    },

    large: {
      type: Boolean,
      default: false
    },

    disabled: {
      type: Boolean,
      default: false
    },

    extraClasses: {
      type: String,
      default: ""
    }
  }
};
</script>
