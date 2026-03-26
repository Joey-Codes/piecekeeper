<template>
    <div class="mb-6 sm:mb-8">
        <div
            v-if="!session.active"
            class="flex justify-center"
        >
            <!-- Completed: review button -->
            <button
                v-if="completed"
                class="group flex items-center gap-2.5 sm:gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-linear-to-r from-emerald-500 to-green-500 text-white text-sm sm:text-base font-semibold rounded-2xl shadow-lg shadow-emerald-300/30 hover:shadow-xl hover:shadow-emerald-300/40 hover:scale-[1.03] hover:-translate-y-0.5 transition-all duration-200"
                @click="$emit('review')"
            >
                <svg
                    class="w-4 h-4 sm:w-5 sm:h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
                Review Session Details
            </button>
            <!-- Not started: start button -->
            <button
                v-else
                class="group flex items-center gap-2.5 sm:gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-linear-to-r from-amber-500 to-orange-500 text-white text-sm sm:text-base font-semibold rounded-2xl shadow-lg shadow-amber-300/30 hover:shadow-xl hover:shadow-amber-300/40 hover:scale-[1.03] hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:pointer-events-none"
                :disabled="loading"
                @click="startSession"
            >
                <svg
                    class="w-4 h-4 sm:w-5 sm:h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"
                    />
                </svg>
                Start Practice Session
            </button>
        </div>

        <div
            v-else
            class="card px-4 sm:px-6 py-4 sm:py-5"
        >
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center shadow-sm shrink-0"
                        :class="paused
                            ? 'bg-linear-to-br from-amber-300 to-yellow-400'
                            : 'bg-linear-to-br from-emerald-400 to-green-500 animate-pulse'"
                    >
                        <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 bg-white rounded-full" />
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-stone-500">
                            {{ paused ? 'Session paused' : 'Session in progress' }}
                        </p>
                        <p
                            class="text-xl sm:text-2xl font-bold tabular-nums"
                            :class="paused ? 'text-stone-400' : 'text-stone-800'"
                        >
                            {{ elapsedTime }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Pause / Resume button -->
                    <button
                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 text-sm sm:text-base font-semibold rounded-xl shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-200"
                        :class="paused
                            ? 'bg-linear-to-r from-emerald-500 to-green-500 text-white'
                            : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                        @click="togglePause"
                    >
                        <!-- Pause icon -->
                        <svg
                            v-if="!paused"
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15.75 5.25v13.5m-7.5-13.5v13.5"
                            />
                        </svg>
                        <!-- Play/Resume icon -->
                        <svg
                            v-else
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"
                            />
                        </svg>
                        {{ paused ? 'Resume' : 'Pause' }}
                    </button>
                    <!-- End Session button -->
                    <button
                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 text-sm sm:text-base bg-linear-to-r from-rose-500 to-pink-500 text-white font-semibold rounded-xl shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-200"
                        @click="promptEnd"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5.25 7.5A2.25 2.25 0 017.5 5.25h9a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25h-9a2.25 2.25 0 01-2.25-2.25v-9z"
                            />
                        </svg>
                        Finish
                    </button>
                </div>
            </div>
        </div>

        <!-- End session confirmation -->
        <ConfirmModal
            :show="showEndConfirm"
            title="Finish practice session?"
            :message="endMessage"
            confirm-label="End Session"
            cancel-label="Keep Practicing"
            @confirm="confirmEnd"
            @cancel="cancelEnd"
        />
    </div>
</template>

<script setup>
import { ref, computed, onUnmounted } from 'vue'
import ConfirmModal from '../ui/ConfirmModal.vue'
import api from '@/api'

const props = defineProps({
    sessionId: { type: Number, default: null },
    loading: { type: Boolean, default: false },
    completed: { type: Boolean, default: false },
    remainingCount: { type: Number, default: 0 },
})

const emit = defineEmits(['update:active', 'session-finished', 'review'])

const session = ref({ active: false, startTime: null })
const elapsedSeconds = ref(0)
const paused = ref(false)
const showEndConfirm = ref(false)
let wasRunningBeforePrompt = false
let accumulatedSeconds = 0
let timerInterval = null
let segmentStart = null

const endMessage = computed(() => {
    let msg = `You've been practicing for ${elapsedTime.value}.`
    if (props.remainingCount > 0) {
        msg += ` You have not completed ${props.remainingCount} ${props.remainingCount === 1 ? 'piece' : 'pieces'}.`
    }
    msg += ' Are you sure you want to end this session?'
    return msg
})

const elapsedTime = computed(() => {
    const hrs = Math.floor(elapsedSeconds.value / 3600)
    const mins = Math.floor((elapsedSeconds.value % 3600) / 60)
    const secs = elapsedSeconds.value % 60
    const pad = (n) => String(n).padStart(2, '0')
    return hrs > 0 ? `${hrs}:${pad(mins)}:${pad(secs)}` : `${mins}:${pad(secs)}`
})

function startTimer() {
    segmentStart = Date.now()
    timerInterval = setInterval(() => {
        elapsedSeconds.value = accumulatedSeconds + Math.floor((Date.now() - segmentStart) / 1000)
    }, 1000)
}

function stopTimer() {
    accumulatedSeconds = elapsedSeconds.value
    clearInterval(timerInterval)
    timerInterval = null
    segmentStart = null
}

function startSession() {
    session.value.active = true
    paused.value = false
    accumulatedSeconds = 0
    elapsedSeconds.value = 0
    startTimer()
    emit('update:active', true)
}

function togglePause() {
    if (paused.value) {
        paused.value = false
        startTimer()
    } else {
        paused.value = true
        stopTimer()
    }
}

function promptEnd() {
    wasRunningBeforePrompt = !paused.value
    if (wasRunningBeforePrompt) {
        paused.value = true
        stopTimer()
    }
    showEndConfirm.value = true
}

async function confirmEnd() {
    showEndConfirm.value = false
    stopTimer()

    let finishedSession = null
    if (props.sessionId) {
        try {
            const data = await api.post(`/api/dashboard/sessions/${props.sessionId}/finish`, {
                duration_seconds: elapsedSeconds.value,
            })
            finishedSession = data.session
        } catch {
            // Still end the local session even if the API call fails
        }
    }

    session.value.active = false
    paused.value = false
    accumulatedSeconds = 0
    emit('update:active', false)
    emit('session-finished', finishedSession)
}

function cancelEnd() {
    showEndConfirm.value = false
    if (wasRunningBeforePrompt) {
        paused.value = false
        startTimer()
    }
}

onUnmounted(() => {
    if (timerInterval) clearInterval(timerInterval)
})
</script>
