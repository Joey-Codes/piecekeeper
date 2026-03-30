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
                    <div class="relative flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                        <div
                            class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center shadow-sm shrink-0"
                            :class="stat.bgClass"
                        >
                            <div
                                class="text-white w-3.5 h-3.5 sm:w-4 sm:h-4"
                                v-html="stat.icon"
                            />
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-stone-700 uppercase tracking-widest">
                            {{ stat.label }}
                        </p>
                    </div>
                    <p
                        class="relative font-bold text-stone-800 truncate"
                        :class="typeof stat.value === 'string' ? 'text-lg sm:text-xl' : 'text-2xl sm:text-3xl'"
                    >
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
            <header
                id="calendar"
                class="mt-10 sm:mt-16 mb-4 sm:mb-6 text-center"
            >
                <h2 class="text-xl sm:text-3xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    This Month's<span class="bg-linear-to-r from-amber-600 to-orange-500 bg-clip-text text-transparent"> Progress</span>
                </h2>
                <div class="mt-2.5 sm:mt-3 mx-auto w-10 sm:w-12 h-1 rounded-full bg-linear-to-r from-amber-600 to-orange-400 opacity-60" />
            </header>

            <section class="card px-3 sm:px-6 py-4 sm:py-5 bg-white border-stone-200 hover:shadow-lg transition-shadow duration-300">
                <Calendar
                    :attributes="calendarAttributes"
                    :max-date="new Date()"
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
                                :class="isToday(day) ? 'bg-linear-to-br from-violet-500 to-purple-600 text-white shadow-sm' : 'text-stone-500'"
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
                        <span class="w-3 h-3 rounded-full bg-violet-500" />
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
        label: 'Lifetime Practice',
        value: 18.5,
        unit: 'hrs',
        detail: '23 sessions total',
        icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        bgClass: 'bg-linear-to-br from-rose-400 to-pink-500',
        tintClass: 'bg-white border-rose-200',
    },
    {
        label: 'Total Pieces',
        value: 12,
        unit: null,
        detail: '5 learning \u00b7 7 polished',
        icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V4.125A2.25 2.25 0 0017.868 1.9l-6.75 1.929a2.25 2.25 0 00-1.618 2.163V15m0 0v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 01-.99-3.467l2.31-.66A2.25 2.25 0 009.5 15.553z" /></svg>',
        bgClass: 'bg-linear-to-br from-violet-400 to-purple-500',
        tintClass: 'bg-white border-violet-200',
    },
    {
        label: 'This Week',
        value: 6.5,
        unit: 'hrs',
        detail: '5 sessions',
        icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>',
        bgClass: 'bg-linear-to-br from-amber-400 to-orange-500',
        tintClass: 'bg-white border-amber-200',
    },
    {
        label: 'Most Practiced',
        value: 'Clair de Lune',
        unit: null,
        detail: '18 sessions',
        icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-4.5A3.375 3.375 0 0012.75 11h-.5A3.375 3.375 0 009 14.25v4.5m7.5 0h-6m1.5-12V2.25m3.75 2.25L13.5 2.25m-3 2.25L12.75 2.25" /></svg>',
        bgClass: 'bg-linear-to-br from-emerald-400 to-teal-500',
        tintClass: 'bg-white border-emerald-200',
    },
    {
        label: 'Avg. Session',
        value: 42,
        unit: 'min',
        detail: 'Trending up this month',
        icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>',
        bgClass: 'bg-linear-to-br from-sky-400 to-blue-500',
        tintClass: 'bg-white border-sky-200',
    },
    {
        label: 'Pieces Learned',
        value: 7,
        unit: null,
        detail: '2 this month',
        icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15v-3.75m0 0h10.5m-10.5 0L12 7.5l5.25 3.75" /></svg>',
        bgClass: 'bg-linear-to-br from-yellow-400 to-amber-500',
        tintClass: 'bg-white border-yellow-200',
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
    if (isToday(day)) return `${base} border-violet-400 bg-violet-50/80 shadow-sm`
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
