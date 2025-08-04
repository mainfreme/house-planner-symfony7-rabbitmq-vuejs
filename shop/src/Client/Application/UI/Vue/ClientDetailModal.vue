<template>
  <slot name="button" :toggle="toggle">
    <button
        class="btn btn-outline-secondary btn-sm"
        @click="toggle('show')"
    >
      Szczegóły
    </button>
  </slot>

  <teleport to="body">
    <div v-if="visible">
      <div class="modal-backdrop fade show" @click="toggle('hide')"></div>

      <div
          class="modal d-block"
          ref="modal"
          :style="modalStyle"
          @mousedown.stop
      >
        <div class="modal-dialog modal-xl">
          <div class="modal-content">

            <!-- Nagłówek -->
            <div class="modal-header cursor-move" @mousedown="startDrag">
              <h5 class="modal-title">Szczegóły klienta</h5>
              <button type="button" class="btn-close" @click="toggle('hide')" />
            </div>

            <!-- Zakładki -->
            <div class="modal-body">
              <nav class="nav nav-tabs mb-3">
                <button
                    v-for="tab in tabs"
                    :key="tab.key"
                    @click="activeTab = tab.key"
                    class="nav-link"
                    :class="{ active: activeTab === tab.key }"
                >
                  {{ tab.label }}
                </button>
              </nav>

              <Suspense>
                <template #default>
                  <component :is="tabComponent" :clientId="props.clientId" />
                </template>
                <template #fallback>
                  <div class="text-muted">Ładowanie zakładki...</div>
                </template>
              </Suspense>
            </div>

            <div class="modal-footer">
              <button class="btn btn-secondary" @click="toggle('hide')">Zamknij</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </teleport>
</template>


<script setup>
import { ref, computed, defineAsyncComponent, defineProps, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  clientId: {
    type: Number,
    required: false,
    default: null
  }
})

const visible = ref(false)
const activeTab = ref('general')

const componentsMap = {
  general: defineAsyncComponent(() => import('./ClientGeneral.vue')),
  contacts: defineAsyncComponent(() => import('./ContactCard.vue')),
  addresses: defineAsyncComponent(() => import('./AddressCard.vue'))
}

const tabs = [
  { key: 'general', label: 'Ogólne' },
  { key: 'contacts', label: 'Kontakty' },
  { key: 'addresses', label: 'Adresy' }
]

const tabComponent = computed(() => componentsMap[activeTab.value])

function toggle(action) {
  visible.value = action === 'show'
}

// === Zamykanie ESC ===
function onKeyDown(event) {
  if (event.key === 'Escape') {
    toggle('hide')
  }
}

onMounted(() => {
  document.addEventListener('keydown', onKeyDown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', onKeyDown)
})

// === DRAGGABLE ===
const modal = ref(null)
const modalStyle = ref({
  position: 'fixed',
  top: '100px',
  left: '50%',
  transform: 'translateX(-50%)',
  zIndex: 1050
})

let offset = { x: 0, y: 0 }
let dragging = false

function startDrag(event) {
  const el = modal.value
  dragging = true

  const rect = el.getBoundingClientRect()
  offset = {
    x: event.clientX - rect.left,
    y: event.clientY - rect.top
  }

  document.addEventListener('mousemove', onDrag)
  document.addEventListener('mouseup', stopDrag)
}

function onDrag(event) {
  if (!dragging) return

  modalStyle.value = {
    ...modalStyle.value,
    left: `${event.clientX - offset.x}px`,
    top: `${event.clientY - offset.y}px`,
    transform: 'none'
  }
}

function stopDrag() {
  dragging = false
  document.removeEventListener('mousemove', onDrag)
  document.removeEventListener('mouseup', stopDrag)
}
</script>

<style scoped>
.cursor-move {
  cursor: move;
}
</style>
