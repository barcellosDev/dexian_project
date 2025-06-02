<script setup>
import { onMounted, ref } from 'vue'
import useApi from '@/composables/useApi'
import LoadingOverlay from '@/components/LoadingOverlay.vue'
import ProductCard from '@/components/ProductCard.vue'

const addedProducts = ref([])
const products = ref([])

const orderApi = useApi('orders')
const productsApi = useApi('products')

const productsApiLoading = ref(productsApi.loading)
const ordersApiLoading = ref(orderApi.loading)

const emits = defineEmits(['saved-order'])

const props = defineProps({
    order: Object
})

onMounted(getProducts)

async function getProducts() {

    // EDITING ORDER
    if (props.order?.id) {
        addedProducts.value = props.order.products
    }

    await productsApi.fetchAll()
    products.value = productsApi.data.value.data
}

function AddProduct(product) {
    const hasProduct = addedProducts.value.filter(needle => needle.id == product.id)
    console.log(hasProduct)

    if (hasProduct.length === 0)
        addedProducts.value.push(product)
}

async function SaveOrder() {
    const { create, update, error } = orderApi

    // EDITING ORDER
    if (props.order?.id) {
        const payload = new FormData()
        payload.append('_method', 'PUT')
        payload.append('products', JSON.stringify(addedProducts.value))

        await update(props.order?.id, payload)

    } else {
        const payload = JSON.stringify(addedProducts.value.map(product => product.id))
        await create(payload)
    }

    if (error.value !== null) {
        alert(error.value)
        return
    }

    emits('saved-order')
}

function RemoveProductFromAdded(productId) {
    addedProducts.value = addedProducts.value.filter(needle => needle.id != productId)
}

</script>

<template>
    <div class="div-page">

        <div class="container">
            <ProductCard class="selectable" v-for="(product, i) in products" :key="i" :product="product"
                @click="AddProduct(product)">
            </ProductCard>
        </div>

        <div>
            <div class="inner-container">
                <div class="header">
                    <h1 v-if="props.order?.id">Pedido #{{ props.order.id }}</h1>
                    <h1 v-if="!props.order?.id">Produtos adicionados</h1>
                </div>

                <div class="selected-products">
                    <div class="product-card-wrapper" v-for="(product, i) in addedProducts" :key="i">
                        <div class="delete-item selectable" @click="RemoveProductFromAdded(product.id)">
                            <span>X</span>
                        </div>
                        <ProductCard class="disabled" :product="product">
                        </ProductCard>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <button @click="emits('saved-order', true)">Voltar</button>
            <button id="save-order" @click="SaveOrder">Salvar pedido</button>
        </div>

        <LoadingOverlay v-if="productsApiLoading || ordersApiLoading" />
    </div>
</template>

<style scoped>
.disabled {
    pointer-events: none;
}

.product-card-wrapper {
    position: relative;
}

.div-page {
    width: 100%;
    color: black;
}

.container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);

    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 30px;
}

.header {
    margin-bottom: 2em;
}

.inner-container {
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background: white;
    width: 80%;
    padding: 20px;
}

.selected-products {
    display: flex;
    overflow-x: auto;
    gap: 50px;
}
</style>