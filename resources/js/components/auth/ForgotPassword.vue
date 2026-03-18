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

        <!-- Back to login -->
        <div class="relative z-10 px-4 sm:px-6 pt-4 sm:pt-6">
            <router-link
                to="/login"
                class="inline-flex items-center gap-2 text-sm sm:text-base text-stone-600 hover:text-amber-600 transition-colors font-medium"
            >
                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                Back to login
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
                        Reset your password
                    </h1>
                    <p class="text-sm sm:text-lg text-stone-600">
                        Enter your email and we'll send you a reset link
                    </p>
                </div>

                <!-- Forgot password form -->
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl shadow-stone-200/50 border border-stone-100 p-5 sm:p-8">
                    <!-- Success state -->
                    <div
                        v-if="sent"
                        class="text-center py-3 sm:py-4"
                    >
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <svg
                                class="w-7 h-7 sm:w-8 sm:h-8 text-green-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </div>
                        <h2 class="text-lg sm:text-xl font-semibold text-stone-800 mb-2">
                            Check your email
                        </h2>
                        <p class="text-sm sm:text-base text-stone-600 mb-4 sm:mb-6">
                            If an account exists for <span class="font-semibold">{{ form.email }}</span>, we've sent a password reset link.
                        </p>
                        <button
                            type="button"
                            class="text-sm sm:text-base font-medium text-amber-600 hover:text-amber-700 transition-colors"
                            @click="resetForm"
                        >
                            Send another link
                        </button>
                    </div>

                    <!-- Form state -->
                    <form
                        v-else
                        class="space-y-4 sm:space-y-5"
                        @submit.prevent="handleSubmit"
                    >
                        <!-- Email -->
                        <div>
                            <label
                                for="email"
                                class="block text-sm sm:text-base font-semibold text-stone-700 mb-1.5 sm:mb-2"
                            >
                                Email address
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                placeholder="you@example.com"
                                class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3.5 text-sm sm:text-base rounded-xl border border-stone-200 bg-stone-50/50 text-stone-800 placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all"
                            >
                        </div>

                        <!-- Error message -->
                        <p
                            v-if="error"
                            class="text-sm sm:text-base text-red-500 font-medium"
                        >
                            {{ error }}
                        </p>

                        <!-- Submit button -->
                        <button
                            type="submit"
                            :disabled="loading"
                            class="w-full py-2.5 sm:py-3.5 text-base sm:text-lg bg-linear-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-300/30 hover:shadow-xl hover:shadow-amber-300/40 hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-60 disabled:pointer-events-none"
                        >
                            <span v-if="!loading">Send Reset Link</span>
                            <span
                                v-else
                                class="flex items-center justify-center gap-2"
                            >
                                <svg
                                    class="w-5 h-5 animate-spin"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    />
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                    />
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </form>

                    <div class="mt-4 sm:mt-6">
                        <!-- Back to login link -->
                        <p class="text-center text-sm sm:text-base text-stone-600">
                            Remember your password?
                            <router-link
                                to="/login"
                                class="font-semibold text-amber-600 hover:text-amber-700 transition-colors"
                            >
                                Log in
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

const form = reactive({
    email: '',
})

const error = ref('')
const loading = ref(false)
const sent = ref(false)

async function handleSubmit() {
    error.value = ''
    loading.value = true

    try {
        const response = await fetch('/api/forgot-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ email: form.email }),
        })

        const data = await response.json()

        if (!response.ok) {
            error.value = data.message || 'Something went wrong. Please try again.'
            return
        }

        sent.value = true
    } catch {
        error.value = 'Unable to connect. Please try again.'
    } finally {
        loading.value = false
    }
}

function resetForm() {
    sent.value = false
    error.value = ''
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
