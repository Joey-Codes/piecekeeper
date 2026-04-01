<template>
    <div class="mt-6 sm:mt-8 rounded-2xl border border-orange-300 bg-white px-4 sm:px-8 py-6 sm:py-7 shadow-md">
        <div class="flex items-center justify-between mb-5 sm:mb-6">
            <h3 class="text-base font-serif font-bold tracking-wide text-stone-800 uppercase">
                Last 7 Days
            </h3>
            <router-link
                to="/insights#calendar"
                class="px-3 py-1.5 text-xs rounded-xl font-semibold text-white bg-linear-to-r from-amber-500 to-orange-500 shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-200 no-underline"
            >
                View All
            </router-link>
        </div>
        <div class="flex gap-1 sm:gap-3 justify-between">
            <div
                v-for="(day, i) in days"
                :key="i"
                class="flex-1 flex flex-col items-center gap-2.5 sm:gap-3"
                :class="day.done ? 'cursor-pointer' : ''"
                @click="day.done && openSessionDetail(day.dateKey)"
            >
                <span
                    class="text-[10px] sm:text-xs font-bold uppercase tracking-wide"
                    :class="day.isToday ? 'text-amber-600' : 'text-stone-700'"
                >{{ day.dayName }}</span>
                <div
                    class="w-9 h-9 sm:w-13 sm:h-13 rounded-lg sm:rounded-xl flex items-center justify-center transition-all duration-300"
                    :class="[
                        day.done
                            ? 'bg-linear-to-br from-amber-400 to-orange-500 shadow-md shadow-amber-300/30'
                            : 'bg-stone-100 border border-stone-200/60',
                        day.isToday && !day.done ? 'ring-2 ring-amber-300/50' : '',
                        day.justCompleted ? 'animate-pop' : '',
                    ]"
                    :title="day.label + (day.done ? ' ✓' : '')"
                >
                    <svg
                        v-if="day.done"
                        class="w-4 h-4 sm:w-6 sm:h-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>
                <span
                    class="text-[10px] sm:text-sm font-bold"
                    :class="day.isToday ? 'text-amber-600' : 'text-stone-600'"
                >{{ day.dateNum }}</span>
            </div>
        </div>
    </div>

    <!-- Session Detail Modal -->
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="sessionModal.show"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @mousedown.self="sessionModal.show = false"
            >
                <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" />

                <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-5 sm:p-6">
                    <template v-if="sessionModal.session">
                        <!-- Header -->
                        <div class="text-center mb-5">
                            <div class="mx-auto w-12 h-12 rounded-xl bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-sm mb-3">
                                <svg
                                    class="w-6 h-6 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                                    />
                                </svg>
                            </div>
                            <h3 class="text-xl font-serif font-bold uppercase tracking-wide text-stone-800">
                                {{ formatModalDate(sessionModal.session.date) }}
                            </h3>
                            <div class="text-2xl font-bold text-stone-800 mt-2">
                                {{ formatDuration(sessionModal.session.duration_seconds) }}
                            </div>
                        </div>

                        <!-- Piece list -->
                        <div class="space-y-2 mb-5">
                            <div
                                v-for="piece in sessionModal.session.pieces"
                                :key="piece.id"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl"
                                :class="piece.completed
                                    ? 'bg-emerald-50 border border-emerald-100'
                                    : 'bg-stone-50 border border-stone-200'"
                            >
                                <div
                                    class="w-5 h-5 rounded-full flex items-center justify-center shrink-0"
                                    :class="piece.completed ? 'bg-emerald-400' : 'bg-stone-300'"
                                >
                                    <svg
                                        v-if="piece.completed"
                                        class="w-3 h-3 text-white"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="3"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                    <svg
                                        v-else
                                        class="w-3 h-3 text-white"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="3"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M5 12h14"
                                        />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <span
                                        class="text-sm font-semibold truncate block"
                                        :class="piece.completed ? 'text-emerald-700' : 'text-stone-400'"
                                    >
                                        {{ piece.title }}
                                    </span>
                                    <span
                                        v-if="piece.composer"
                                        class="text-xs text-stone-400 truncate block"
                                    >
                                        {{ piece.composer }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Summary -->
                        <p class="text-center text-sm text-stone-800 mb-4">
                            {{ sessionModal.session.pieces_completed }}/{{ sessionModal.session.pieces_assigned }} pieces were completed
                        </p>

                        <button
                            class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-linear-to-r from-amber-500 to-orange-500 rounded-xl hover:shadow-md hover:scale-[1.01] transition-all duration-150"
                            @click="sessionModal.show = false"
                        >
                            Close
                        </button>
                    </template>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, nextTick, onMounted } from 'vue'
import api from '@/api'

const days = ref(buildEmptyWeek())
const sessionsMap = ref({})

const sessionModal = ref({
    show: false,
    session: null,
})

function buildEmptyWeek() {
    const result = []
    const today = new Date()
    for (let i = 6; i >= 0; i--) {
        const d = new Date(today)
        d.setDate(d.getDate() - i)
        const isToday = i === 0
        result.push({
            label: d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }),
            dayName: isToday ? 'Today' : d.toLocaleDateString('en-US', { weekday: 'short' }),
            dateNum: d.getDate(),
            dateKey: `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`,
            done: false,
            isToday,
        })
    }
    return result
}

function markTodayDone() {
    const today = days.value.find(d => d.isToday)
    if (today && !today.done) {
        today.done = true
        nextTick(() => {
            today.justCompleted = true
        })
    }
}

function openSessionDetail(dateKey) {
    const session = sessionsMap.value[dateKey]
    if (session) {
        sessionModal.value = { show: true, session }
    }
}

function formatModalDate(dateStr) {
    return new Date(dateStr + 'T00:00:00').toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
    })
}

function formatDuration(seconds) {
    const hrs = Math.floor(seconds / 3600)
    const mins = Math.floor((seconds % 3600) / 60)
    const secs = seconds % 60
    const pad = (n) => String(n).padStart(2, '0')
    if (hrs > 0) return `${hrs}:${pad(mins)}:${pad(secs)}`
    return `${pad(mins)}:${pad(secs)}`
}

defineExpose({ markTodayDone })

onMounted(async () => {
    try {
        const data = await api.get('/api/dashboard/history')
        const map = {}
        data.sessions
            .filter(s => s.duration_seconds > 0)
            .forEach(s => { map[s.date] = s })
        sessionsMap.value = map
        const sessionDates = new Set(Object.keys(map))
        days.value = days.value.map(day => ({
            ...day,
            done: sessionDates.has(day.dateKey),
        }))
    } catch {
        // Keep empty week on failure
    }
})
</script>

<style scoped>
.animate-pop {
    animation: pop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes pop {
    0% { transform: scale(0); opacity: 0; }
    60% { transform: scale(1.2); }
    100% { transform: scale(1); opacity: 1; }
}

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}
.modal-enter-active .relative,
.modal-leave-active .relative {
    transition: transform 0.2s ease, opacity 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
.modal-enter-from .relative {
    transform: scale(0.95);
    opacity: 0;
}
.modal-leave-to .relative {
    transform: scale(0.95);
    opacity: 0;
}
</style>
