<template>
  <button
      class="btn position-absolute"
      :class="isInWishlist ? 'btn-outline-success' : 'btn-success'"
      style="top: 2px; right: 2px;"
      @click="toggleWishlist"
      :disabled="loading"
  >
    <i
        class="fa-heart"
        :class="[isInWishlist ? 'fa-solid text-success' : 'fa-regular text-light']"
    >
      <SmallLoader :active="smallLoading" />
    </i>
  </button>
</template>

<script>
import {ref, onMounted} from 'vue'
import axios from 'axios'
import SmallLoader from '@/component/SmallLoader';


export default {
  name: "AddToWishlistButton",
  components: {
    SmallLoader,
  },
  props: {
    productUuid: {type: String, required: true}
  },
  data() {
    return {
      smallLoading: false,
      isInWishlist: false,
    }
  },
  mounted() {
    this.toggleWishlist();
  },
  methods: {
    async toggleWishlist() {
      this.smallLoading = true;
      try {
        const response = await axios.post('/api/wishlist/toggle', {
          uuid: props.productUuid
        })

        isInWishlist.value = response.data.inWishlist
      } catch (error) {
        console.error('Błąd dodawania do listy marzeń:', error)
      } finally {
        loading.value = false
      }
    }
  }
}
</script>
