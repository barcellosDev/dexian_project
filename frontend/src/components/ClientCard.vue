<script setup>

const props = defineProps({
    clientData: Object
})

</script>

<template>
    <div :client-id="props.clientData.id">
        <h1>{{ props.clientData.name }}</h1>
        <h2>{{ props.clientData.email }}</h2>
    </div>
</template>

<style scoped>

</style>