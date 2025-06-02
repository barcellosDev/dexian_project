<script setup>
import useApi from '@/composables/useApi'
import { useValidators } from '@/composables/useValidators'
import { onMounted } from 'vue'


const { update, create, error } = useApi('clients')

const emits = defineEmits(['saved-client'])
const props = defineProps({
    clientData: Object
})

async function SaveClient() {
    const rootElement = document.querySelector(`[data-id]`)

    const name = rootElement.querySelector(`#name .content-value`)
    const email = rootElement.querySelector(`#email .content-value`)
    const call_number = rootElement.querySelector(`#call_number .content-value`)
    const birth_date = rootElement.querySelector(`#birth_date .content-value`)
    const address = rootElement.querySelector(`#address .content-value`)
    const address_complement = rootElement.querySelector(`#address_complement .content-value`)
    const neighborhood = rootElement.querySelector(`#neighborhood .content-value`)
    const postal_address_code = rootElement.querySelector(`#postal_address_code .content-value`)

    const { validateBirthDate, validateEmail, setFieldToError } = useValidators()

    let hasError = false

    if (!validateBirthDate(birth_date.value)) {
        setFieldToError(birth_date)
        hasError = true
    }

    if (!validateEmail(email.value)) {
        setFieldToError(email)
        hasError = true
    }

    if (hasError) {
        return
    }

    const clientData = JSON.stringify({
        name: name.value,
        email: email.value,
        call_number: call_number.value,
        birth_date: birth_date.value,
        address: address.value,
        address_complement: address_complement.value,
        neighborhood: neighborhood.value,
        postal_address_code: postal_address_code.value
    })

    if (props.clientData.id) {
        const payload = new FormData()
        payload.append('_method', 'PUT')
        payload.append('client_data', clientData)

        await update(props.clientData.id, payload)
    } else {
        await create(clientData)
    }

    if (error.value !== null) {
        alert(error.value)
        return
    }

    emits('saved-client')
}

onMounted(() => {
    const { formatPhoneNumber } = useValidators()
    const rootElement = document.querySelector(`[data-id]`)
    const call_number = rootElement.querySelector(`#call_number .content-value`)

    call_number.oninput = function () {
        this.value = formatPhoneNumber(this.value)
    }
})


</script>

<template>
    <div class="div-page">
        <div class="container">
            <div class="client" :data-id="props.clientData.id ?? ''">
                <h1>{{ props.clientData.name }}</h1>

                <div class="data-group" id="name">
                    <div class="content">
                        <div class="content">
                            <label for="">Nome</label>
                            <input class="content-value" type="text" :value="props.clientData.name">
                        </div>
                    </div>
                </div>

                <div class="data-group" id="email">
                    <div class="content">
                        <label for="">Email</label>
                        <input class="content-value" type="text" :value="props.clientData.email">
                    </div>
                </div>

                <div class="data-group" id="call_number">
                    <div class="content">
                        <label for="">Telefone</label>
                        <input class="content-value" placeholder="(DD) XXXXXX-XXX" type="text"
                            :value="props.clientData.call_number">
                    </div>
                </div>

                <div class="data-group" id="birth_date">
                    <div class="content">
                        <label for="">Data de nascimento</label>
                        <input class="content-value" type="date" :value="props.clientData.birth_date">
                    </div>
                </div>

                <div class="data-group" id="address">
                    <div class="content">
                        <label for="">Endereço</label>
                        <input class="content-value" type="text" :value="props.clientData.address">
                    </div>
                </div>

                <div class="data-group" id="address_complement">
                    <div class="content">
                        <label for="">Complemento</label>
                        <input class="content-value" type="text" :value="props.clientData.address_complement">
                    </div>
                </div>

                <div class="data-group" id="neighborhood">
                    <div class="content">
                        <label for="">Bairro</label>
                        <input class="content-value" type="text" :value="props.clientData.neighborhood">
                    </div>
                </div>

                <div class="data-group" id="postal_address_code">
                    <div class="content">
                        <label for="">CEP</label>
                        <input class="content-value" type="text" :value="props.clientData.postal_address_code">
                    </div>
                </div>

            </div>
            <div id="actions">
                <button class="confirm-action" @click="SaveClient">Confirmar</button>
                <button class="cancel-action" @click="emits('saved-client', true)">Cancel</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.container {
    max-width: 50%;
    margin: 30px auto;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.client {
    border-bottom: 1px solid #ddd;
    padding: 15px 0;
    text-align: center;
    width: min-content;
    margin: 0 auto;
}

.client:last-child {
    border-bottom: none;
}

.data-group {
    text-align: center;
}

.data-group>label {
    font-size: 1.3em;
}

#actions {
    display: flex;
    gap: 10px;
    width: min-content;
}

.content {
    display: flex;
    flex-direction: column;
}

.data-group:not(:first-child) {
    margin-top: 20px;
}
</style>