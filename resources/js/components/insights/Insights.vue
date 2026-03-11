<template>
    <div class="min-h-screen bg-white">
        <div class="relative max-w-4xl mx-auto px-6 py-12">
            <header class="mb-10">
                <h1 class="text-3xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    Practice <span class="text-amber-700">Insights</span>
                </h1>
                <p class="font-semibold text-stone-600 mt-1">
                    A snapshot of your piano journey
                </p>
            </header>

            <!-- Stats grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div
                    v-for="stat in stats"
                    :key="stat.label"
                    class="card px-5 py-5 flex flex-col"
                >
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-9 h-9 rounded-xl flex items-center justify-center shadow-sm"
                            :class="stat.bgClass"
                        >
                            <span
                                class="text-white text-sm"
                                v-html="stat.icon"
                            />
                        </div>
                        <p class="text-md font-semibold text-stone-800 uppercase tracking-wide">
                            {{ stat.label }}
                        </p>
                    </div>
                    <p class="text-3xl font-bold text-stone-800">
                        {{ stat.value }}<span
                            v-if="stat.unit"
                            class="text-sm font-semibold text-stone-500 ml-1"
                        >{{ stat.unit }}</span>
                    </p>
                    <p class="text-md font-semibold text-stone-500 mt-1">
                        {{ stat.detail }}
                    </p>
                </div>
            </div>

            <!-- Monthly breakdown -->
            <header class="mt-14 mb-6">
                <h2 class="text-2xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    This <span class="text-amber-700">Month</span>
                </h2>
            </header>

            <div class="grid grid-cols-7 gap-2">
                <div
                    v-for="(day, i) in monthDays"
                    :key="i"
                    class="aspect-square rounded-lg transition-colors"
                    :class="dayClass(day)"
                    :title="day.label"
                />
            </div>
            <div class="flex items-center gap-3 mt-3 text-sm font-semibold text-stone-400">
                <span>Less</span>
                <div class="flex gap-1">
                    <div class="w-3 h-3 rounded bg-stone-100" />
                    <div class="w-3 h-3 rounded bg-amber-200" />
                    <div class="w-3 h-3 rounded bg-amber-400" />
                    <div class="w-3 h-3 rounded bg-amber-600" />
                </div>
                <span>More</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const stats = ref([
    {
        label: 'Current Streak',
        value: 14,
        unit: 'days',
        detail: 'Best streak: 21 days',
        icon: '&#128293;',
        bgClass: 'bg-linear-to-br from-rose-400 to-pink-500',
    },
    {
        label: 'Total Pieces',
        value: 12,
        unit: null,
        detail: '5 learning \u00b7 7 polished',
        icon: '&#9835;',
        bgClass: 'bg-linear-to-br from-violet-400 to-purple-500',
    },
    {
        label: 'This Week',
        value: 6.5,
        unit: 'hrs',
        detail: '5 sessions',
        icon: '&#9201;',
        bgClass: 'bg-linear-to-br from-amber-400 to-orange-500',
    },
    {
        label: 'Total Practice',
        value: 84,
        unit: 'hrs',
        detail: 'Since you started',
        icon: '&#9200;',
        bgClass: 'bg-linear-to-br from-emerald-400 to-teal-500',
    },
    {
        label: 'Avg. Session',
        value: 42,
        unit: 'min',
        detail: 'Trending up this month',
        icon: '&#9889;',
        bgClass: 'bg-linear-to-br from-sky-400 to-blue-500',
    },
    {
        label: 'Pieces Learned',
        value: 7,
        unit: null,
        detail: '2 this month',
        icon: '&#127942;',
        bgClass: 'bg-linear-to-br from-yellow-400 to-amber-500',
    },
])

// Generate mock data for 31 days
const monthDays = ref(
    Array.from({ length: 31 }, (_, i) => {
        const mins = [0, 0, 20, 35, 0, 50, 45, 0, 30, 60, 40, 0, 0, 25, 55, 70, 0, 40, 30, 0, 45, 50, 60, 0, 0, 35, 40, 55, 0, 50, 45][i]
        return { minutes: mins, label: `Mar ${i + 1}: ${mins} min` }
    })
)

function dayClass(day) {
    if (day.minutes === 0) return 'bg-stone-100'
    if (day.minutes < 30) return 'bg-amber-200'
    if (day.minutes < 50) return 'bg-amber-400'
    return 'bg-amber-600'
}
</script>
