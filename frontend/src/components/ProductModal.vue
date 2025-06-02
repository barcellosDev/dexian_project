<script setup>
import { defineProps, defineEmits, computed, ref } from 'vue'
import useApi from '@/composables/useApi'
import LoadingOverlay from './LoadingOverlay.vue'
import { useValidators } from '@/composables/useValidators'

const props = defineProps(['product'])
const emits = defineEmits(['close-modal', 'product-created'])
const { update, error, create, data, loading } = useApi('products')

const defaultNoImage = `http://localhost:8000/storage/images/no-image.jpg`
const selectedFile = ref()
const fileInput = ref(null)

const onFileChange = (event) => {
    selectedFile.value = event.target.files[0]
    const file = event.target.files[0]

    const reader = new FileReader()
    reader.onload = () => {
        productImage.value = reader.result
    }
    reader.readAsDataURL(file)
    event.target.value = ''
}

const TriggerFileInput = () => {
    fileInput.value.click();
};

async function SubmitUpdate() {

    const formData = new FormData()

    formData.append('_method', 'PUT')
    formData.append('name', props.product.name)
    formData.append('price', props.product.price)
    formData.append('photo', selectedFile.value)

    await update(props.product.id, formData)
    if (error.value)
        alert(error.value.message)
    else
        Object.assign(props.product, data.value.data)

}

async function CreateProduct() {
    const formData = new FormData()

    const { setFieldToError } = useValidators()

    let hasErrors = false

    if (name.value.trim() === '') {
        const nameElement = document.getElementById('product-name')
        setFieldToError(nameElement)
        hasErrors = true
    }

    if (price.value.trim() === '') {
        const priceElement = document.getElementById('product-price')
        setFieldToError(priceElement)
        hasErrors = true
    }

    if (hasErrors) {
        return
    }

    if (props.product.name)
        formData.append('name', props.product.name)

    if (props.product.price)
        formData.append('price', props.product.price)

    if (selectedFile.value)
        formData.append('photo', selectedFile.value)

    await create(formData)

    if (error.value !== null) {
        alert(error.value)
        return
    }
    
    Object.assign(props.product, data.value.data)
    emits('product-created', data.value.data)
    emits('close-modal')
}

const name = computed({
    get() {
        return props.product.name ?? ''
    },
    set(newValue) {
        Object.assign(props.product, { name: newValue })
    }
})

const price = computed({
    get() {
        return props.product.price ?? ''
    },
    set(newValue) {
        Object.assign(props.product, { price: newValue })
    }
})

const productImage = ref(props.product.photo_src)

</script>

<template>
    <div id="div-container-modal" class="modal">
        <div id="div-user-content" class="modal-content">

            <div class="div-product-card">
                <div class="close-modal">
                    <span @click="emits('close-modal')">✕</span>
                </div>

                <div class="carousel">
                    <div class="carousel-window">
                        <div class="carousel-track">
                            <div class="carousel-slide">
                                <img :src="productImage ?? defaultNoImage" />
                                <button class="delete-button"
                                    @click="() => {productImage = null; selectedFile = null}">
                                    ✕
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <input ref="fileInput" type="file" accept="image/*" style="display: none" @change="onFileChange" />

                <div class="div-btns">
                    <button @click="TriggerFileInput">Acrescentar Imagem</button>
                </div>

                <div class="div-product-info">
                    <div class="div-values">
                        <div class="div-left">
                            <span class="sub-title">Nome: </span>
                            <input id="product-name" class="h4-product-name" type="text" v-model="name">
                        </div>
                    </div>

                    <div class="div-values">
                        <div class="div-right">
                            <span class="sub-title">Preço:</span>
                            <input id="product-price" type="text" v-model="price">
                        </div>
                    </div>
                </div>

                <button v-if="!props.product?.id" class="btn-save-info" @click="CreateProduct">Criar</button>
                <button v-else class="btn-save-info" @click="SubmitUpdate">Gravar</button>
            </div>
        </div>
        <LoadingOverlay v-if="loading" />
    </div>
</template>

<style scoped>
input,
textarea {
    border-color: rgba(119, 136, 153, 0.26);
}

.modal-content {
    position: absolute;
    width: 500px;
    height: 600px;
}

.close-modal span {
    font-weight: bold;
    cursor: pointer;
}

.close-modal {
    display: flex;
    justify-content: flex-end;
    position: absolute;
    right: 3px;
    top: -3px;
    color: var(--default-app-color);
}

.close-modal i {
    font-size: 1.5em;
    cursor: pointer;
}

.div-product-card {
    width: 100%;
    height: 100%;
    background: white;
    padding: 1em;
    box-shadow: 0 5px 5px #e1e1e1;
}

.sub-title {
    font-size: 1.2em;
}

.div-img-carousel {
    width: 100%;
    text-align: center;
}

.div-product-info {
    justify-content: space-between;
    align-items: center;
}

.h4-product-name {
    font-weight: 600;
    margin: .5em 0 0px;
    font-size: 1.3em;
    display: flex;
    text-align: center;
    width: 100%;
}

.div-values {
    display: flex;
    justify-content: space-between;
    margin: 10px 0px;
    font-size: .85em;
}

.div-btns {
    display: flex;
    justify-content: space-between;
    margin: 10px 0 0;
}

.btn-status {
    width: 80px;
}

.div-left,
.div-right {
    display: flex;
    flex-direction: column;
}

.btn-save-info {
    background: var(--default-app-color);
    color: white;
    width: 100%;
    font-weight: 600;
    margin-top: 12px
}

.btn-save-info:hover {
    background: white;
    color: var(--default-app-color);
}


.carousel {
    position: relative;
    width: 100%;
    height: 45%;
    margin: auto;
    overflow: hidden;
}

.carousel-window {
    width: 100%;
    height: 100%;
    overflow: hidden;
    border: 2px solid var(--default-app-color);
}

.carousel-track {
    display: flex;
    height: 100%;
    transition: transform 0.4s ease;
}

.carousel-slide {
    min-width: 100%;
    box-sizing: border-box;
    position: relative;
}

.carousel-slide img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: contain;
}

.nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.4);
    color: white;
    border: none;
    font-size: 2rem;
    padding: 0.25em 0.6em;
    cursor: pointer;
    z-index: 10;
    user-select: none;
}

.nav.prev {
    left: 10px;
}

.nav.next {
    right: 10px;
}

.nav:disabled {
    opacity: 0.5;
    cursor: default;
}

.delete-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255, 0, 0, 0.7);
    color: white;
    border: none;
    padding: 5px 10px;
    font-weight: bold;
    cursor: pointer;
}
</style>