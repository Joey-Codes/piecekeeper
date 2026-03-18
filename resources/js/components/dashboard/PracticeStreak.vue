<template>
    <router-link
        to="/insights#calendar"
        class="block mt-6 sm:mt-8 card px-4 sm:px-6 py-4 sm:py-5 bg-white border-orange-200 no-underline cursor-pointer hover:shadow-lg transition-shadow duration-300"
    >
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h3 class="text-xs sm:text-sm font-bold text-stone-700 uppercase tracking-widest">
                Last 7 Days
            </h3>
            <div class="flex items-center gap-1.5 sm:gap-2 text-xs font-semibold text-stone-600">
                <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded bg-stone-200/80" />
                <span>Missed</span>
                <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded bg-amber-400" />
                <span>Practiced</span>
            </div>
        </div>
        <div class="flex gap-2 sm:gap-3 justify-between">
            <div
                v-for="(day, i) in days"
                :key="i"
                class="flex-1 flex flex-col items-center gap-1"
            >
                <span class="text-[10px] sm:text-xs font-semibold text-stone-400 uppercase">{{ day.dayName }}</span>
                <div
                    class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110"
                    :class="day.done
                        ? 'bg-linear-to-br from-amber-400 to-orange-500 shadow-sm shadow-amber-300/40'
                        : 'bg-stone-200/60'"
                    :title="day.label + (day.done ? ' ✓' : '')"
                >
                    <svg
                        v-if="day.done"
                        class="w-4 h-4 sm:w-5 sm:h-5 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="3"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>
                <span class="text-[10px] sm:text-xs font-semibold text-stone-500">{{ day.dateNum }}</span>
            </div>
        </div>
    </router-link>
</template>

<script setup>
import { ref } from 'vue'

const days = ref(generateStreak())

function generateStreak() {
    const result = []
    const today = new Date()
    for (let i = 6; i >= 0; i--) {
        const d = new Date(today)
        d.setDate(d.getDate() - i)
        const label = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
        const dayName = d.toLocaleDateString('en-US', { weekday: 'short' })
        const dateNum = d.getDate()
        const rand = Math.random()
        const done = rand > 0.2
        result.push({ label, dayName, dateNum, done })
    }
    return result
}
</script>
