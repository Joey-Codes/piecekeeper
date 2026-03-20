import { reactive } from 'vue'
import api from '@/api'

const state = reactive({
    user: null,
    checked: false,
})

export const auth = {
    get user() { return state.user },
    get isLoggedIn() { return !!state.user },
    get checked() { return state.checked },

    async fetchUser() {
        try {
            state.user = await api.get('/api/user')
        } catch {
            state.user = null
        }
        state.checked = true
    },

    setUser(user) {
        state.user = user
        state.checked = true
    },

    clear() {
        state.user = null
        state.checked = true
    },
}
