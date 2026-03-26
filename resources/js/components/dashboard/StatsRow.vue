<template>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <router-link
            to="/insights"
            class="card px-4 sm:px-5 py-4 sm:py-5 bg-white border-violet-200 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300 cursor-pointer no-underline"
        >
            <div class="relative flex items-center gap-2.5 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-linear-to-br from-violet-400 to-purple-500 flex items-center justify-center shadow-sm">
                    <span class="text-white text-xs">&#9733;</span>
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
                    <span class="text-white text-xs">&#9829;</span>
                </div>
                <p class="text-xs sm:text-sm font-bold text-stone-700 uppercase tracking-widest">
                    Streak
                </p>
            </div>
            <p class="relative text-2xl sm:text-3xl font-bold text-stone-800">
                {{ stats.streakDays }}<span class="text-xs sm:text-sm font-semibold text-stone-600 ml-1.5">days</span>
            </p>
            <p class="relative text-xs sm:text-sm font-semibold text-stone-600 mt-1">
                Keep it going!
            </p>
        </router-link>

        <router-link
            to="/insights"
            class="card px-4 sm:px-5 py-4 sm:py-5 bg-white border-amber-200 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300 cursor-pointer no-underline"
        >
            <div class="relative flex items-center gap-2.5 sm:gap-3 mb-2 sm:mb-3">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-sm">
                    <span class="text-white text-xs">&#9201;</span>
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
    streakDays: 14,
    weeklyHours: 6.5,
    weeklySessionCount: 5,
})
</script>
