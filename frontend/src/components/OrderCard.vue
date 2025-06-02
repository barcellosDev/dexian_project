<script setup>
import ProductCard from '@/components/ProductCard.vue'

const props = defineProps({
    order: Object
})

function FormatDateTime(dateTimeString) {
    const date = new Date(props.order.created_at)
    return `${date.toLocaleDateString()} ${date.toLocaleTimeString()}`
}
</script>

<template>
    <div>
        <div class="header">
            <h1>Pedido #{{ props.order.id }}</h1>
            <span>Criado em: {{ (FormatDateTime(props.order.created_at)) }}</span>
        </div>

        <div class="products">
            <ProductCard v-for="(product, i) in props.order.products" :key="i" :product="product">
            </ProductCard>
        </div>
    </div>
</template>

<style scoped>
.header {
    border-bottom: #ddd 1px solid;
    padding-bottom: 20px;
}

.products {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
}
</style>