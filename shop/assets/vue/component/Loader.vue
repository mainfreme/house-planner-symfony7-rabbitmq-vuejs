<template>
  <div class="loader-overlay">
    <div class="loader-spinner"></div>
  </div>
</template>

<script setup>
// Ten komponent nie przyjmuje propsów, bo v-if na <Loader /> jest sterowany z zewnątrz
</script>

<style scoped>
.loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.loader-spinner {
  border: 6px solid #ccc;
  border-top: 6px solid #42b983;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
