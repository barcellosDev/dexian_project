import { ref } from 'vue'
import { callApi } from './callApi'

const user = ref(null)

export function useAuth() {
    async function checkAuth() {
        try {
            const res = await callApi('me')

            if (res?.success) {
                user.value = res.data
            } else {
                user.value = null
            }
        } catch (e) {
            console.log(e)
            user.value = null
        }
    }

    return {
        user,
        checkAuth,
    }
}
