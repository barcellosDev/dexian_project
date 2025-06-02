<script setup>
import { onMounted, ref } from 'vue'
import useApi from '@/composables/useApi'
import ProductModal from '@/components/ProductModal.vue'
import Header from '@/components/Header.vue'
import LoadingOverlay from '@/components/LoadingOverlay.vue'
import ProductCard from '@/components/ProductCard.vue'

const products = ref(null)
const showModal = ref(false)
const selectedProduct = ref({})
const { fetchAll, destroy, data, loading, error } = useApi('products')

function ShowModal(product = {}) {
    showModal.value = true
    selectedProduct.value = product
}

function ProductCreated(product) {
    products.value.push(product)
    selectedProduct.value = products.value[products.value.length - 1]
}

async function GetAllProducts() {
    await fetchAll()
    products.value = data.value.data
}

onMounted(GetAllProducts)

async function DeleteProduct(productId) {
    await destroy(productId)
    GetAllProducts()
}

</script>

<template>
    <div class="div-page">

        <Header>
            <button @click="ShowModal()">Novo produto</button>
        </Header>

        <div class="container">
            <div class="sub-header">
                <h1>Produtos</h1>
            </div>

            <div class="products">
                <div class="product-card-wrapper" v-for="(product, i) in products" :key="i">
                    <div class="delete-item selectable" @click="DeleteProduct(product.id)">
                        <span>X</span>
                    </div>
                    <ProductCard @click="ShowModal(product)" class="selectable" :product="product">
                    </ProductCard>
                </div>
            </div>
        </div>

        <div v-if="showModal">
            <ProductModal :product="selectedProduct" @close-modal="showModal = !showModal"
                @product-created="ProductCreated($event)" />
        </div>

        <LoadingOverlay v-if="loading" />
    </div>
</template>

<style scoped>
.sub-header {
    text-align: center;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: #fff8e1;
}

.container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.products {
    align-items: center;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
}

.product-card-wrapper {
    position: relative;
    padding: 20px;
}
</style>