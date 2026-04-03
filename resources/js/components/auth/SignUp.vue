<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50 via-white to-white flex flex-col">
        <!-- Decorative piano keys along the top -->
        <div class="absolute top-0 left-0 right-0 flex justify-center opacity-[0.05] pointer-events-none">
            <div
                v-for="i in 29"
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
                        Create your account
                    </h1>
                    <p class="text-sm sm:text-lg text-stone-800">
                        Start organizing your piano journey today
                    </p>
                </div>

                <!-- Sign up form -->
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl shadow-stone-200/50 border border-amber-300 p-5 sm:p-8">
                    <form
                        class="space-y-4 sm:space-y-5"
                        @submit.prevent="handleSignUp"
                    >
                        <!-- Name -->
                        <div>
                            <label
                                for="name"
                                class="block text-sm sm:text-base font-semibold text-stone-700 mb-1.5 sm:mb-2"
                            >
                                Display name
                            </label>
                            <p class="text-xs sm:text-sm text-amber-600 font-semibold mb-1.5 sm:mb-2">
                                Your name or a username
                            </p>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="e.g. Clara, pianolover42, etc."
                                class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3.5 text-sm sm:text-base rounded-xl border border-stone-300 bg-stone-50/50 text-stone-800 placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all"
                            >
                        </div>

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
                                class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3.5 text-sm sm:text-base rounded-xl border border-stone-300 bg-stone-50/50 text-stone-800 placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all"
                            >
                        </div>

                        <!-- Password -->
                        <div>
                            <label
                                for="password"
                                class="block text-sm sm:text-base font-semibold text-stone-700 mb-1.5 sm:mb-2"
                            >
                                Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    placeholder="At least 8 characters"
                                    class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3.5 pr-11 text-sm sm:text-base rounded-xl border border-stone-300 bg-stone-50/50 text-stone-800 placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all"
                                >
                                <button
                                    type="button"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 transition-colors"
                                    tabindex="-1"
                                    @click="showPassword = !showPassword"
                                >
                                    <svg
                                        v-if="!showPassword"
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                    <svg
                                        v-else
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label
                                for="password_confirmation"
                                class="block text-sm sm:text-base font-semibold text-stone-700 mb-1.5 sm:mb-2"
                            >
                                Confirm password
                            </label>
                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    required
                                    placeholder="Re-enter your password"
                                    class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3.5 pr-11 text-sm sm:text-base rounded-xl border border-stone-300 bg-stone-50/50 text-stone-800 placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all"
                                >
                                <button
                                    type="button"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 transition-colors"
                                    tabindex="-1"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                >
                                    <svg
                                        v-if="!showConfirmPassword"
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                    <svg
                                        v-else
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                                        />
                                    </svg>
                                </button>
                            </div>
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
                            <span v-if="!loading">Create Account</span>
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
                                Creating account...
                            </span>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="flex items-center gap-4 my-4 sm:my-6">
                        <div class="flex-1 h-px bg-stone-400" />
                        <span class="text-xs sm:text-sm text-stone-500 font-medium uppercase tracking-wider">or</span>
                        <div class="flex-1 h-px bg-stone-400" />
                    </div>

                    <!-- Continue with Google -->
                    <a
                        href="/auth/google"
                        class="w-full flex items-center justify-center gap-2.5 sm:gap-3 py-2.5 sm:py-3.5 text-sm sm:text-base font-semibold rounded-xl border border-stone-300 bg-white text-stone-700 hover:bg-stone-50 hover:border-stone-500 transition-all duration-200 no-underline"
                    >
                        <svg
                            class="w-5 h-5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"
                                fill="#4285F4"
                            />
                            <path
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                fill="#34A853"
                            />
                            <path
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18A10.96 10.96 0 0 0 1 12c0 1.77.42 3.45 1.18 4.93l3.66-2.84z"
                                fill="#FBBC05"
                            />
                            <path
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                fill="#EA4335"
                            />
                        </svg>
                        Continue with Google
                    </a>

                    <div class="mt-4 sm:mt-6">
                        <!-- Log in link -->
                        <p class="text-center text-sm sm:text-base text-stone-600">
                            Already have an account?
                            <router-link
                                to="/login"
                                class="font-semibold text-amber-600 hover:text-amber-700 transition-colors"
                            >
                                Log in
                            </router-link>
                        </p>
                    </div>
                </div>

                <!-- Footer note -->
                <p class="text-center text-xs sm:text-sm text-stone-700 mt-4 sm:mt-6">
                    By creating an account, you agree to our
                    <router-link
                        to="/terms"
                        class="text-amber-600 hover:text-amber-700 transition-colors"
                    >
                        Terms of Service
                    </router-link>
                    and
                    <router-link
                        to="/privacy"
                        class="text-amber-600 hover:text-amber-700 transition-colors"
                    >
                        Privacy Policy
                    </router-link>.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api'
import { auth } from '@/auth'

const router = useRouter()

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const showPassword = ref(false)
const showConfirmPassword = ref(false)
const error = ref('')
const loading = ref(false)

async function handleSignUp() {
    error.value = ''

    if (form.password.length < 8) {
        error.value = 'Password must be at least 8 characters.'
        return
    }

    if (form.password !== form.password_confirmation) {
        error.value = 'Passwords do not match.'
        return
    }

    loading.value = true

    try {
        const user = await api.post('/api/register', {
            ...form,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        })
        auth.setUser(user)
        router.push('/dashboard')
    } catch (e) {
        error.value = e.data?.message || 'Something went wrong. Please try again.'
    } finally {
        loading.value = false
    }
}

// Decorative helpers
function isBlackKey(i) {
    const mod = (i - 1) % 12
    return mod === 1 || mod === 3 || mod === 6 || mod === 8 || mod === 10
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
