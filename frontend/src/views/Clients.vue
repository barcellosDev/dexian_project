<script setup>
import { onMounted, ref } from 'vue'
import useApi from '@/composables/useApi'
import LoadingOverlay from '@/components/LoadingOverlay.vue'
import NewClient from '@/views/NewClient.vue'
import Header from '@/components/Header.vue'
import ClientCard from '@/components/ClientCard.vue'
import { useAuth } from '@/composables/useAuth'
import router from '@/router'

const clients = ref([])
const isNewClient = ref(false)
const selectedClient = ref({})

const { fetchAll, destroy, data, loading, error } = useApi('clients')

onMounted(getAllClients)

async function getAllClients() {
    await fetchAll()
    clients.value = data.value.data
}

function EditClient(client) {
    isNewClient.value = true
    selectedClient.value = client
}

function CreateClient() {
    isNewClient.value = true
    selectedClient.value = {}
}

function onSavedClient(canceled = false) {
    isNewClient.value = false

    if (!canceled) {
        getAllClients()
    }

    selectedClient.value = {}
}

async function DeleteClient(clientId) {
    const { user } = useAuth()
    await destroy(clientId)

    if (error.value === null && clientId == user.value.id) {
        router.push('/login')
        sessionStorage.removeItem('auth_token')
        return
    }

    getAllClients()
}

</script>

<template>
    <div class="div-page">
        <Header>
            <button v-if="!isNewClient" @click="CreateClient">Novo cliente</button>
        </Header>

        <div class="container" v-if="!isNewClient">
            <div @click="EditClient(client)" class="client-card-wrapper selectable" v-for="(client, i) in clients"
                :key="i">
                <div class="delete-item selectable" @click.stop="DeleteClient(client.id)">
                    <span>X</span>
                </div>

                <ClientCard :clientData="client">
                </ClientCard>
            </div>
        </div>

        <NewClient v-if="isNewClient" :clientData="selectedClient" @saved-client="onSavedClient">
        </NewClient>

        <LoadingOverlay v-if="loading" />
    </div>
</template>

<style scoped>
.div-page {
    width: 100%;
    color: black;
}

.page-header {
    text-align: center;
    margin-bottom: 20px;
}

.div-inner-header {
    margin-bottom: 20px;
    text-align: end;
    display: flex;
    justify-content: space-between;
}

.container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.client-card-wrapper {
    position: relative;
}
.client-card-wrapper:not(:first-child) {
    border-top: 1px solid #eee;
    margin-top: 1.5em;
    padding-top: 1.5em;
}

</style>