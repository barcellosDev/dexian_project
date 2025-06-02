export function callApi(uri, {
    method = 'GET',
    body = {}
} = {}) {
    return new Promise((resolve, reject) => {
        try {
            const headers = {
                'Accept': 'application/json'
            }

            const authToken = sessionStorage.getItem('auth_token')

            if (authToken) {
                headers['Authorization'] = `Bearer ${authToken}`
            }

            const options = {
                credentials: 'include',
                headers,
                method
            }

            if (method === "POST" || method === 'PUT') {
                options['body'] = body
            }

            fetch(`http://localhost:8000/api/${uri}`, options)
                .then(response => response.json())
                .then(body => resolve(body))
        } catch (e) {
            reject(e)
            throw e
        }
    })

}