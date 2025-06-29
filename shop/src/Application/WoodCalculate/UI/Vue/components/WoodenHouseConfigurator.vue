<template>
  <div class="relative h-screen flex p-4 gap-4">
    <!-- Loader -->
    <Loader v-if="loading" class="absolute inset-0 z-50" />

    <!-- Save Button -->
    <button
        @click="saveConfiguration"
        class="absolute top-4 left-4 z-20 bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700"
    >
      Save
    </button>

    <!-- Panel komponentów -->
    <div class="w-1/4 bg-gray-100 rounded-xl p-4 z-10">
      <h2 class="text-xl font-bold mb-2">Komponenty</h2>
      <div
          v-for="(component, index) in components"
          :key="index"
          draggable="true"
          @dragstart="onDragStart(component)"
          class="cursor-move mb-4 p-2 bg-white rounded shadow hover:bg-gray-50 flex items-center gap-2"
      >
        <img :src="component.image" class="w-12 h-12 object-contain" />
        <div>
          <div class="font-semibold">{{ component.name }}</div>
          <div class="text-xs text-gray-500">{{ component.width }}×{{ component.height }} cm</div>
        </div>
      </div>
    </div>

    <!-- Obszar projektu -->
    <div
        class="flex-1 bg-green-100 rounded-xl relative overflow-hidden"
        @dragover.prevent
        @drop="onDrop"
        ref="canvas"
    >
      <h2 class="absolute top-2 left-2 text-xl font-bold z-0">Projekt domku</h2>

      <!-- Umieszczone komponenty -->
      <div
          v-for="(item, index) in placedComponents"
          :key="index"
          class="absolute border rounded shadow cursor-move z-10 flex flex-col items-center text-xs bg-white p-1"
          :style="{
    top: item.y + 'px',
    left: item.x + 'px',
    width: item.width + 'px',
    height: item.height + 'px'
  }"
          @mousedown="startDrag(index, $event)"
      >
        <img :src="item.image" class="object-contain max-w-full max-h-full" />
        <div>{{ item.name }}</div>
        <div>{{ item.width }}×{{ item.height }} cm</div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import html2canvas from 'html2canvas'
import Loader from '@/component/Loader.vue'

export default {
  name: 'WoodenHouseConfigurator',
  components: {
    Loader
  },
  setup() {
    const components = ref([
      { name: 'Drzwi' },
      { name: 'Okno' },
      { name: 'Ściana' },
    ])
    const currentDrag = ref(null)
    const placedComponents = ref([])
    const canvas = ref(null)
    const loading = ref(false)

    // Snap to grid size
    const gridSize = 20

    const onDragStart = (component) => {
      currentDrag.value = component
    }

    const onDrop = (event) => {
      const rect = canvas.value.getBoundingClientRect()
      const x = Math.round((event.clientX - rect.left) / gridSize) * gridSize
      const y = Math.round((event.clientY - rect.top) / gridSize) * gridSize
      placedComponents.value.push({
        name: currentDrag.value.name,
        width: currentDrag.value.width,
        height: currentDrag.value.height,
        image: currentDrag.value.image,
        x,
        y,
      })
    }

    // Przeciąganie istniejących komponentów
    let draggingIndex = null
    let offsetX = 0
    let offsetY = 0

    const startDrag = (index, event) => {
      draggingIndex = index
      offsetX = event.offsetX
      offsetY = event.offsetY
      document.addEventListener('mousemove', onMouseMove)
      document.addEventListener('mouseup', stopDrag)
    }

    const onMouseMove = (event) => {
      if (draggingIndex === null) return
      const rect = canvas.value.getBoundingClientRect()
      let x = event.clientX - rect.left - offsetX
      let y = event.clientY - rect.top - offsetY

      // snap to grid
      x = Math.round(x / gridSize) * gridSize
      y = Math.round(y / gridSize) * gridSize

      placedComponents.value[draggingIndex].x = x
      placedComponents.value[draggingIndex].y = y
    }

    const stopDrag = () => {
      draggingIndex = null
      document.removeEventListener('mousemove', onMouseMove)
      document.removeEventListener('mouseup', stopDrag)
    }

    // Zapis konfiguracji (JSON + obraz)
    const saveConfiguration = async () => {
      loading.value = true

      try {
        // Pobierz zrzut canvas jako base64
        const snapshot = await html2canvas(canvas.value, { backgroundColor: null })
        const imageBase64 = snapshot.toDataURL('image/png')

        // Przygotuj payload do API
        const payload = {
          components: placedComponents.value,
          snapshot: imageBase64,
          name: 'Projekt użytkownika', // możesz dodać formularz z nazwą
        }

        // Wysyłka do backendu
        const response = await fetch('/api/projects/house-configurator/save', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(payload)
        })

        if (!response.ok) {
          throw new Error('Błąd podczas zapisywania projektu')
        }

        const data = await response.json()
        alert(`Zapisano projekt! ID: ${data.id || 'brak'}`)
      } catch (err) {
        console.error('Błąd zapisu:', err)
        alert('Wystąpił błąd podczas zapisu projektu.')
      } finally {
        loading.value = false
      }
    }

    onBeforeUnmount(() => {
      stopDrag()
    })

    return {
      components,
      onDragStart,
      onDrop,
      placedComponents,
      startDrag,
      canvas,
      saveConfiguration,
      loading
    }
  }
}
</script>

<style scoped>
</style>

