<template>
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="card px-5 py-4">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-linear-to-br from-violet-400 to-purple-500 flex items-center justify-center">
                    <span class="text-white text-xs">&#9733;</span>
                </div>
                <p class="text-sm font-semibold text-stone-800 uppercase tracking-wide">
                    Repertoire
                </p>
            </div>
            <p class="text-2xl font-bold text-stone-800">
                {{ stats.totalPieces }}
            </p>
            <p class="text-sm font-semibold text-stone-400 mt-0.5">
                {{ stats.learning }} learning &middot; {{ stats.polished }} polished
            </p>
        </div>

        <div class="card px-5 py-4">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-linear-to-br from-rose-400 to-pink-500 flex items-center justify-center">
                    <span class="text-white text-xs">&#9829;</span>
                </div>
                <p class="text-sm font-semibold text-stone-800 uppercase tracking-wide">
                    Streak
                </p>
            </div>
            <p class="text-2xl font-bold text-stone-800">
                {{ stats.streakDays }}<span class="text-sm font-semibold text-stone-400 ml-1">days</span>
            </p>
            <p class="text-sm font-semibold text-stone-400 mt-0.5">
                Keep it going!
            </p>
        </div>

        <div class="card px-5 py-4">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                    <span class="text-white text-xs">&#9201;</span>
                </div>
                <p class="text-sm font-semibold text-stone-800 uppercase tracking-wide">
                    This Week
                </p>
            </div>
            <p class="text-2xl font-bold text-stone-800">
                {{ stats.weeklyHours }}<span class="text-sm font-semibold text-stone-400 ml-1">hrs</span>
            </p>
            <p class="text-sm font-semibold text-stone-400 mt-0.5">
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
