<template>
    <div
        v-if="loading"
        class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6 sm:mb-8"
    >
        <div
            v-for="i in 3"
            :key="i"
            class="h-20 sm:h-24 rounded-2xl border border-stone-200 bg-white animate-pulse"
        />
    </div>
    <div
        v-else
        class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6 sm:mb-8"
    >
        <router-link
            to="/insights"
            class="flex items-center gap-4 rounded-2xl border bg-white px-4 py-4 sm:px-5 sm:py-5 shadow-md transition-shadow duration-200 hover:shadow-lg no-underline border-violet-300"
        >
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-linear-to-br from-violet-400 to-purple-500 flex items-center justify-center shrink-0">
                <svg
                    class="w-5 h-5 sm:w-6 sm:h-6 text-white"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V4.125A2.25 2.25 0 0017.868 1.9l-6.75 1.929a2.25 2.25 0 00-1.618 2.163V15m0 0v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 01-.99-3.467l2.31-.66A2.25 2.25 0 009.5 15.553z"
                    />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-violet-600 uppercase tracking-wider leading-none mb-1.5">
                    Repertoire
                </p>
                <p class="text-xl sm:text-2xl font-bold text-stone-800 leading-tight">
                    {{ stats.totalPieces }}
                </p>
                <p class="text-xs sm:text-sm text-stone-600 font-medium leading-tight mt-0.5">
                    {{ stats.learning }} learning · {{ stats.polished }} polished
                </p>
            </div>
        </router-link>

        <router-link
            to="/insights"
            class="flex items-center gap-4 rounded-2xl border bg-white px-4 py-4 sm:px-5 sm:py-5 shadow-md transition-shadow duration-200 hover:shadow-lg no-underline border-sky-300"
        >
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-linear-to-br from-sky-400 to-blue-500 flex items-center justify-center shrink-0">
                <svg
                    class="w-5 h-5 sm:w-6 sm:h-6 text-white"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"
                    />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-sky-600 uppercase tracking-wider leading-none mb-1.5">
                    Avg. Session
                </p>
                <p class="text-xl sm:text-2xl font-bold text-stone-800 leading-tight">
                    {{ stats.avgSessionMinutes }}<span class="text-xs sm:text-sm font-semibold text-stone-600 ml-0.5">min</span>
                </p>
                <p class="text-xs sm:text-sm text-stone-600 font-medium leading-tight mt-0.5">
                    {{ stats.totalSessions }} sessions total
                </p>
            </div>
        </router-link>

        <router-link
            to="/insights"
            class="flex items-center gap-4 rounded-2xl border bg-white px-4 py-4 sm:px-5 sm:py-5 shadow-md transition-shadow duration-200 hover:shadow-lg no-underline border-emerald-300"
        >
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-linear-to-br from-emerald-400 to-teal-500 flex items-center justify-center shrink-0">
                <svg
                    class="w-5 h-5 sm:w-6 sm:h-6 text-white"
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
            <div class="min-w-0">
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider leading-none mb-1.5">
                    This Week
                </p>
                <p class="text-xl sm:text-2xl font-bold text-stone-800 leading-tight">
                    {{ stats.weeklyHours }}<span class="text-xs sm:text-sm font-semibold text-stone-600 ml-0.5">hrs</span>
                </p>
                <p class="text-xs sm:text-sm text-stone-600 font-medium leading-tight mt-0.5">
                    {{ stats.weeklySessionCount }} sessions
                </p>
            </div>
        </router-link>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'

const loading = ref(true)
const stats = ref({
    totalPieces: 0,
    learning: 0,
    polished: 0,
    avgSessionMinutes: 0,
    totalSessions: 0,
    weeklyHours: 0,
    weeklySessionCount: 0,
})

async function fetchStats() {
    try {
        const data = await api.get('/api/insights')
        const weekHours = data.week_seconds / 3600
        stats.value = {
            totalPieces: data.total_pieces,
            learning: data.learning_pieces,
            polished: data.polishing_pieces,
            avgSessionMinutes: Math.round(data.avg_session_seconds / 60),
            totalSessions: data.lifetime_sessions,
            weeklyHours: weekHours % 1 === 0 ? weekHours : parseFloat(weekHours.toFixed(1)),
            weeklySessionCount: data.week_sessions,
        }
    } finally {
        loading.value = false
    }
}

defineExpose({ refresh: fetchStats })

onMounted(fetchStats)
</script>
