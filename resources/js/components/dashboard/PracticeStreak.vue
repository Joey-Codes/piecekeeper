<template>
    <router-link
        to="/insights#calendar"
        class="block mt-6 sm:mt-8 rounded-2xl border border-stone-200 bg-white px-4 sm:px-8 py-6 sm:py-7 shadow-md no-underline cursor-pointer hover:shadow-lg transition-shadow duration-200"
    >
        <h3 class="text-md font-serif font-bold tracking-wide text-stone-800 uppercase mb-5 sm:mb-6 text-center">
            Last 7 Days
        </h3>
        <div class="flex gap-1 sm:gap-3 justify-between">
            <div
                v-for="(day, i) in days"
                :key="i"
                class="flex-1 flex flex-col items-center gap-2.5 sm:gap-3"
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
    </router-link>
</template>

<script setup>
import { ref, nextTick, onMounted } from 'vue'
import api from '@/api'

const days = ref(buildEmptyWeek())

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

defineExpose({ markTodayDone })

onMounted(async () => {
    try {
        const data = await api.get('/api/dashboard/history')
        const sessionDates = new Set(
            data.sessions
                .filter(s => s.duration_seconds > 0)
                .map(s => s.date)
        )
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
</style>
