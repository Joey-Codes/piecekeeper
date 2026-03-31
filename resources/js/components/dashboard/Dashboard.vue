<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50/80 via-white to-stone-50 relative overflow-hidden">
        <FloatingNotes />

        <div class="relative max-w-4xl mx-auto px-6 py-12">
            <header class="mb-10 text-center">
                <p class="text-sm font-semibold tracking-widest text-amber-600 uppercase mb-2">
                    IT'S {{ formattedDate }}
                </p>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold tracking-wide text-stone-800">
                    Welcome Back, <span class="bg-linear-to-r from-amber-600 to-orange-500 bg-clip-text text-transparent">{{ name }}</span>
                </h1>
            </header>

            <PracticeSession
                :session-id="todaySession?.id"
                :loading="loading"
                :completed="todaySession?.duration_seconds > 0"
                :remaining-count="sessionPieces.filter(p => !p.done).length"
                @update:active="sessionActive = $event"
                @session-finished="onSessionFinished"
                @review="showReview = true"
            />
            <PracticeChecklist
                :disabled="!sessionActive"
                :session-id="todaySession?.id"
                :pieces="sessionPieces"
                :loading="loading"
                :session-finished="todaySession?.duration_seconds > 0"
                @piece-toggled="onPieceToggled"
            />
            <StatsRow ref="statsRowRef" />
            <PracticeStreak ref="practiceStreakRef" />
        </div>

        <SessionReviewModal
            :show="showReview"
            :session-id="todaySession?.id"
            :pieces="sessionPieces"
            :duration-seconds="todaySession?.duration_seconds ?? 0"
            @close="showReview = false"
            @updated="onSessionUpdated"
        />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import PracticeSession from './PracticeSession.vue'
import StatsRow from './StatsRow.vue'
import PracticeChecklist from './PracticeChecklist.vue'
import PracticeStreak from './PracticeStreak.vue'
import SessionReviewModal from './SessionReviewModal.vue'
import FloatingNotes from '../ui/FloatingNotes.vue'
import { auth } from '@/auth'
import api from '@/api'

const name = computed(() => auth.user?.name || '')
const practiceStreakRef = ref(null)
const statsRowRef = ref(null)
const sessionActive = ref(false)
const loading = ref(true)
const todaySession = ref(null)
const showReview = ref(false)

const sessionPieces = computed(() => {
    if (!todaySession.value?.pieces) return []
    return todaySession.value.pieces.map(p => ({
        ...p,
        done: p.completed,
    }))
})

async function fetchTodaySession() {
    loading.value = true
    try {
        const data = await api.get('/api/dashboard/today')
        todaySession.value = data.session
    } finally {
        loading.value = false
    }
}

function onPieceToggled({ pieceId, completed, piecesCompleted }) {
    if (!todaySession.value) return
    const piece = todaySession.value.pieces.find(p => p.id === pieceId)
    if (piece) piece.completed = completed
    todaySession.value.pieces_completed = piecesCompleted
}

function onSessionFinished(session) {
    if (session) {
        todaySession.value = session
    } else if (todaySession.value) {
        todaySession.value = { ...todaySession.value, duration_seconds: 1 }
    }
    setTimeout(() => practiceStreakRef.value?.markTodayDone(), 1500)
    statsRowRef.value?.refresh()
}

function onSessionUpdated(session) {
    todaySession.value = session
    statsRowRef.value?.refresh()
}

onMounted(fetchTodaySession)

const formattedDate = computed(() => {
    return new Date().toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
    })
})
</script>
