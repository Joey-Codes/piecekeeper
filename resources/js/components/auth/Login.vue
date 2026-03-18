<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50 via-white to-white flex flex-col">
        <!-- Decorative piano keys along the top -->
        <div class="absolute top-0 left-0 right-0 flex justify-center opacity-[0.05] pointer-events-none">
            <div
                v-for="i in 30"
                :key="'key-'+i"
                class="shrink-0"
            >
                <div
                    v-if="isBlackKey(i)"
                    class="w-4 h-20 bg-stone-900 -mx-2 relative z-10 rounded-b"
                />
                <div
                    v-else
                    class="w-8 h-32 bg-stone-400 border-r border-stone-300"
                />
            </div>
        </div>

        <!-- Floating musical symbols -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <span
                v-for="(note, i) in floatingNotes"
                :key="'note-'+i"
                class="absolute text-amber-400/15 font-serif select-none"
                :style="note.style"
                :class="note.animClass"
            >{{ note.symbol }}</span>
        </div>

        <!-- Back to home -->
        <div class="relative z-10 px-4 sm:px-6 pt-4 sm:pt-6">
            <router-link
                to="/"
                class="inline-flex items-center gap-2 text-sm sm:text-base text-stone-600 hover:text-amber-600 transition-colors font-medium"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to home
            </router-link>
        </div>

        <!-- Main content -->
        <div class="relative z-10 flex-1 flex items-center justify-center px-4 sm:px-6 py-6 sm:py-12">
            <div class="w-full max-w-md">
                <!-- Logo & heading -->
                <div class="text-center mb-5 sm:mb-8">
                    <div class="flex items-center justify-center gap-3 mb-4 sm:mb-6">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center shadow-lg shadow-amber-200/50">
                            <span class="text-white text-xl sm:text-2xl">&#9835;</span>
                        </div>
                    </div>
                    <h1 class="font-serif text-2xl sm:text-4xl font-bold text-stone-800 mb-2 sm:mb-3">
                        Welcome back
                    </h1>
                    <p class="text-sm sm:text-lg text-stone-600">
                        Log in to continue practicing
                    </p>
                </div>

                <!-- Login form -->
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl shadow-stone-200/50 border border-stone-100 p-5 sm:p-8">
                    <form @submit.prevent="handleLogin" class="space-y-4 sm:space-y-5">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm sm:text-base font-semibold text-stone-700 mb-1.5 sm:mb-2">
                                Email address
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                placeholder="you@example.com"
                                class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3.5 text-sm sm:text-base rounded-xl border border-stone-200 bg-stone-50/50 text-stone-800 placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all"
                            />
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                <label for="password" class="block text-sm sm:text-base font-semibold text-stone-700">
                                    Password
                                </label>
                                <router-link
                                    to="/forgot-password"
                                    class="text-xs sm:text-sm font-medium text-amber-600 hover:text-amber-700 transition-colors"
                                >
                                    Forgot password?
                                </router-link>
                            </div>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                required
                                placeholder="Enter your password"
                                class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3.5 text-sm sm:text-base rounded-xl border border-stone-200 bg-stone-50/50 text-stone-800 placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all"
                            />
                        </div>

                        <!-- Error message -->
                        <p v-if="error" class="text-sm sm:text-base text-red-500 font-medium">
                            {{ error }}
                        </p>

                        <!-- Submit button -->
                        <button
                            type="submit"
                            :disabled="loading"
                            class="w-full py-2.5 sm:py-3.5 text-base sm:text-lg bg-linear-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-300/30 hover:shadow-xl hover:shadow-amber-300/40 hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-60 disabled:pointer-events-none"
                        >
                            <span v-if="!loading">Log In</span>
                            <span v-else class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                Logging in...
                            </span>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="flex items-center gap-4 my-4 sm:my-6">
                        <div class="flex-1 h-px bg-stone-200" />
                        <span class="text-xs sm:text-sm text-stone-500 font-medium uppercase tracking-wider">or</span>
                        <div class="flex-1 h-px bg-stone-200" />
                    </div>

                    <!-- Sign in with Google -->
                    <button
                        type="button"
                        class="w-full flex items-center justify-center gap-2.5 sm:gap-3 py-2.5 sm:py-3.5 text-sm sm:text-base font-semibold rounded-xl border border-stone-200 bg-white text-stone-700 hover:bg-stone-50 hover:border-stone-300 transition-all duration-200"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4" />
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18A10.96 10.96 0 0 0 1 12c0 1.77.42 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05" />
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                        </svg>
                        Sign in with Google
                    </button>

                    <div class="mt-4 sm:mt-6">
                        <!-- Sign up link -->
                        <p class="text-center text-sm sm:text-base text-stone-600">
                            Don't have an account?
                            <router-link
                                to="/signup"
                                class="font-semibold text-amber-600 hover:text-amber-700 transition-colors"
                            >
                                Sign up
                            </router-link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = reactive({
    email: '',
    password: '',
})

const error = ref('')
const loading = ref(false)

async function handleLogin() {
    error.value = ''
    loading.value = true

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(form),
        })

        const data = await response.json()

        if (!response.ok) {
            error.value = data.message || 'Invalid email or password.'
            return
        }

        router.push('/dashboard')
    } catch {
        error.value = 'Unable to connect. Please try again.'
    } finally {
        loading.value = false
    }
}

// Decorative helpers
function isBlackKey(i) {
    const mod = i % 7
    return mod === 2 || mod === 4 || mod === 0
}

const floatingNotes = [
    { symbol: '♫', style: 'top: 12%; left: 6%; font-size: 2.5rem; transform: rotate(-15deg);', animClass: 'animate-float-slow' },
    { symbol: '♪', style: 'top: 30%; right: 8%; font-size: 2rem; transform: rotate(10deg);', animClass: 'animate-float-medium' },
    { symbol: '𝄞', style: 'top: 55%; left: 4%; font-size: 3rem; transform: rotate(5deg);', animClass: 'animate-float-medium' },
    { symbol: '♯', style: 'top: 70%; right: 6%; font-size: 1.8rem; transform: rotate(-8deg);', animClass: 'animate-float-slow' },
    { symbol: '♭', style: 'top: 85%; left: 12%; font-size: 2rem; transform: rotate(12deg);', animClass: 'animate-float-medium' },
]
</script>

<style scoped>
@keyframes float-slow {
    0%, 100% { transform: translateY(0) rotate(var(--rotation, 0deg)); }
    50% { transform: translateY(-15px) rotate(var(--rotation, 0deg)); }
}

@keyframes float-medium {
    0%, 100% { transform: translateY(0) rotate(var(--rotation, 0deg)); }
    50% { transform: translateY(-10px) rotate(var(--rotation, 0deg)); }
}

.animate-float-slow {
    animation: float-slow 6s ease-in-out infinite;
}

.animate-float-medium {
    animation: float-medium 4s ease-in-out infinite;
}
</style>
