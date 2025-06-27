<template>
  <form @submit.prevent="submit" class="form">
    <div>
      <label>Email</label>
      <input v-model="form.email" type="email" />
    </div>

    <div>
      <label>Password</label>
      <input v-model="form.password" type="password" />
    </div>

    <div v-if="error" class="error">{{ error }}</div>

    <button type="submit" :disabled="loading">Login</button>

    <div class="status-message">
      <template v-if="status === 'success'">
        ✅ Login successful! Redirecting...
      </template>
      <template v-else-if="status === 'error'">
        ❌ Invalid credentials.
      </template>
    </div>
    <p class="form-switch">
      Don’t have an account?
      <a href="#" @click.prevent="$emit('switchForm', 'register')">Register</a>
    </p>
  </form>
</template>

<script>
import axios from 'axios'

export default {
  name: 'LoginForm',
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      error: '',
      status: null,
      loading: false
    }
  },
  methods: {
    async submit() {
      this.error = ''
      this.status = null
      this.loading = true

      try {
        const response = await axios.post('/api/login_check', {
          username: this.form.email,
          password: this.form.password
        })
        localStorage.setItem('token', response.data.token)
        this.status = 'success'
        setTimeout(() => {
          window.location.href = 'http://localhost:8080'
        }, 2000)
      } catch (e) {
        this.status = 'error'
        this.error = 'Invalid email or password.'
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
.error {
  color: red;
  margin-top: 5px;
}
.status-message {
  margin-top: 10px;
  font-weight: bold;
}
.form-switch {
  margin-top: 10px;
}
</style>
