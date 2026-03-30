<template>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <router-link
            to="/insights"
            class="card px-4 sm:px-5 py-4 sm:py-5 bg-white border-violet-200 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300 cursor-pointer no-underline"
        >
            <div class="relative flex items-center gap-2.5 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-linear-to-br from-violet-400 to-purple-500 flex items-center justify-center shadow-sm">
                    <svg
                        class="w-4 h-4 text-white"
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
                <p class="text-xs sm:text-sm font-bold text-stone-700 uppercase tracking-widest">
                    Repertoire
                </p>
            </div>
            <p class="relative text-2xl sm:text-3xl font-bold text-stone-800">
                {{ stats.totalPieces }}
            </p>
            <p class="relative text-xs sm:text-sm font-semibold text-stone-600 mt-1">
                {{ stats.learning }} learning &middot; {{ stats.polished }} polished
            </p>
        </router-link>

        <router-link
            to="/insights"
            class="card px-4 sm:px-5 py-4 sm:py-5 bg-white border-rose-200 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300 cursor-pointer no-underline"
        >
            <div class="relative flex items-center gap-2.5 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-linear-to-br from-rose-400 to-pink-500 flex items-center justify-center shadow-sm">
                    <svg
                        class="w-4 h-4 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <p class="text-xs sm:text-sm font-bold text-stone-700 uppercase tracking-widest">
                    Avg. Session
                </p>
            </div>
            <p class="relative text-2xl sm:text-3xl font-bold text-stone-800">
                {{ stats.avgSessionMinutes }}<span class="text-xs sm:text-sm font-semibold text-stone-600 ml-1.5">min</span>
            </p>
            <p class="relative text-xs sm:text-sm font-semibold text-stone-600 mt-1">
                {{ stats.totalSessions }} sessions total
            </p>
        </router-link>

        <router-link
            to="/insights"
            class="card px-4 sm:px-5 py-4 sm:py-5 bg-white border-amber-200 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300 cursor-pointer no-underline"
        >
            <div class="relative flex items-center gap-2.5 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-sm">
                    <svg
                        class="w-4 h-4 text-white"
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
                <p class="text-xs sm:text-sm font-bold text-stone-700 uppercase tracking-widest">
                    This Week
                </p>
            </div>
            <p class="relative text-2xl sm:text-3xl font-bold text-stone-800">
                {{ stats.weeklyHours }}<span class="text-xs sm:text-sm font-semibold text-stone-600 ml-1.5">hrs</span>
            </p>
            <p class="relative text-xs sm:text-sm font-semibold text-stone-600 mt-1">
                {{ stats.weeklySessionCount }} sessions
            </p>
        </router-link>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const stats = ref({
    totalPieces: 12,
    learning: 5,
    polished: 7,
    avgSessionMinutes: 48,
    totalSessions: 23,
    weeklyHours: 6.5,
    weeklySessionCount: 5,
})
</script>
