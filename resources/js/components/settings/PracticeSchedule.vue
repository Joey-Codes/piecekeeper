<template>
    <div>
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

            <!-- Update schedule button -->
            <Transition name="fade">
                <div
                    v-if="scheduleDirty"
                    class="relative mt-2 px-3 sm:px-4 py-2.5 sm:py-3 bg-white/70 rounded-xl border border-emerald-200/50 shadow-sm flex items-center justify-between gap-2"
                >
                    <p class="text-sm sm:text-md font-semibold text-stone-600">
                        <span class="font-bold uppercase text-emerald-600">Unsaved changes</span>
                    </p>
                    <button
                        class="px-4 sm:px-5 py-1.5 sm:py-2 text-sm sm:text-base font-semibold rounded-lg border border-emerald-400 bg-emerald-500 text-white hover:bg-emerald-600 transition-colors duration-200 shadow-sm disabled:opacity-50 shrink-0"
                        :disabled="savingSchedule"
                        @click="saveSchedule"
                    >
                        {{ savingSchedule ? 'Saving...' : 'Update Schedule' }}
                    </button>
                </div>
            </Transition>
            <p
                v-if="scheduleError"
                class="text-sm text-red-500 font-medium mt-2"
            >
                {{ scheduleError }}
            </p>
            <p
                v-if="scheduleSuccess"
                class="text-sm text-emerald-600 font-medium mt-2"
            >
                {{ scheduleSuccess }}
            </p>
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
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Calendar } from 'v-calendar'
import 'v-calendar/style.css'
import api from '@/api'
import { auth } from '@/auth'

const showCalendar = ref(false)

const piecesPerDay = ref(auth.user?.pieces_per_session ?? 3)
const frequency = ref(auth.user?.practice_frequency ?? 'every_day')
const savedPiecesPerDay = ref(piecesPerDay.value)
const savedFrequency = ref(frequency.value)

const savingSchedule = ref(false)
const scheduleError = ref('')
const scheduleSuccess = ref('')

const scheduleDirty = computed(() => {
    return piecesPerDay.value !== savedPiecesPerDay.value
        || frequency.value !== savedFrequency.value
})

watch(scheduleDirty, (dirty) => {
    if (dirty) {
        scheduleError.value = ''
        scheduleSuccess.value = ''
    }
})

async function saveSchedule() {
    savingSchedule.value = true
    scheduleError.value = ''
    scheduleSuccess.value = ''
    try {
        const res = await api.put('/api/user', {
            pieces_per_session: piecesPerDay.value,
            practice_frequency: frequency.value,
        })
        auth.setUser(res.data)
        savedPiecesPerDay.value = piecesPerDay.value
        savedFrequency.value = frequency.value
        scheduleSuccess.value = 'Schedule updated.'
    } catch (e) {
        scheduleError.value = e.data?.message || 'Something went wrong.'
    } finally {
        savingSchedule.value = false
    }
}

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
