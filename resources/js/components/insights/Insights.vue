<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50/80 via-white to-stone-50 relative overflow-hidden">
        <FloatingNotes />

        <div class="relative max-w-4xl mx-auto px-6 py-12">
            <header class="mb-10 text-center">
                <h1 class="text-4xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    Practice <span class="bg-linear-to-r from-amber-600 to-orange-500 bg-clip-text text-transparent">Insights</span>
                </h1>
                <p class="font-semibold text-stone-500 mt-2">
                    A snapshot of your piano journey
                </p>
                <div class="mt-3 mx-auto w-16 h-1 rounded-full bg-linear-to-r from-amber-400 to-orange-400 opacity-60" />
            </header>

            <!-- Stats grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div
                    v-for="stat in stats"
                    :key="stat.label"
                    class="card px-5 py-5 flex flex-col relative overflow-hidden group hover:shadow-lg transition-shadow duration-300"
                    :class="stat.tintClass"
                >
                    <span
                        class="absolute -bottom-2 -right-2 text-5xl opacity-20 rotate-[-10deg] font-serif select-none transition-transform duration-300 group-hover:scale-110"
                        :class="stat.watermarkClass"
                        v-html="stat.icon"
                    />
                    <div class="relative flex items-center gap-3 mb-3">
                        <div
                            class="w-9 h-9 rounded-xl flex items-center justify-center shadow-sm"
                            :class="stat.bgClass"
                        >
                            <span
                                class="text-white text-sm"
                                v-html="stat.icon"
                            />
                        </div>
                        <p class="text-sm font-bold text-stone-700 uppercase tracking-widest">
                            {{ stat.label }}
                        </p>
                    </div>
                    <p class="relative text-3xl font-bold text-stone-800">
                        {{ stat.value }}<span
                            v-if="stat.unit"
                            class="text-sm font-semibold text-stone-600 ml-1.5"
                        >{{ stat.unit }}</span>
                    </p>
                    <p class="relative text-sm font-semibold text-stone-600 mt-1">
                        {{ stat.detail }}
                    </p>
                </div>
            </div>

            <!-- Monthly breakdown -->
            <header class="mt-16 mb-6 text-center">
                <h2 class="text-3xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    This <span class="bg-linear-to-r from-violet-500 to-purple-500 bg-clip-text text-transparent">Month</span>
                </h2>
                <div class="mt-3 mx-auto w-12 h-1 rounded-full bg-linear-to-r from-violet-400 to-purple-400 opacity-60" />
            </header>

            <section class="card px-6 py-5 bg-white border-stone-200 hover:shadow-lg transition-shadow duration-300">
                <div class="grid grid-cols-7 gap-2">
                    <div
                        v-for="(day, i) in monthDays"
                        :key="i"
                        class="aspect-square rounded-lg transition-all duration-300 hover:scale-110"
                        :class="dayClass(day)"
                        :title="day.label"
                    />
                </div>
                <div class="flex items-center justify-end gap-2 mt-4 text-xs font-semibold text-stone-600">
                    <span>Less</span>
                    <div class="w-3 h-3 rounded bg-stone-200/80" />
                    <div class="w-3 h-3 rounded bg-amber-200" />
                    <div class="w-3 h-3 rounded bg-amber-400" />
                    <div class="w-3 h-3 rounded bg-amber-600" />
                    <span>More</span>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import FloatingNotes from '../ui/FloatingNotes.vue'

const stats = ref([
    {
        label: 'Current Streak',
        value: 14,
        unit: 'days',
        detail: 'Best streak: 21 days',
        icon: '&#128293;',
        bgClass: 'bg-linear-to-br from-rose-400 to-pink-500',
        tintClass: 'bg-white border-rose-200',
        watermarkClass: 'text-rose-200',
    },
    {
        label: 'Total Pieces',
        value: 12,
        unit: null,
        detail: '5 learning \u00b7 7 polished',
        icon: '&#9835;',
        bgClass: 'bg-linear-to-br from-violet-400 to-purple-500',
        tintClass: 'bg-white border-violet-200',
        watermarkClass: 'text-violet-200',
    },
    {
        label: 'This Week',
        value: 6.5,
        unit: 'hrs',
        detail: '5 sessions',
        icon: '&#9201;',
        bgClass: 'bg-linear-to-br from-amber-400 to-orange-500',
        tintClass: 'bg-white border-amber-200',
        watermarkClass: 'text-amber-200',
    },
    {
        label: 'Total Practice',
        value: 84,
        unit: 'hrs',
        detail: 'Since you started',
        icon: '&#9200;',
        bgClass: 'bg-linear-to-br from-emerald-400 to-teal-500',
        tintClass: 'bg-white border-emerald-200',
        watermarkClass: 'text-emerald-200',
    },
    {
        label: 'Avg. Session',
        value: 42,
        unit: 'min',
        detail: 'Trending up this month',
        icon: '&#9889;',
        bgClass: 'bg-linear-to-br from-sky-400 to-blue-500',
        tintClass: 'bg-white border-sky-200',
        watermarkClass: 'text-sky-200',
    },
    {
        label: 'Pieces Learned',
        value: 7,
        unit: null,
        detail: '2 this month',
        icon: '&#127942;',
        bgClass: 'bg-linear-to-br from-yellow-400 to-amber-500',
        tintClass: 'bg-white border-yellow-200',
        watermarkClass: 'text-yellow-200',
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
    if (day.minutes === 0) return 'bg-stone-200/60'
    if (day.minutes < 30) return 'bg-amber-200'
    if (day.minutes < 50) return 'bg-amber-400'
    return 'bg-amber-600 shadow-sm shadow-amber-300/40'
}
</script>
