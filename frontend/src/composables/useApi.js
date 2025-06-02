import { ref } from 'vue'
import { callApi } from './callApi'

export default function useApi(resource) {
    const data = ref(null)
    const error = ref(null)
    const loading = ref(false)

    const fetchAll = async () => {
        loading.value = true
        try {
            const res = await callApi(resource)
            if (!res.success) throw new Error('Failed to fetch data')
            data.value = res
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }

    const fetchOne = async (id) => {
        loading.value = true
        try {
            const res = await callApi(`${resource}/${id}`)
            if (!res.success) throw new Error('Failed to fetch item')
            data.value = res
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }


    const create = async (payload) => {
        loading.value = true
        error.value = null
        data.value = null

        try {
            const res = await callApi(resource, {
                method: 'POST',
                body: payload,
            })

            const responseData = res
            data.value = responseData

            if (!res.success) {
                if (responseData.errors.length > 0) {
                    const allErrors = Object.values(responseData.errors)
                        .flat()
                        .join('\n')

                    error.value = allErrors
                } else {
                    const message = responseData.message || 'Erro inesperado'
                    error.value = message
                }
            }
        } catch (err) {
            error.value = 'Erro ao conectar com o servidor.'
            alert(error.value)
        } finally {
            loading.value = false
        }
    }

    const update = async (id, payload) => {
        loading.value = true
        error.value = null
        data.value = null

        try {
            const res = await callApi(`${resource}/${id}`, {
                method: 'POST',
                body: payload,
            })

            if (!res.success) {
                const err = res
                throw new Error(err.message || 'Failed to update item')
            }

            data.value = res

            return res
        } catch (err) {
            error.value = err
            return null
        } finally {
            loading.value = false
        }
    }

    const destroy = async (id) => {
        loading.value = true
        data.value = null
        error.value = null

        try {
            const res = await callApi(`${resource}/${id}`, { method: 'DELETE' })

            if (!res.success)
                throw new Error('Failed to delete')

            data.value = res
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }

    return {
        data,
        error,
        loading,
        fetchAll,
        fetchOne,
        create,
        update,
        destroy,
    }
}
