<template>
  <teleport to="body">
    <div
        class="modal fade show"
        tabindex="-1"
        style="display: block; background-color: rgba(0,0,0,0.5);"
        role="dialog"
        v-if="showModal"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Potwierdzenie</h5>
            <button type="button" class="btn-close" @click="$emit('close')" aria-label="Zamknij"></button>
          </div>
          <div class="modal-body">
            <div v-if="error" class="alert alert-danger mt-2">
              {{ error }}
            </div>
            <p>
              Czy na pewno chcesz usunąć klienta
              <strong>{{ clientToDelete?.name }}</strong>?
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="$emit('close')">Anuluj</button>
            <button type="button" class="btn btn-danger" @click="deleteObject" :disabled="smallLoading">
              <SmallLoader :active="smallLoading"/>
              Usuń
            </button>
          </div>
        </div>
      </div>
    </div>
  </teleport>
</template>

<script>

import SmallLoader from '@/component/SmallLoader';

export default {
  name: 'DeletePopup',
  components: {
    SmallLoader
  },
  props: {
    showModal: {
      type: Boolean,
      required: true
    },
    clientToDelete: {
      type: Object,
      default: null
    },
    error: {
      type: String,
      default: ''
    }
  },
  computed() {
    this.error = '';
  },
  data() {
    return {
      smallLoading: false,
    }
  },
  watch: {
    error(newVal) {
      if (newVal) {
        this.smallLoading = false;
      }
    }
  },
  methods: {
    deleteObject() {
      this.smallLoading = true;
      this.$emit('confirm-delete');
    }
  }
}
</script>
