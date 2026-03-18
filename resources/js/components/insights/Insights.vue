<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50/80 via-white to-stone-50 relative overflow-hidden">
        <FloatingNotes />

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
            <header class="mb-6 sm:mb-10 text-center">
                <h1 class="text-2xl sm:text-4xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    Practice <span class="bg-linear-to-r from-amber-600 to-orange-500 bg-clip-text text-transparent">Insights</span>
                </h1>
                <p class="text-sm sm:text-base font-semibold text-stone-500 mt-1.5 sm:mt-2">
                    A snapshot of your piano journey
                </p>
                <div class="mt-2.5 sm:mt-3 mx-auto w-12 sm:w-16 h-1 rounded-full bg-linear-to-r from-amber-400 to-orange-400 opacity-60" />
            </header>

            <!-- Stats grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                <div
                    v-for="stat in stats"
                    :key="stat.label"
                    class="card px-3.5 sm:px-5 py-4 sm:py-5 flex flex-col relative overflow-hidden group hover:shadow-lg transition-shadow duration-300"
                    :class="stat.tintClass"
                >
                    <span
                        class="absolute -bottom-2 -right-2 text-4xl sm:text-5xl opacity-20 rotate-[-10deg] font-serif select-none transition-transform duration-300 group-hover:scale-110"
                        :class="stat.watermarkClass"
                        v-html="stat.icon"
                    />
                    <div class="relative flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                        <div
                            class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center shadow-sm shrink-0"
                            :class="stat.bgClass"
                        >
                            <span
                                class="text-white text-xs sm:text-sm"
                                v-html="stat.icon"
                            />
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-stone-700 uppercase tracking-widest">
                            {{ stat.label }}
                        </p>
                    </div>
                    <p class="relative text-2xl sm:text-3xl font-bold text-stone-800">
                        {{ stat.value }}<span
                            v-if="stat.unit"
                            class="text-xs sm:text-sm font-semibold text-stone-600 ml-1"
                        >{{ stat.unit }}</span>
                    </p>
                    <p class="relative text-xs sm:text-sm font-semibold text-stone-600 mt-1">
                        {{ stat.detail }}
                    </p>
                </div>
            </div>

            <!-- Monthly breakdown -->
            <header class="mt-10 sm:mt-16 mb-4 sm:mb-6 text-center">
                <h2 class="text-xl sm:text-3xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    This Month's<span class="bg-linear-to-r from-amber-600 to-orange-500 bg-clip-text text-transparent"> Progress</span>
                </h2>
                <div class="mt-2.5 sm:mt-3 mx-auto w-10 sm:w-12 h-1 rounded-full bg-linear-to-r from-amber-600 to-orange-400 opacity-60" />
            </header>

            <section class="card px-3 sm:px-6 py-4 sm:py-5 bg-white border-stone-200 hover:shadow-lg transition-shadow duration-300">
                <Calendar
                    :attributes="calendarAttributes"
                    class="custom-calendar"
                    expanded
                    borderless
                >
                    <template #day-content="{ day }">
                        <div
                            class="w-full h-full flex flex-col p-1 sm:p-1.5 rounded-lg"
                            :class="getDayBoxClass(day)"
                        >
                            <span
                                class="text-[10px] sm:text-xs font-bold leading-none w-4 h-4 sm:w-6 sm:h-6 flex items-center justify-center rounded-full"
                                :class="isToday(day) ? 'bg-amber-500 text-white' : 'text-stone-500'"
                            >{{ day.day }}</span>
                            <div class="flex-1 flex items-center justify-center">
                                <span
                                    v-if="practicedDates.has(day.id)"
                                    class="w-5 h-5 sm:w-7 sm:h-7 rounded-full bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white text-xs sm:text-sm font-bold shadow-sm"
                                >&#10003;</span>
                            </div>
                        </div>
                    </template>
                </Calendar>
                <div class="flex items-center justify-end gap-3 sm:gap-4 mt-3 sm:mt-4 text-xs font-semibold text-stone-500">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-amber-500" />
                        <span>Today</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-linear-to-br from-amber-400 to-orange-500" />
                        <span>Practiced</span>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import FloatingNotes from '../ui/FloatingNotes.vue'
import { Calendar } from 'v-calendar'
import 'v-calendar/style.css'

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

// Set random dates to have checkmarks for demo
const practicedDates = computed(() => {
    const dates = new Set()
    // Add some random dates in this month for demo
    const randomDays = [3, 5, 7, 8, 12, 14, 18, 21, 25, 27, 30]
    const year = new Date().getFullYear()
    const month = String(new Date().getMonth() + 1).padStart(2, '0')
    randomDays.forEach(day => {
        dates.add(`${year}-${month}-${String(day).padStart(2, '0')}`)
    })
    return dates
})

const calendarAttributes = ref([])

const now = new Date()

function isToday(day) {
    const d = new Date(day.id)
    return d.getDate() === now.getDate() && d.getMonth() === now.getMonth() && d.getFullYear() === now.getFullYear()
}

function getDayBoxClass(day) {
    const base = 'border transition-all duration-200 hover:shadow-md cursor-default'
    if (isToday(day)) return `${base} border-amber-400 bg-amber-50/80 shadow-sm`
    if (practicedDates.value.has(day.id)) return `${base} border-amber-200 bg-linear-to-br from-amber-50/80 to-orange-50/60 hover:border-amber-400`
    return `${base} border-stone-200 bg-white hover:border-stone-300`
}
</script>

<style scoped>
:deep(.vc-container) {
    font-family: ui-sans-serif, system-ui, sans-serif;
}

:deep(.vc-header) {
    padding: 0.75rem 0 1.75rem;
}

:deep(.vc-title) {
    font-family: 'Comfortaa', ui-sans-serif, system-ui, sans-serif;
    font-size: 1.4rem;
    font-weight: 800;
    color: rgb(28 25 23);
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

:deep(.vc-arrow) {
    color: rgb(120 113 108);
    border-radius: 0.5rem;
}

:deep(.vc-arrow:hover) {
    background-color: rgb(254 243 199);
    color: rgb(180 83 9);
}

:deep(.vc-weekday) {
    font-weight: 700;
    font-size: 0.7rem;
    letter-spacing: 0.1em;
    color: rgb(120 113 108);
    text-transform: uppercase;
    padding-bottom: 0.5rem;
}

:deep(.vc-day) {
    padding: 0.1rem;
    min-height: 3.5rem;
}

@media (min-width: 640px) {
    :deep(.vc-day) {
        padding: 0.2rem;
        min-height: 5rem;
    }
}

:deep(.vc-day-content) {
    width: 100%;
    height: 100%;
}
</style>
