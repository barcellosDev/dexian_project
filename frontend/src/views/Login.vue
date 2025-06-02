<script setup>
import { ref } from 'vue'
import router from '@/router'
import LoadingOverlay from '@/components/LoadingOverlay.vue'
import useApi from '@/composables/useApi'
import { callApi } from '@/composables/callApi'

const { error, loading } = useApi('clients')
const email = ref('')

async function Submit() {
  // Pega o cookie CSRF
  await fetch('http://localhost:8000/sanctum/csrf-cookie', {
    credentials: 'include'
  })

  let body = JSON.stringify({ email: email.value })

  loading.value = true

  let res = await callApi(`login`, {
    method: 'POST',
    body,
  })

  loading.value = false

  if (!res.success) {
    error.value = res.message
    return
  }

  sessionStorage.setItem('auth_token', res.data);

  if (error.value !== null) {
    return
  }

  router.push('products')
}

</script>

<template>
  <div class="auth-container">
    <h2>Login</h2>

    <form @submit.prevent="Submit">
      <div>
        <input type="email" v-model="email" placeholder="E-email">
      </div>

      <button>Login</button>
    </form>

    <p class="error">{{ error }}</p>

    <LoadingOverlay v-if="loading" />
  </div>
</template>

<style scoped>
body {
  background: linear-gradient(135deg, #fdfcfb, #e2d1c3);
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  margin: 0;
}

.auth-container {
  background: white;
  padding: 30px 20px 60px;
  /* Mais espaço na parte de baixo */
  border-radius: 8px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  width: 320px;
  text-align: center;
  border-bottom: 30px solid #eaeaea;
  /* Polaroid bottom border */
  margin: 17% auto;
}

h2 {
  margin-bottom: 20px;
  color: #333;
}

input {
  width: 100%;
  padding: 10px;
  margin-bottom: 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-family: inherit;
  font-size: 14px;
}

button {
  width: 100%;
  background: #333;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 10px;
  transition: background 0.2s ease;
  font-family: inherit;
}

button:hover {
  background: #555;
}

.error {
  color: red;
  margin-top: 10px;
  font-size: 13px;
}

.switch {
  margin-top: 20px;
  font-size: 14px;
  color: #555;
}

.switch button {
  background: none;
  color: #0077cc;
  border: none;
  padding: 0;
  cursor: pointer;
  font-weight: bold;
}

.switch button:hover {
  text-decoration: underline;
}
</style>