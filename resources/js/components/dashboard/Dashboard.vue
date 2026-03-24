<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50/80 via-white to-stone-50 relative overflow-hidden">
        <FloatingNotes />

        <div class="relative max-w-4xl mx-auto px-6 py-12">
            <header class="mb-10 text-center">
                <p class="text-sm font-semibold tracking-widest text-amber-600 uppercase mb-2">
                    IT'S {{ formattedDate }}
                </p>
                <h1 class="text-4xl font-serif font-bold tracking-wide text-stone-800">
                    Welcome Back, <span class="bg-linear-to-r from-amber-600 to-orange-500 bg-clip-text text-transparent">{{ name }}</span>
                </h1>
                <div class="mt-3 mx-auto w-16 h-1 rounded-full bg-linear-to-r from-amber-400 to-orange-400 opacity-60" />
            </header>

            <PracticeSession @update:active="sessionActive = $event" />
            <PracticeChecklist :disabled="!sessionActive" />
            <StatsRow />
            <PracticeStreak />
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import PracticeSession from './PracticeSession.vue'
import StatsRow from './StatsRow.vue'
import PracticeChecklist from './PracticeChecklist.vue'
import PracticeStreak from './PracticeStreak.vue'
import FloatingNotes from '../ui/FloatingNotes.vue'
import { auth } from '@/auth'

const name = computed(() => auth.user?.name || '')
const sessionActive = ref(false)

const formattedDate = computed(() => {
    return new Date().toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
    })
})
</script>
