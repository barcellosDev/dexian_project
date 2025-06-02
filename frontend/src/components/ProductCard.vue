<script setup>
const props = defineProps({
    product: Object
})

const defaultNoImage = `http://localhost:8000/storage/images/no-image.jpg`
</script>

<template>
    <div class="pastel">
        <span class="name">{{ props.product.name }}</span>
        <p class="price">R$ {{ props.product.price }}</p>
        <div class="picture">
            <img :src="props.product.photo_src ?? defaultNoImage">
        </div>
        
    </div>
</template>

<style scoped>
.pastel {
    padding: 15px 0;
    word-break: keep-all;
}

.pastel:last-child {
    border-bottom: none;
}

.pastel .name {
    font-size: 1.5em;
    color: #e65100;
}

.pastel p {
    margin: 5px 0;
    color: #555;
}

.price {
    font-weight: bold;
    color: #2e7d32;
}

.picture {
    width: 10em;
    height: 10em;
}

.picture>img {
    width: 100%;
    height: 100%;
}
</style>