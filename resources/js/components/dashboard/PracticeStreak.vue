<template>
    <section class="mt-8 card px-6 py-5 bg-white border-orange-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-stone-700 uppercase tracking-widest">
                Last 4 Weeks
            </h3>
            <div class="flex items-center gap-2 text-xs font-semibold text-stone-600">
                <span>Less</span>
                <div class="w-3 h-3 rounded bg-stone-200/80" />
                <div class="w-3 h-3 rounded bg-amber-200" />
                <div class="w-3 h-3 rounded bg-amber-400" />
                <div class="w-3 h-3 rounded bg-amber-600" />
                <span>More</span>
            </div>
        </div>
        <div class="flex gap-1.5 justify-between">
            <div
                v-for="(day, i) in days"
                :key="i"
                class="flex flex-col items-center gap-1"
            >
                <div
                    class="w-5 h-5 rounded-md transition-all duration-300 hover:scale-125"
                    :class="day.level === 0 ? 'bg-stone-200/60'
                        : day.level === 1 ? 'bg-amber-200'
                            : day.level === 2 ? 'bg-amber-400'
                                : 'bg-amber-600 shadow-sm shadow-amber-300/40'"
                    :title="day.label"
                />
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue'

const days = ref(generateStreak())

function generateStreak() {
    const result = []
    const today = new Date()
    for (let i = 27; i >= 0; i--) {
        const d = new Date(today)
        d.setDate(d.getDate() - i)
        const label = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
        const rand = Math.random()
        const recencyBoost = i < 14 ? 0.2 : 0
        const level = rand < 0.15 ? 0 : rand < 0.4 - recencyBoost ? 1 : rand < 0.7 ? 2 : 3
        result.push({ label, level })
    }
    return result
}
</script>
