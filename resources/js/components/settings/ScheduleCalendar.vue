<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @mousedown.self="$emit('close')"
            >
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" />

                <!-- Modal panel -->
                <div class="relative bg-white rounded-2xl shadow-xl w-[75vw] max-h-[92vh] overflow-y-auto p-10">
                    <!-- Header with month name and close button -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-sm">
                                <span class="text-white text-sm">&#128197;</span>
                            </div>
                            <h3 class="text-2xl font-serif font-bold uppercase tracking-wide text-stone-800">
                                {{ monthName }} {{ year }}
                            </h3>
                        </div>
                        <button
                            class="w-8 h-8 rounded-lg border border-stone-200 bg-white text-stone-400 hover:bg-stone-50 hover:text-stone-600 transition-colors flex items-center justify-center"
                            title="Close"
                            @click="$emit('close')"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Day-of-week headers -->
                    <div class="grid grid-cols-7 gap-1.5 mb-1.5">
                        <div
                            v-for="day in weekdays"
                            :key="day"
                            class="text-center text-sm font-semibold text-stone-400 uppercase py-1.5"
                        >
                            {{ day }}
                        </div>
                    </div>

                    <!-- Calendar grid -->
                    <div class="grid grid-cols-7 gap-1.5">
                        <!-- Empty cells for offset -->
                        <div
                            v-for="n in startOffset"
                            :key="'empty-' + n"
                            class="min-h-24"
                        />

                        <!-- Day cells -->
                        <div
                            v-for="day in daysInMonth"
                            :key="day"
                            class="min-h-24 rounded-lg border p-2 transition-colors duration-150"
                            :class="isToday(day) ? 'border-amber-400 bg-amber-50/60' : isPracticeDay(day) ? 'border-stone-200 bg-white' : 'border-stone-100 bg-stone-50/50'"
                        >
                            <div class="mb-1">
                                <span
                                    class="text-sm font-bold"
                                    :class="isToday(day) ? 'text-amber-600' : 'text-stone-500'"
                                >
                                    {{ day }}
                                </span>
                            </div>
                            <div
                                v-for="piece in getPiecesForDay(day)"
                                :key="piece.id"
                                class="text-sm leading-snug text-stone-600 truncate"
                                :title="piece.title + ' - ' + piece.composer"
                            >
                                {{ piece.title }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    show: { type: Boolean, default: false },
    piecesPerDay: { type: Number, required: true },
    frequency: { type: String, required: true },
})

defineEmits(['close'])

const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

// Hardcoded repertoire (same as other components — will be centralized later)
const allPieces = [
    { id: 1, title: 'Clair de Lune', composer: 'Debussy' },
    { id: 2, title: 'Nocturne Op. 9 No. 2', composer: 'Chopin' },
    { id: 3, title: 'Gymnopédie No. 1', composer: 'Satie' },
    { id: 4, title: 'Prelude in C Major', composer: 'Bach' },
    { id: 5, title: 'Moonlight Sonata, Mvt. 1', composer: 'Beethoven' },
]

const now = new Date()
const year = now.getFullYear()
const month = now.getMonth()

const monthName = computed(() =>
    new Date(year, month).toLocaleString('default', { month: 'long' })
)

const daysInMonth = computed(() => new Date(year, month + 1, 0).getDate())

const startOffset = computed(() => new Date(year, month, 1).getDay())

function isToday(day) {
    return day === now.getDate() && month === now.getMonth() && year === now.getFullYear()
}

function isPracticeDay(day) {
    return getPiecesForDay(day).length > 0
}

function shouldPractice(date) {
    const dayOfWeek = date.getDay()

    switch (props.frequency) {
        case 'every_day':
            return true
        case 'weekdays':
            return dayOfWeek >= 1 && dayOfWeek <= 5
        case 'weekends':
            return dayOfWeek === 0 || dayOfWeek === 6
        case 'weekly':
            return dayOfWeek === 1
        case 'biweekly': {
            // Every other Monday starting from the first Monday of the month
            const firstDay = new Date(year, month, 1)
            const diff = Math.floor((date - firstDay) / (1000 * 60 * 60 * 24))
            return dayOfWeek === 1 && Math.floor(diff / 7) % 2 === 0
        }
        case 'monthly':
            return date.getDate() === 1
        default: {
            // every_N_days patterns
            const match = props.frequency.match(/every_(\d+)_days/)
            if (match) {
                const interval = parseInt(match[1])
                const epoch = new Date(year, month, 1)
                const diff = Math.floor((date - epoch) / (1000 * 60 * 60 * 24))
                return diff % interval === 0
            }
            return false
        }
    }
}

function getPiecesForDay(day) {
    const date = new Date(year, month, day)
    if (!shouldPractice(date)) return []

    // Rotate through pieces based on the day index among practice days
    const practiceDayIndex = countPracticeDaysBefore(day)
    const count = Math.min(props.piecesPerDay, allPieces.length)
    const startIdx = (practiceDayIndex * count) % allPieces.length
    const result = []
    for (let i = 0; i < count; i++) {
        result.push(allPieces[(startIdx + i) % allPieces.length])
    }
    return result
}

function countPracticeDaysBefore(day) {
    let count = 0
    for (let d = 1; d < day; d++) {
        if (shouldPractice(new Date(year, month, d))) count++
    }
    return count
}
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}
.modal-enter-active .relative,
.modal-leave-active .relative {
  transition: opacity 0.2s ease, transform 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
.modal-enter-from .relative {
  transform: scale(0.95);
}
.modal-leave-to .relative {
  transform: scale(0.95);
}
</style>
