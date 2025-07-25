<template>
  <svg
      v-if="active"
      :width="size"
      :height="size"
      viewBox="0 0 38 38"
      xmlns="http://www.w3.org/2000/svg"
      aria-label="loading"
      role="status"
      fill="none"
      :style="{ color }"
      class="small-loader"
  >
    <circle
        cx="19"
        cy="19"
        r="17"
        :stroke-width="strokeWidth"
        stroke="currentColor"
        stroke-opacity="0.2"
    />
    <path
        d="M36 19c0-9.39-7.61-17-17-17"
        :stroke-width="strokeWidth"
        stroke="currentColor"
        stroke-linecap="round"
    >
      <animateTransform
          attributeName="transform"
          type="rotate"
          from="0 19 19"
          to="360 19 19"
          :dur="duration"
          repeatCount="indefinite"
      />
    </path>
  </svg>
</template>

<script>

export default {
  name: 'SmallLoader',
  props: {
    size: {
      type: Number,
      default: 20,
    },
    color: {
      type: String,
      default: '#3498db',
    },
    strokeWidth: {
      type: Number,
      default: 4,
    },
    duration: {
      type: String,
      default: '0.8s',
    },
    active: {
      type: Boolean,
      default: true,
    },
  },
}
</script>

<style scoped>
.small-loader {
  display: inline-block;
  vertical-align: middle;
}
</style>
