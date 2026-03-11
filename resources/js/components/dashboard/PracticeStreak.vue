<template>
    <section class="mt-8 card px-6 py-5">
        <h3 class="text-sm font-semibold text-stone-600 mb-3">
            Last 4 Weeks
        </h3>
        <div class="flex gap-1.5 justify-between">
            <div
                v-for="(day, i) in days"
                :key="i"
                class="flex flex-col items-center gap-1"
            >
                <div
                    class="w-5 h-5 rounded-md transition-colors"
                    :class="day.level === 0 ? 'bg-stone-100'
                        : day.level === 1 ? 'bg-amber-200'
                            : day.level === 2 ? 'bg-amber-400'
                                : 'bg-amber-600'"
                    :title="day.label"
                />
            </div>
        </div>
        <div class="flex items-center gap-2 mt-3 text-sm font-semibold text-stone-400">
            <span>Less</span>
            <div class="w-3.5 h-3.5 rounded bg-stone-100" />
            <div class="w-3.5 h-3.5 rounded bg-amber-200" />
            <div class="w-3.5 h-3.5 rounded bg-amber-400" />
            <div class="w-3.5 h-3.5 rounded bg-amber-600" />
            <span>More</span>
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
