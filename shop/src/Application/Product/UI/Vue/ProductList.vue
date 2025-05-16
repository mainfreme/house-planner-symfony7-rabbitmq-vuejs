<template>
  <div ref="productContainer" class="product-card">
    <div v-if="!isVisible">Ładowanie...</div>
    <img v-if="isVisible && image" :src="image" alt="Produkt" class="product-image" />
    <div v-if="isVisible && details">
      <h2>{{ details.name }}</h2>
      <p>{{ details.description }}</p>
      <p><strong>{{ details.price }} PLN</strong></p>
    </div>
    <div v-if="isVisible && availability !== null">
      <p :class="{'available': availability, 'unavailable': !availability}">
        {{ availability ? 'Dostępny' : 'Brak w magazynie' }}
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: "product_list",
  props: {
    productId: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      isVisible: false,
      image: null,
      details: null,
      availability: null
    };
  },
  mounted() {
    const observer = new IntersectionObserver((entries) => {
      if (entries[0].isIntersecting) {
        this.isVisible = true;
        this.loadProductData();
        observer.disconnect();
      }
    }, { threshold: 0.1 });

    observer.observe(this.$refs.productContainer);
  },
  methods: {
    async loadProductData() {
      try {
        const [imageResponse, detailsResponse, availabilityResponse] = await Promise.all([
          fetch(`https://api.example.com/product-image/${this.productId}`).then(res => res.json()),
          fetch(`https://api.example.com/product-details/${this.productId}`).then(res => res.json()),
          fetch(`https://api.example.com/product-availability/${this.productId}`).then(res => res.json())
        ]);

        this.image = imageResponse.url;
        this.details = detailsResponse;
        this.availability = availabilityResponse.available;
      } catch (error) {
        console.error('Błąd ładowania danych produktu:', error);
      }
    }
  }
};
</script>

<style scoped>
.product-card {
  border: 1px solid #ddd;
  padding: 16px;
  margin: 16px;
  text-align: center;
  max-width: 300px;
  box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
}
.product-image {
  max-width: 100%;
  height: auto;
  margin-bottom: 8px;
}
.available {
  color: green;
}
.unavailable {
  color: red;
}
</style>

