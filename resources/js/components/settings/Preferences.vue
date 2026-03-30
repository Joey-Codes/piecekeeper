<template>
    <div>
        <div class="flex items-center gap-2.5 sm:gap-3 mb-5 sm:mb-7">
            <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-linear-to-br from-emerald-400 to-green-500 flex items-center justify-center shadow-sm shrink-0">
                <svg
                    class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                ><path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"
                /></svg>
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

        <div class="space-y-4 sm:space-y-6">
            <!-- Timezone -->
            <div>
                <label class="block text-sm sm:text-md font-semibold text-stone-700 mb-1">Timezone</label>
                <p class="text-xs sm:text-sm text-stone-500 mb-2">
                    Used to determine your practice day boundaries
                </p>
                <div class="relative">
                    <input
                        v-model="tzSearch"
                        type="text"
                        class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-xl border border-stone-200 bg-stone-50/50 text-stone-800 focus:outline-none focus:ring-2 focus:ring-violet-400/50 focus:border-violet-400 transition-all"
                        placeholder="Search timezone..."
                        @focus="tzDropdownOpen = true"
                        @blur="tzDropdownOpen = false"
                    >
                    <div
                        v-if="tzDropdownOpen"
                        class="absolute z-10 mt-1 w-full max-h-60 overflow-y-auto rounded-xl border border-stone-200 bg-white shadow-lg"
                    >
                        <button
                            v-for="tz in filteredTimezones"
                            :key="tz"
                            type="button"
                            class="w-full text-left px-3.5 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base hover:bg-amber-50 transition-colors"
                            :class="tz === timezone ? 'bg-amber-50 font-semibold text-amber-700' : 'text-stone-700'"
                            @mousedown.prevent="selectTimezone(tz)"
                        >
                            {{ tz.replace(/_/g, ' ') }}
                        </button>
                        <p
                            v-if="filteredTimezones.length === 0"
                            class="px-4 py-3 text-sm text-stone-400"
                        >
                            No timezones found
                        </p>
                    </div>
                </div>
            </div>

            <!-- Unsaved changes -->
            <Transition name="fade">
                <div
                    v-if="dirty"
                    class="relative mt-2 px-3 sm:px-4 py-2.5 sm:py-3 bg-white/70 rounded-xl border border-emerald-200/50 shadow-sm flex items-center justify-between gap-2"
                >
                    <p class="text-sm sm:text-md font-semibold text-stone-600">
                        <span class="font-bold uppercase text-emerald-600">Unsaved changes</span>
                    </p>
                    <button
                        class="px-4 sm:px-5 py-1.5 sm:py-2 text-sm sm:text-base font-semibold rounded-lg border border-emerald-400 bg-emerald-500 text-white hover:bg-emerald-600 transition-colors duration-200 shadow-sm disabled:opacity-50 shrink-0"
                        :disabled="saving"
                        @click="save"
                    >
                        {{ saving ? 'Saving...' : 'Update Preferences' }}
                    </button>
                </div>
            </Transition>
            <p
                v-if="errorMsg"
                class="text-sm text-red-500 font-medium mt-2"
            >
                {{ errorMsg }}
            </p>
            <p
                v-if="successMsg"
                class="text-sm text-emerald-600 font-medium mt-2"
            >
                {{ successMsg }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import api from '@/api'
import { auth } from '@/auth'

const allTimezones = Intl.supportedValuesOf('timeZone')

const timezone = ref(auth.user?.timezone ?? Intl.DateTimeFormat().resolvedOptions().timeZone)
const savedTimezone = ref(timezone.value)
const tzSearch = ref(timezone.value.replace(/_/g, ' '))
const tzDropdownOpen = ref(false)

const filteredTimezones = computed(() => {
    const query = tzSearch.value.toLowerCase().replace(/\s+/g, '_')
    if (!query) return allTimezones
    return allTimezones.filter(tz => tz.toLowerCase().includes(query))
})

function selectTimezone(tz) {
    timezone.value = tz
    tzSearch.value = tz.replace(/_/g, ' ')
    tzDropdownOpen.value = false
}

const saving = ref(false)
const errorMsg = ref('')
const successMsg = ref('')

const dirty = computed(() => timezone.value !== savedTimezone.value)

watch(dirty, (d) => {
    if (d) {
        errorMsg.value = ''
        successMsg.value = ''
    }
})

async function save() {
    saving.value = true
    errorMsg.value = ''
    successMsg.value = ''
    try {
        const res = await api.put('/api/user', {
            timezone: timezone.value,
        })
        auth.setUser(res.data)
        savedTimezone.value = timezone.value
        successMsg.value = 'Preferences updated.'
    } catch (e) {
        errorMsg.value = e.data?.message || 'Something went wrong.'
    } finally {
        saving.value = false
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
