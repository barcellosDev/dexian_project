<script setup>
import { useAuth } from '@/composables/useAuth'
import { callApi } from '@/composables/callApi';
import LoadingOverlay from './LoadingOverlay.vue';
import router from '@/router';
import { ref } from 'vue';

const { user } = useAuth()
const loading = ref(false)

async function Logout() {
    loading.value = true
    await callApi('logout')
    loading.value = false
    sessionStorage.removeItem('auth_token')
    router.push('/login')
}

</script>

<template>
    <h1 class="page-header">
        <div id="welcome">
            Bem-vindo {{ user.name }}
        </div>

        <div class="div-inner-header">
            <slot>
                <div></div>
            </slot>
            <div id="links">
                <button class="link" @click="router.push('clients')">Clientes</button>
                <button class="link" @click="router.push('products')">Produtos</button>
                <button class="link" @click="router.push('orders')">Pedidos</button>
                <button class="link" id="logout" @click="Logout()">Sair</button>
            </div>
        </div>
    </h1>
    <LoadingOverlay v-if="loading" />
</template>

<style scoped>
.page-header {
    text-align: center;
    margin-bottom: 20px;
    background-color: #FFD6BA;
    padding: .8rem;
}

#links {
    display: flex;
    gap: 20px;
}

.link {
    border: none;
    padding: 5px;
}

.div-inner-header {
    text-align: end;
    display: flex;
    justify-content: space-between;
}

#logout {
    background-color: #CB0404;
    color: white;
}
</style>