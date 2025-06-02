<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import LoadingOverlay from '@/components/LoadingOverlay.vue'
import useApi from '@/composables/useApi'
import { callApi } from '@/composables/callApi'

const { create, loading, error } = useApi('clients')
const router = useRouter()

const name = ref('')
const email = ref('')
const call_number = ref()
const birth_date = ref()
const address = ref()
const address_complement = ref()
const neighborhood = ref()
const postal_address_code = ref()

const isRegister = ref(false)

async function Submit() {
  // Pega o cookie CSRF
  await fetch('http://localhost:8000/sanctum/csrf-cookie', {
    credentials: 'include'
  })

  let body = JSON.stringify({ email: email.value })

  if (isRegister.value) {
    body = JSON.stringify({
      name: name.value,
      email: email.value,
      call_number: call_number.value,
      birth_date: birth_date.value,
      address: address.value,
      address_complement: address_complement.value,
      neighborhood: neighborhood.value,
      postal_address_code: postal_address_code.value,
    })

    await create(body)

  } else {
    let res = await callApi(`login`, {
      method: 'POST',
      body,
    })

    if (!res.success) {
      error.value = res.message
      return
    }

    sessionStorage.setItem('auth_token', res.data);
  }

  if (error.value !== null) {
    return
  }

  router.push('products')
}

function Toggle() {
  isRegister.value = !isRegister.value
  error.value = null
}

</script>

<template>
  <div class="auth-container">
    <h2>{{ isRegister ? 'Cadastrar' : 'Login' }}</h2>

    <form @submit.prevent="Submit">

      <div v-if="isRegister">
        <input v-model="name" placeholder="Name" required />
      </div>
      <div>
        <input type="email" v-model="email" placeholder="E-email">
      </div>
      <div v-if="isRegister">
        <input type="text" v-model="call_number" placeholder="Número de telefone">
      </div>
      <div v-if="isRegister">
        <input type="date" v-model="birth_date" placeholder="Data de nascimento">
      </div>
      <div v-if="isRegister">
        <input type="text" v-model="address" placeholder="Endereço">
      </div>
      <div v-if="isRegister">
        <input type="text" v-model="address_complement" placeholder="Complemento">
      </div>
      <div v-if="isRegister">
        <input type="text" v-model="neighborhood" placeholder="Bairro">
      </div>
      <div v-if="isRegister">
        <input type="text" v-model="postal_address_code" placeholder="CEP">
      </div>

      <button>{{ isRegister ? 'Cadastrar' : 'Login' }}</button>

    </form>

    <p class="error">{{ error }}</p>

    <p class="switch">
      <span v-if="!isRegister">Não possui uma conta?</span>
      <span v-else>Já possui uma conta?</span>
      <button @click="Toggle">{{ isRegister ? 'Login' : 'Cadastrar' }}</button>
    </p>

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