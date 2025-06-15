<template>
  <div class="auth-wrapper">
    <RegisterForm v-if="activeForm === 'register'" @switchForm="switchForm" />
    <LoginForm v-else @switchForm="switchForm" />
  </div>
</template>

<script>
import RegisterForm from './RegisterForm.vue'
import LoginForm from './LoginForm.vue'

export default {
  name: 'AuthView',
  components: {
    RegisterForm,
    LoginForm
  },
  data() {
    return {
      activeForm: 'login' // lub 'register'
    }
  },
  methods: {
    switchForm(to) {
      this.activeForm = to
    }
  }
}
</script>
