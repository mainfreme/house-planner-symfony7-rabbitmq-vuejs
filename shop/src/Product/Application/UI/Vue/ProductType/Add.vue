<template>
  <div>
<!--    <Notification v-if="notification.message" :message="notification.message" :type="notification.type"/>-->
    <form @submit.prevent="submitForm">
      <div>
        <label for="name">Nazwa</label>
        <input type="text" id="name" v-model="form.name"/>
        <span v-if="errors.name" class="error">{{ errors.name }}</span>
      </div>

      <div>
        <label for="link">Link</label>
        <input type="text" id="link" v-model="form.link"/>
        <span v-if="errors.link" class="error">{{ errors.link }}</span>
      </div>

      <div>
        <label>
          <input type="checkbox" v-model="form.is_public"/> Publiczny
        </label>
        <span v-if="errors.is_public" class="error">{{ errors.is_public }}</span>
      </div>

      <button type="submit">Zapisz</button>
      <span v-if="generalError" class="error">{{ generalError }}</span>
    </form>
  </div>
</template>

<script>
// import Notification from "./Notification.vue";

export default {
  name: "ProductTypeAdd",
  // components: {Notification},
  data() {
    return {
      form: {
        name: "",
        link: "",
        is_public: false,
      },
      errors: {},
      generalError: "",
      // notification: {
      //   message: "",
      //   type: "success",
      // },
    };
  },
  methods: {
    async submitForm() {
      try {
        const response = await fetch("/api/product/type/add", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(this.form),
        });

        if (!response.ok) {
          const errorData = await response.json();
          if (errorData.errors) {
            this.errors = errorData.errors;
          } else {
            this.generalError = "Wystąpił błąd podczas zapisu.";
          }
          return;
        }

        // this.notification.message = "Produkt dodany!";
        // this.notification.type = "success";
        // setTimeout(() => {
        //   this.notification.message = "";
        // }, 5000);

        this.form = {name: "", link: "", is_public: false};
        this.errors = {};
        this.generalError = "";
      } catch (error) {
        this.generalError = "Błąd połączenia z serwerem.";
      }
    },
  },
};
</script>

<style>
.error {
  color: red;
  font-size: 0.9em;
}
</style>
