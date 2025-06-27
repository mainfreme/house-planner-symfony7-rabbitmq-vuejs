<template>
  <form @submit.prevent="submit" class="form">
    <div>
      <label>Email</label>
      <input v-model="form.email" type="email" />
      <div v-if="errors.email">
        <small v-for="err in errors.email" :key="err">{{ err }}</small>
      </div>
    </div>

    <div>
      <label>Password</label>
      <input v-model="form.password" type="password" />
      <div v-if="errors.plainPassword">
        <small v-for="err in errors.plainPassword" :key="err">{{ err }}</small>
      </div>
    </div>

    <button type="submit" :disabled="loading">Register</button>

    <div class="status-message">
      <template v-if="status === 'success'">
        ✅ Registration successful! Redirecting...
      </template>
      <template v-else-if="status === 'error'">
        ❌ Registration failed. Check form errors.
      </template>
    </div>
    <p class="form-switch">
      Already have an account?
      <a href="#" @click.prevent="$emit('switchForm', 'login')">Log in</a>
    </p>
  </form>
</template>

<script>
import axios from 'axios'

export default {
  name: 'RegisterForm',
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      errors: {},
      status: null, // 'success' | 'error' | null
      loading: false
    }
  },
  methods: {
    async submit() {
      this.errors = {}
      this.status = null
      this.loading = true

      try {
        await axios.post('/api/register', {
          email: this.form.email,
          plainPassword: this.form.password
        })
        this.status = 'success'
        setTimeout(() => {
          window.location.href = 'http://localhost:8080'
        }, 2000)
      } catch (error) {
        this.status = 'error'
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors
        }
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
.status-message {
  margin-top: 10px;
  font-weight: bold;
}
.form-switch {
  margin-top: 10px;
}
</style>
