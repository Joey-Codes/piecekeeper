<template>
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="card px-5 py-5 bg-white border-violet-200 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
            <span class="absolute -bottom-2 -right-2 text-5xl text-violet-200/30 rotate-[-10deg] font-serif select-none transition-transform duration-300 group-hover:scale-110">&#9733;</span>
            <div class="relative flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-linear-to-br from-violet-400 to-purple-500 flex items-center justify-center shadow-sm">
                    <span class="text-white text-xs">&#9733;</span>
                </div>
                <p class="text-sm font-bold text-stone-700 uppercase tracking-widest">
                    Repertoire
                </p>
            </div>
            <p class="relative text-3xl font-bold text-stone-800">
                {{ stats.totalPieces }}
            </p>
            <p class="relative text-sm font-semibold text-stone-600 mt-1">
                {{ stats.learning }} learning &middot; {{ stats.polished }} polished
            </p>
        </div>

        <div class="card px-5 py-5 bg-white border-rose-200 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
            <span class="absolute -bottom-2 -right-2 text-5xl text-rose-200/30 rotate-10 font-serif select-none transition-transform duration-300 group-hover:scale-110">&#9829;</span>
            <div class="relative flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-linear-to-br from-rose-400 to-pink-500 flex items-center justify-center shadow-sm">
                    <span class="text-white text-xs">&#9829;</span>
                </div>
                <p class="text-sm font-bold text-stone-700 uppercase tracking-widest">
                    Streak
                </p>
            </div>
            <p class="relative text-3xl font-bold text-stone-800">
                {{ stats.streakDays }}<span class="text-sm font-semibold text-stone-600 ml-1.5">days</span>
            </p>
            <p class="relative text-sm font-semibold text-stone-600 mt-1">
                Keep it going!
            </p>
        </div>

        <div class="card px-5 py-5 bg-white border-amber-200 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
            <span class="absolute -bottom-2 -right-2 text-5xl text-amber-200/30 rotate-[-15deg] font-serif select-none transition-transform duration-300 group-hover:scale-110">&#9201;</span>
            <div class="relative flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-sm">
                    <span class="text-white text-xs">&#9201;</span>
                </div>
                <p class="text-sm font-bold text-stone-700 uppercase tracking-widest">
                    This Week
                </p>
            </div>
            <p class="relative text-3xl font-bold text-stone-800">
                {{ stats.weeklyHours }}<span class="text-sm font-semibold text-stone-600 ml-1.5">hrs</span>
            </p>
            <p class="relative text-sm font-semibold text-stone-600 mt-1">
                {{ stats.weeklySessionCount }} sessions
            </p>
        </div>
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
