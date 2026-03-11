<template>
    <div class="min-h-screen bg-white">
        <div class="relative max-w-4xl mx-auto px-6 py-12">
            <header class="mb-10">
                <p class="text-lg font-semibold tracking-wide text-amber-500 uppercase mb-1">
                    IT'S {{ formattedDate }}
                </p>
                <h1 class="text-3xl font-serif font-bold tracking-wide text-stone-800">
                    Welcome Back, <span class="text-amber-700 normal-case">{{ name }}</span>
                </h1>
            </header>

            <PracticeSession @update:active="sessionActive = $event" />
            <PracticeChecklist :disabled="!sessionActive" />
            <StatsRow />
            <PracticeStreak />
            <ComposerQuote />
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import PracticeSession from './PracticeSession.vue'
import StatsRow from './StatsRow.vue'
import PracticeChecklist from './PracticeChecklist.vue'
import PracticeStreak from './PracticeStreak.vue'
import ComposerQuote from './ComposerQuote.vue'

const name = ref('James')
const sessionActive = ref(false)

const formattedDate = computed(() => {
    return new Date().toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
    })
})
</script>
