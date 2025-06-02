<script setup>
import { onMounted, ref } from 'vue'
import useApi from '@/composables/useApi'
import LoadingOverlay from '@/components/LoadingOverlay.vue'
import Header from '@/components/Header.vue'
import OrderCard from '@/components/OrderCard.vue'
import NewOrder from './NewOrder.vue'

const isNewOrder = ref(false)
const orders = ref([])

const selectedOrder = ref({})

const { fetchAll, destroy, data, loading } = useApi('orders')

onMounted(GetAllOrders)

async function GetAllOrders() {
    await fetchAll()
    orders.value = data.value.data
}

async function DeleteOrder(orderId) {
    await destroy(orderId)
    GetAllOrders()
}

function EditOrder(order) {
    selectedOrder.value = order
    isNewOrder.value = true
}

function onSavedOrder(canceled = false) {
    isNewOrder.value = false

    if (!canceled) {
        GetAllOrders()
    }

    selectedOrder.value = {}
}

</script>

<template>
    <div class="div-page">
        <Header>
             <button @click="isNewOrder = true" v-if="!isNewOrder">Novo pedido</button>
        </Header>

        <div class="container" v-if="!isNewOrder">
            <h1 v-if="orders.length === 0">
                Nenhum pedido realizado
            </h1>
            <div @click="EditOrder(order)" class="order-card-wrapper" v-for="(order, i) in orders" :key="i">
                <div class="delete-item selectable delete-order" @click.stop="DeleteOrder(order.id)">
                    <span>X</span>
                </div>
                <OrderCard class="selectable" :order="order">
                </OrderCard>
            </div>
        </div>

        <NewOrder v-else @saved-order="onSavedOrder" :order="selectedOrder">
        </NewOrder>


        <LoadingOverlay v-if="loading" />
    </div>
</template>

<style scoped>
.div-page {
    width: 100%;
    color: black;
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

.order-card-wrapper {
    position: relative;
}

.order-card-wrapper:not(:first-child) {
    padding-top: 2em;
    word-break: keep-all;
}

.delete-order {
    right: -25px;
    top: -25px;
}
</style>