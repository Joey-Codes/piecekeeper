<template>
  <div class="mb-8">
    <div v-if="!session.active" class="flex justify-center">
      <button
        @click="startSession"
        class="group flex items-center gap-3 px-8 py-4 bg-linear-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-2xl shadow-md hover:shadow-lg hover:scale-[1.02] transition-all duration-200"
      >
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z" />
        </svg>
        Start Practice
      </button>
    </div>

    <div v-else class="card px-6 py-5">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div
            class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm"
            :class="paused
              ? 'bg-linear-to-br from-amber-300 to-yellow-400'
              : 'bg-linear-to-br from-emerald-400 to-green-500 animate-pulse'"
          >
            <div class="w-3 h-3 bg-white rounded-full" />
          </div>
          <div>
            <p class="text-sm font-semibold text-stone-500">
              {{ paused ? 'Session paused' : 'Session in progress' }}
            </p>
            <p class="text-2xl font-bold tabular-nums" :class="paused ? 'text-stone-400' : 'text-stone-800'">
              {{ elapsedTime }}
            </p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <!-- Pause / Resume button -->
          <button
            @click="togglePause"
            class="flex items-center gap-2 px-5 py-3 font-semibold rounded-xl shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-200"
            :class="paused
              ? 'bg-linear-to-r from-emerald-500 to-green-500 text-white'
              : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
          >
            <!-- Pause icon -->
            <svg v-if="!paused" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
            </svg>
            <!-- Play/Resume icon -->
            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z" />
            </svg>
            {{ paused ? 'Resume' : 'Pause' }}
          </button>
          <!-- End Session button -->
          <button
            @click="promptEnd"
            class="flex items-center gap-2 px-5 py-3 bg-linear-to-r from-rose-500 to-pink-500 text-white font-semibold rounded-xl shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-200"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 7.5A2.25 2.25 0 017.5 5.25h9a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25h-9a2.25 2.25 0 01-2.25-2.25v-9z" />
            </svg>
            End
          </button>
        </div>
      </div>
    </div>

    <!-- End session confirmation -->
    <ConfirmModal
      :show="showEndConfirm"
      title="End practice session?"
      :message="`You've been practicing for ${elapsedTime}. Are you sure you want to end this session?`"
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

const emit = defineEmits(['update:active'])

const session = ref({ active: false, startTime: null })
const elapsedSeconds = ref(0)
const paused = ref(false)
const showEndConfirm = ref(false)
let wasRunningBeforePrompt = false
let accumulatedSeconds = 0
let timerInterval = null
let segmentStart = null

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

function confirmEnd() {
  showEndConfirm.value = false
  session.value.active = false
  paused.value = false
  stopTimer()
  accumulatedSeconds = 0
  emit('update:active', false)
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
