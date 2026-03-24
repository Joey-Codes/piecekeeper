<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50/80 via-white to-stone-50 relative overflow-hidden">
        <FloatingNotes />

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
            <header class="mb-6 sm:mb-10 text-center">
                <h1 class="text-2xl sm:text-4xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    <span class="bg-linear-to-r bg-clip-text">Settings</span>
                </h1>
                <p class="text-sm sm:text-base font-semibold text-stone-500 mt-1.5 sm:mt-2">
                    Customize your practice experience
                </p>
                <div class="mt-2.5 sm:mt-3 mx-auto w-12 sm:w-16 h-1 rounded-full bg-linear-to-r from-amber-400 to-orange-400 opacity-60" />
            </header>

            <div class="flex flex-col sm:flex-row gap-4 sm:gap-0 rounded-2xl bg-white border border-stone-200/70 shadow-sm overflow-hidden">
                <!-- Sidebar Navigation -->
                <nav class="sm:w-52 shrink-0 sm:border-r sm:border-amber-600/30 bg-amber-500 px-2 py-3 sm:py-5">
                    <div class="flex sm:flex-col gap-1 sm:gap-0.5 overflow-x-auto sm:overflow-x-visible pb-2 sm:pb-0">
                        <button
                            v-for="tab in tabs"
                            :key="tab.id"
                            class="flex items-center gap-2.5 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl text-left transition-all duration-200 whitespace-nowrap shrink-0 sm:shrink sm:w-full"
                            :class="activeTab === tab.id
                                ? 'bg-white/90 text-stone-800 shadow-sm'
                                : 'text-white hover:bg-white/20 hover:text-white'"
                            @click="activeTab = tab.id"
                        >
                            <div
                                class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-xs"
                                :class="tab.iconClass"
                            >
                                <span class="text-white">{{ tab.icon }}</span>
                            </div>
                            <span class="text-sm font-semibold">{{ tab.label }}</span>
                        </button>
                    </div>
                </nav>

                <!-- Content Panel -->
                <div class="flex-1 min-w-0 p-4 sm:p-8">
                    <!-- Practice Schedule -->
                    <div v-if="activeTab === 'schedule'">
                        <div class="flex items-center gap-2.5 sm:gap-3 mb-5 sm:mb-7">
                            <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-sm shrink-0">
                                <span class="text-white text-xs sm:text-sm">&#128197;</span>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-xl font-serif font-bold uppercase tracking-wide text-stone-800">
                                    Practice Schedule
                                </h2>
                                <p class="text-sm sm:text-md font-semibold text-stone-600">
                                    How often and how much you want to practice
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4 sm:space-y-6">
                            <!-- Pieces per day -->
                            <div>
                                <label class="block text-sm sm:text-md font-semibold text-stone-700 mb-2">Pieces per session</label>
                                <div class="flex items-center gap-2.5 sm:gap-3">
                                    <button
                                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg border border-stone-300 bg-white text-stone-500 hover:bg-stone-50 hover:text-stone-700 transition-colors flex items-center justify-center text-lg"
                                        @click="piecesPerDay = Math.max(1, piecesPerDay - 1)"
                                    >
                                        &minus;
                                    </button>
                                    <span class="text-xl sm:text-2xl font-bold text-stone-800 w-8 sm:w-10 text-center">{{ piecesPerDay }}</span>
                                    <button
                                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg border border-stone-300 bg-white text-stone-500 hover:bg-stone-50 hover:text-stone-700 transition-colors flex items-center justify-center text-lg"
                                        @click="piecesPerDay = Math.min(20, piecesPerDay + 1)"
                                    >
                                        +
                                    </button>
                                    <span class="text-sm sm:text-md font-semibold text-stone-600 ml-1 sm:ml-2">{{ piecesPerDay === 1 ? 'piece' : 'pieces' }}</span>
                                </div>
                            </div>

                            <!-- Practice frequency -->
                            <div>
                                <label class="block text-sm sm:text-md font-semibold text-stone-700 mb-2">Practice frequency</label>
                                <div class="flex flex-wrap gap-1.5 sm:gap-2">
                                    <button
                                        v-for="option in frequencyOptions"
                                        :key="option.value"
                                        class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border transition-all duration-200"
                                        :class="frequency === option.value
                                            ? 'border-amber-400 bg-amber-500 text-white shadow-sm'
                                            : 'border-stone-200 bg-white text-stone-700 hover:bg-stone-50 hover:text-stone-700'"
                                        @click="frequency = option.value"
                                    >
                                        {{ option.label }}
                                    </button>
                                </div>
                            </div>

                            <!-- Summary with calendar arrow -->
                            <div class="relative mt-2 px-3 sm:px-4 py-2.5 sm:py-3 bg-white/70 rounded-xl border border-amber-200/50 shadow-sm flex items-center justify-between gap-2">
                                <p class="text-sm sm:text-md text-stone-600">
                                    <span class="font-bold uppercase text-amber-600">Your schedule:</span>
                                    {{ piecesPerDay }} {{ piecesPerDay === 1 ? 'piece' : 'pieces' }} {{ frequencyLabel }}
                                </p>
                                <button
                                    class="w-8 h-8 rounded-lg border border-amber-300/60 bg-amber-50 text-amber-500 hover:bg-amber-100 hover:text-amber-700 transition-colors flex items-center justify-center shrink-0"
                                    title="View calendar"
                                    @click="showCalendar = true"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 5l7 7-7 7"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences -->
                    <div v-else-if="activeTab === 'preferences'">
                        <div class="flex items-center gap-2.5 sm:gap-3 mb-5 sm:mb-7">
                            <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-linear-to-br from-violet-300 to-purple-400 flex items-center justify-center shadow-sm shrink-0">
                                <span class="text-white text-xs sm:text-sm">&#9881;</span>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-xl font-serif font-bold uppercase tracking-wide text-stone-800">
                                    Preferences
                                </h2>
                                <p class="text-sm sm:text-md font-semibold text-stone-600">
                                    Personalize your experience
                                </p>
                            </div>
                        </div>

                        <div class="px-4 py-8 text-center rounded-xl border border-dashed border-stone-300/60">
                            <p class="text-sm font-semibold text-stone-400">
                                Coming soon
                            </p>
                        </div>
                    </div>

                    <!-- Account -->
                    <div v-else-if="activeTab === 'account'">
                        <div class="flex items-center gap-2.5 sm:gap-3 mb-5 sm:mb-7">
                            <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-linear-to-br from-sky-400 to-blue-500 flex items-center justify-center shadow-sm shrink-0">
                                <span class="text-white text-xs sm:text-sm">&#128100;</span>
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
                                    class="relative mt-2 px-3 sm:px-4 py-2.5 sm:py-3 bg-white/70 rounded-xl border border-amber-200/50 shadow-sm flex items-center justify-between gap-2"
                                >
                                    <p class="text-sm sm:text-md font-semibold text-stone-600">
                                        <span class="font-bold uppercase text-amber-600">Unsaved changes</span>
                                    </p>
                                    <button
                                        class="px-4 sm:px-5 py-1.5 sm:py-2 text-sm sm:text-base font-semibold rounded-lg border border-amber-400 bg-amber-500 text-white hover:bg-amber-600 transition-colors duration-200 shadow-sm disabled:opacity-50 shrink-0"
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
                                    <div>
                                        <input
                                            id="current-password"
                                            v-model="currentPassword"
                                            type="password"
                                            class="w-full sm:w-80 px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base rounded-lg border border-stone-300 bg-white text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-colors"
                                            placeholder="Current password"
                                        >
                                    </div>
                                    <div>
                                        <input
                                            id="new-password"
                                            v-model="newPassword"
                                            type="password"
                                            class="w-full sm:w-80 px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base rounded-lg border border-stone-300 bg-white text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-colors"
                                            placeholder="New password"
                                        >
                                    </div>
                                    <div>
                                        <input
                                            id="confirm-password"
                                            v-model="confirmPassword"
                                            type="password"
                                            class="w-full sm:w-80 px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base rounded-lg border border-stone-300 bg-white text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-colors"
                                            placeholder="Confirm new password"
                                        >
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
                </div>
            </div>
        </div>

        <!-- Schedule Calendar Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div
                    v-if="showCalendar"
                    class="fixed inset-0 z-50 flex items-center justify-center p-0 sm:p-4"
                    @mousedown.self="showCalendar = false"
                >
                    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" />

                    <div class="relative bg-white rounded-2xl shadow-xl calendar-modal-panel sm:w-[75vw] max-h-[92vh] overflow-y-auto p-4 sm:p-10">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="flex items-center gap-2.5 sm:gap-3">
                                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-sm shrink-0">
                                    <span class="text-white text-xs sm:text-sm">&#128197;</span>
                                </div>
                                <h3 class="text-lg sm:text-2xl font-serif font-bold uppercase tracking-wide text-stone-800">
                                    Practice Schedule
                                </h3>
                            </div>
                            <button
                                class="w-8 h-8 rounded-lg border border-stone-200 bg-white text-stone-400 hover:bg-stone-50 hover:text-stone-600 transition-colors flex items-center justify-center"
                                title="Close"
                                @click="showCalendar = false"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>

                        <Calendar
                            :attributes="calendarAttributes"
                            class="schedule-calendar"
                            expanded
                            borderless
                        >
                            <template #day-content="{ day }">
                                <div
                                    class="w-full h-full flex flex-col p-1 sm:p-2 rounded-lg border transition-colors duration-150"
                                    :class="getDayClass(day)"
                                >
                                    <span
                                        class="text-xs sm:text-sm font-bold mb-0.5 sm:mb-1"
                                        :class="isToday(day) ? 'text-amber-600' : 'text-stone-500'"
                                    >{{ day.day }}</span>
                                    <div
                                        v-for="piece in getPiecesForDay(day)"
                                        :key="piece.id"
                                        class="text-[10px] sm:text-sm leading-snug text-stone-600 truncate"
                                        :title="piece.title + ' - ' + piece.composer"
                                    >
                                        {{ piece.title }}
                                    </div>
                                </div>
                            </template>
                        </Calendar>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import FloatingNotes from '../ui/FloatingNotes.vue'
import { Calendar } from 'v-calendar'
import 'v-calendar/style.css'
import api from '../../api.js'
import { auth } from '@/auth'

const router = useRouter()

const activeTab = ref('schedule')

const tabs = [
    { id: 'schedule', label: 'Practice Schedule', icon: '\u{1F4C5}', iconClass: 'bg-linear-to-br from-amber-400 to-orange-500' },
    { id: 'preferences', label: 'Preferences', icon: '\u2699', iconClass: 'bg-linear-to-br from-violet-300 to-purple-400' },
    { id: 'account', label: 'Account', icon: '\u{1F464}', iconClass: 'bg-linear-to-br from-sky-400 to-blue-500' },
]

const showCalendar = ref(false)
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

const piecesPerDay = ref(3)
const frequency = ref('every_day')

const frequencyOptions = [
    { value: 'every_day', label: 'Every day' },
    { value: 'every_2_days', label: 'Every 2 days' },
    { value: 'every_3_days', label: 'Every 3 days' },
    { value: 'every_4_days', label: 'Every 4 days' },
    { value: 'every_5_days', label: 'Every 5 days' },
    { value: 'every_6_days', label: 'Every 6 days' },
    { value: 'weekly', label: 'Weekly' },
    { value: 'biweekly', label: 'Biweekly' },
    { value: 'monthly', label: 'Monthly' },
    { value: 'weekdays', label: 'Weekdays only' },
    { value: 'weekends', label: 'Weekends only' },
]

const frequencyLabel = computed(() => {
    const option = frequencyOptions.find(o => o.value === frequency.value)
    const label = option?.label.toLowerCase() || ''
    if (label === 'every day') return 'every day'
    return label
})

// Calendar logic
const calendarAttributes = ref([])

const allPieces = [
    { id: 1, title: 'Clair de Lune', composer: 'Debussy' },
    { id: 2, title: 'Nocturne Op. 9 No. 2', composer: 'Chopin' },
    { id: 3, title: 'Gymnopédie No. 1', composer: 'Satie' },
    { id: 4, title: 'Prelude in C Major', composer: 'Bach' },
    { id: 5, title: 'Moonlight Sonata, Mvt. 1', composer: 'Beethoven' },
]

const now = new Date()
const calYear = now.getFullYear()
const calMonth = now.getMonth()

function isToday(day) {
    const d = new Date(day.id)
    return d.getDate() === now.getDate() && d.getMonth() === now.getMonth() && d.getFullYear() === now.getFullYear()
}

function shouldPractice(date) {
    const dayOfWeek = date.getDay()
    switch (frequency.value) {
        case 'every_day':
            return true
        case 'weekdays':
            return dayOfWeek >= 1 && dayOfWeek <= 5
        case 'weekends':
            return dayOfWeek === 0 || dayOfWeek === 6
        case 'weekly':
            return dayOfWeek === 1
        case 'biweekly': {
            const firstDay = new Date(calYear, calMonth, 1)
            const diff = Math.floor((date - firstDay) / (1000 * 60 * 60 * 24))
            return dayOfWeek === 1 && Math.floor(diff / 7) % 2 === 0
        }
        case 'monthly':
            return date.getDate() === 1
        default: {
            const match = frequency.value.match(/every_(\d+)_days/)
            if (match) {
                const interval = parseInt(match[1])
                const epoch = new Date(calYear, calMonth, 1)
                const diff = Math.floor((date - epoch) / (1000 * 60 * 60 * 24))
                return diff % interval === 0
            }
            return false
        }
    }
}

function countPracticeDaysBefore(day) {
    let count = 0
    for (let d = 1; d < day; d++) {
        if (shouldPractice(new Date(calYear, calMonth, d))) count++
    }
    return count
}

function getPiecesForDay(day) {
    const date = new Date(day.id)
    if (!shouldPractice(date)) return []
    const practiceDayIndex = countPracticeDaysBefore(date.getDate())
    const count = Math.min(piecesPerDay.value, allPieces.length)
    const startIdx = (practiceDayIndex * count) % allPieces.length
    const result = []
    for (let i = 0; i < count; i++) {
        result.push(allPieces[(startIdx + i) % allPieces.length])
    }
    return result
}

function getDayClass(day) {
    if (isToday(day)) return 'border-amber-400 bg-amber-50/60'
    const date = new Date(day.id)
    if (shouldPractice(date)) return 'border-stone-200 bg-white'
    return 'border-stone-100 bg-stone-50/50'
}
</script>

<style scoped>
:deep(.schedule-calendar .vc-header) {
    padding: 0.5rem 0 1.5rem;
}

:deep(.schedule-calendar .vc-title) {
    font-family: 'Comfortaa', ui-sans-serif, system-ui, sans-serif;
    font-size: 1.4rem;
    font-weight: 800;
    color: rgb(28 25 23);
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

:deep(.schedule-calendar .vc-arrow) {
    color: rgb(120 113 108);
    border-radius: 0.5rem;
}

:deep(.schedule-calendar .vc-arrow:hover) {
    background-color: rgb(254 243 199);
    color: rgb(180 83 9);
}

:deep(.schedule-calendar .vc-weekday) {
    font-weight: 700;
    font-size: 0.7rem;
    letter-spacing: 0.1em;
    color: rgb(120 113 108);
    text-transform: uppercase;
    padding-bottom: 0.5rem;
}

:deep(.schedule-calendar .vc-day) {
    padding: 0.1rem;
    min-height: 3.5rem;
}

@media (max-width: 639px) {
    .calendar-modal-panel {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(90deg);
        width: 100vh;
        height: 100vw;
        max-height: none;
        border-radius: 0;
    }
}

@media (min-width: 640px) {
    :deep(.schedule-calendar .vc-day) {
        padding: 0.2rem;
        min-height: 6rem;
    }
}

:deep(.schedule-calendar .vc-day-content) {
    width: 100%;
    height: 100%;
}

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}
.modal-enter-active .relative,
.modal-leave-active .relative {
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
.modal-enter-from .relative {
    transform: scale(0.95);
}
.modal-leave-to .relative {
    transform: scale(0.95);
}

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
