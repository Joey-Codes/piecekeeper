<template>
    <div>
        <div class="flex items-center gap-2.5 sm:gap-3 mb-5 sm:mb-7">
            <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-linear-to-br from-sky-400 to-blue-500 flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
            </div>
            <div>
                <h2 class="text-base sm:text-xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    Account
                </h2>
                <p class="text-sm sm:text-md font-semibold text-stone-600">
                    Manage your account
                </p>
            </div>
        </div>

        <div class="space-y-4 sm:space-y-6">
            <!-- Name -->
            <div>
                <label class="block text-sm sm:text-md font-semibold text-stone-700 mb-2">Name</label>
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <input
                        id="account-name"
                        v-model="accountName"
                        type="text"
                        class="w-full sm:w-80 px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base rounded-lg border border-stone-300 bg-white text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-colors"
                        placeholder="Your name"
                    >
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm sm:text-md font-semibold text-stone-700 mb-2">Email</label>
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <input
                        id="account-email"
                        v-model="accountEmail"
                        type="email"
                        class="w-full sm:w-80 px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base rounded-lg border border-stone-300 bg-white text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-colors"
                        placeholder="your@email.com"
                    >
                </div>
                <!-- Email verification status -->
                <div class="flex items-center gap-2 mt-2">
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm font-semibold rounded-full"
                        :class="emailVerified
                            ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                            : 'bg-amber-50 text-amber-700 border border-amber-200'"
                    >
                        <span
                            v-if="emailVerified"
                            class="text-emerald-500"
                        >&#10003;</span>
                        <span
                            v-else
                            class="text-amber-500"
                        >&#9888;</span>
                        {{ emailVerified ? 'Email verified' : 'Email not verified' }}
                    </span>
                    <button
                        v-if="!emailVerified"
                        class="px-3 sm:px-4 py-1 sm:py-1.5 text-xs sm:text-sm font-semibold rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition-colors duration-200 shadow-sm disabled:opacity-50"
                        :disabled="verificationSent"
                        @click="sendVerification"
                    >
                        {{ verificationSent ? 'Verification sent!' : 'Verify email' }}
                    </button>
                </div>
            </div>

            <!-- Account messages -->
            <p
                v-if="!accountValid && accountDirty"
                class="text-sm text-red-500 font-medium"
            >
                Name and/or email cannot be blank.
            </p>
            <p
                v-if="accountError"
                class="text-sm text-red-500 font-medium"
            >
                {{ accountError }}
            </p>
            <p
                v-if="accountSuccess"
                class="text-sm text-emerald-600 font-medium"
            >
                {{ accountSuccess }}
            </p>

            <!-- Save changes summary bar -->
            <Transition name="fade">
                <div
                    v-if="accountDirty"
                    class="relative mt-2 px-3 sm:px-4 py-2.5 sm:py-3 bg-white/70 rounded-xl border border-emerald-200/50 shadow-sm flex items-center justify-between gap-2"
                >
                    <p class="text-sm sm:text-md font-semibold text-stone-600">
                        <span class="font-bold uppercase text-emerald-600">Unsaved changes</span>
                    </p>
                    <button
                        class="px-4 sm:px-5 py-1.5 sm:py-2 text-sm sm:text-base font-semibold rounded-lg border border-emerald-400 bg-emerald-500 text-white hover:bg-emerald-600 transition-colors duration-200 shadow-sm disabled:opacity-50 shrink-0"
                        :disabled="!accountValid || savingAccount"
                        @click="saveAccount"
                    >
                        {{ savingAccount ? 'Saving...' : 'Save changes' }}
                    </button>
                </div>
            </Transition>

            <!-- Change Password -->
            <div class="border-t border-stone-200/60 pt-4 sm:pt-5">
                <label class="block text-sm sm:text-md font-semibold text-stone-700 mb-2">Change password</label>
                <div class="space-y-3">
                    <div class="relative w-full sm:w-80">
                        <input
                            id="current-password"
                            v-model="currentPassword"
                            :type="showCurrentPassword ? 'text' : 'password'"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-10 text-sm sm:text-base rounded-lg border border-stone-300 bg-white text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-colors"
                            placeholder="Current password"
                        >
                        <button
                            type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 transition-colors"
                            tabindex="-1"
                            @click="showCurrentPassword = !showCurrentPassword"
                        >
                            <svg
                                v-if="!showCurrentPassword"
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
                    <div class="relative w-full sm:w-80">
                        <input
                            id="new-password"
                            v-model="newPassword"
                            :type="showNewPassword ? 'text' : 'password'"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-10 text-sm sm:text-base rounded-lg border border-stone-300 bg-white text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-colors"
                            placeholder="New password"
                        >
                        <button
                            type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 transition-colors"
                            tabindex="-1"
                            @click="showNewPassword = !showNewPassword"
                        >
                            <svg
                                v-if="!showNewPassword"
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
                    <div class="relative w-full sm:w-80">
                        <input
                            id="confirm-password"
                            v-model="confirmPassword"
                            :type="showConfirmPassword ? 'text' : 'password'"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-10 text-sm sm:text-base rounded-lg border border-stone-300 bg-white text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-colors"
                            placeholder="Confirm new password"
                        >
                        <button
                            type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 transition-colors"
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
                    <div>
                        <p
                            v-if="passwordMismatch"
                            class="text-sm text-red-500 font-medium mt-1"
                        >
                            Passwords do not match.
                        </p>
                    </div>
                    <Transition name="fade">
                        <button
                            v-if="passwordDirty"
                            class="px-4 sm:px-5 py-2 sm:py-2.5 text-sm sm:text-base font-semibold rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition-colors duration-200 shadow-sm disabled:opacity-50"
                            :disabled="!passwordValid || savingPassword"
                            @click="changePassword"
                        >
                            {{ savingPassword ? 'Updating...' : 'Update password' }}
                        </button>
                    </Transition>
                    <p
                        v-if="passwordError"
                        class="text-sm text-red-500 font-medium"
                    >
                        {{ passwordError }}
                    </p>
                    <p
                        v-if="passwordSuccess"
                        class="text-sm text-emerald-600 font-medium"
                    >
                        {{ passwordSuccess }}
                    </p>
                </div>
            </div>

            <!-- Log out -->
            <div class="border-t border-stone-200/60 pt-4 sm:pt-5">
                <button
                    class="px-4 sm:px-5 py-2 sm:py-2.5 text-sm sm:text-base font-semibold rounded-lg border border-red-300 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors duration-200 disabled:opacity-50"
                    :disabled="loggingOut"
                    @click="logout"
                >
                    {{ loggingOut ? 'Logging out...' : 'Log out' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api'
import { auth } from '@/auth'

const router = useRouter()
const loggingOut = ref(false)

// Account fields
const accountName = ref(auth.user?.name || '')
const accountEmail = ref(auth.user?.email || '')
const savingAccount = ref(false)
const accountError = ref('')
const accountSuccess = ref('')
const verificationSent = ref(false)

const emailVerified = computed(() => !!auth.user?.email_verified_at)

const accountDirty = computed(() => {
    return accountName.value !== (auth.user?.name || '')
        || accountEmail.value !== (auth.user?.email || '')
})

const accountValid = computed(() => {
    return accountName.value.trim() !== '' && accountEmail.value.trim() !== ''
})

watch(accountDirty, (dirty) => {
    if (dirty) {
        accountError.value = ''
        accountSuccess.value = ''
    }
})

async function saveAccount() {
    savingAccount.value = true
    accountError.value = ''
    accountSuccess.value = ''
    try {
        const data = {}
        if (accountName.value !== (auth.user?.name || '')) data.name = accountName.value
        if (accountEmail.value !== (auth.user?.email || '')) data.email = accountEmail.value

        const res = await api.put('/api/user', data)
        auth.setUser(res.data)
        accountSuccess.value = 'Account updated.'
    } catch (e) {
        accountError.value = extractError(e)
    } finally {
        savingAccount.value = false
    }
}

function sendVerification() {
    // TODO: implement API call
}

// Password fields
const currentPassword = ref('')
const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)
const newPassword = ref('')
const confirmPassword = ref('')
const savingPassword = ref(false)
const passwordError = ref('')
const passwordSuccess = ref('')

const passwordDirty = computed(() => {
    return currentPassword.value !== '' || newPassword.value !== '' || confirmPassword.value !== ''
})

const passwordMismatch = computed(() => {
    return confirmPassword.value !== '' && newPassword.value !== confirmPassword.value
})

const passwordValid = computed(() => {
    return currentPassword.value !== ''
        && newPassword.value !== ''
        && newPassword.value === confirmPassword.value
})

async function changePassword() {
    savingPassword.value = true
    passwordError.value = ''
    passwordSuccess.value = ''
    try {
        await api.put('/api/user', {
            current_password: currentPassword.value,
            new_password: newPassword.value,
            new_password_confirmation: confirmPassword.value,
        })
        currentPassword.value = ''
        newPassword.value = ''
        confirmPassword.value = ''
        passwordSuccess.value = 'Password updated.'
    } catch (e) {
        passwordError.value = extractError(e)
    } finally {
        savingPassword.value = false
    }
}

function extractError(e) {
    if (e.data?.errors) {
        return Object.values(e.data.errors).flat()[0]
    }
    return e.data?.message || 'Something went wrong.'
}

async function logout() {
    loggingOut.value = true
    try {
        await api.post('/api/logout')
        auth.clear()
        router.push({ name: 'login' })
    } catch {
        loggingOut.value = false
    }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
