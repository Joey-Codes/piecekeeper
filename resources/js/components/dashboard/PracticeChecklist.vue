<template>
    <section
        class="card overflow-hidden relative mb-6 sm:mb-8 hover:shadow-lg transition-shadow duration-300"
        :class="allDone ? 'border-emerald-200' : 'border-orange-200'"
    >
        <!-- Card header -->
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-stone-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0">
            <div class="flex items-center gap-2.5 sm:gap-3">
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center shadow-sm shrink-0">
                    <span class="text-white text-xs sm:text-sm">&#9835;</span>
                </div>
                <h2 class="text-base sm:text-xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    Today's Practice<span
                        v-if="allDone"
                        class="text-emerald-500"
                    > (Completed)</span>
                </h2>
            </div>
            <div class="flex items-center gap-2.5 sm:gap-3">
                <span
                    class="text-sm sm:text-base font-semibold"
                    :class="allDone ? 'text-emerald-500' : 'text-stone-800'"
                >
                    {{ completedCount }}/{{ pieces.length }}
                </span>
                <div class="flex-1 sm:flex-none sm:w-44 h-3 sm:h-4 bg-stone-100 rounded-full overflow-hidden">
                    <div
                        class="h-full rounded-full transition-all duration-500 ease-out"
                        :class="allDone ? 'bg-emerald-400' : 'bg-linear-to-r from-amber-400 to-orange-400'"
                        :style="{ width: progressPercent + '%' }"
                    />
                </div>
            </div>
        </div>

        <!-- Disabled hint banner -->
        <div
            v-if="disabled && !sessionFinished"
            class="px-4 sm:px-6 py-2 sm:py-2.5 bg-stone-50 border-b border-stone-200 flex items-center gap-2"
        >
            <svg
                class="w-3.5 h-3.5 text-amber-400 shrink-0"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            <span class="text-sm sm:text-md text-stone-600">Press <strong class="text-amber-700">Start Practice</strong> above to check off pieces</span>
        </div>

        <!-- Loading state -->
        <LoadingSpinner
            v-if="loading"
            message="Loading today's pieces..."
        />

        <!-- Empty state -->
        <div
            v-else-if="pieces.length === 0"
            class="p-6 sm:p-8 text-center"
        >
            <p class="text-sm sm:text-base text-stone-500">
                No pieces in rotation yet. Add pieces in your repertoire to get started!
            </p>
        </div>

        <!-- Piece list -->
        <div
            v-else
            class="p-3 sm:p-4 space-y-2 sm:space-y-3"
        >
            <div
                v-for="(piece, index) in pieces"
                :key="piece.id"
                class="flex items-center gap-2.5 sm:gap-3 p-2.5 sm:p-3 rounded-xl transition-all duration-300 cursor-pointer"
                :class="piece.done
                    ? 'bg-emerald-50 border border-emerald-100'
                    : sessionFinished
                        ? 'bg-stone-50 border border-stone-200 opacity-60'
                        : 'bg-white border border-stone-200 shadow-sm hover:border-amber-300 hover:shadow-md'"
                @click="selectedPiece = piece"
            >
                <!-- Check / number circle -->
                <div
                    :class="disabled ? 'opacity-30 pointer-events-none' : ''"
                    @click.stop
                >
                    <TrebleClefCheck
                        :checked="piece.done"
                        :label="index + 1"
                        @toggle="toggle(piece)"
                    />
                </div>

                <!-- Piece info -->
                <div class="flex-1 min-w-0">
                    <p
                        class="text-sm sm:text-base font-semibold transition-all duration-300 truncate"
                        :class="piece.done ? 'text-emerald-700' : 'text-stone-700'"
                    >
                        {{ piece.title }}
                    </p>
                    <p
                        class="text-xs sm:text-sm mt-0.5 transition-all duration-300"
                        :class="piece.done ? 'text-emerald-500' : 'text-stone-400'"
                    >
                        {{ piece.composer }}
                    </p>
                </div>

                <!-- Status tag -->
                <div
                    v-if="piece.done"
                    class="text-xs px-2 py-1 rounded-full font-medium whitespace-nowrap bg-emerald-100 text-emerald-600"
                >
                    Done
                </div>
                <div
                    v-else-if="sessionFinished"
                    class="text-xs px-2 py-1 rounded-full font-medium whitespace-nowrap bg-stone-100 text-stone-400"
                >
                    Not completed
                </div>
                <div
                    v-else-if="piece.id === nextUpId"
                    class="text-xs px-2 py-1 rounded-full font-medium whitespace-nowrap bg-amber-100 text-amber-600"
                >
                    Up next
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div
            class="px-4 sm:px-6 py-3 sm:py-4 border-t border-stone-200 transition-colors duration-300"
            :class="allDone ? 'bg-emerald-400' : 'bg-linear-to-r from-amber-400 to-orange-300'"
        >
            <p
                v-if="allDone"
                class="text-base sm:text-lg text-white font-semibold text-center"
            >
                All done for today — great work! &#127929;
            </p>
            <p
                v-else
                class="text-base sm:text-lg font-semibold text-white text-center"
            >
                {{ pieces.length - completedCount }} {{ pieces.length - completedCount === 1 ? 'piece' : 'pieces' }} remaining
            </p>
        </div>
    </section>

    <PieceViewModal
        :piece="selectedPiece"
        :practice-active="!disabled"
        @toggle-done="toggleDoneById"
        @close="selectedPiece = null"
    />
</template>

<script setup>
import { ref, computed } from 'vue'
import LoadingSpinner from '../ui/LoadingSpinner.vue'
import TrebleClefCheck from '../ui/TrebleClefCheck.vue'
import PieceViewModal from '../repertoire/PieceViewModal.vue'
import api from '@/api'

const props = defineProps({
    disabled: { type: Boolean, default: false },
    sessionId: { type: Number, default: null },
    pieces: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
    sessionFinished: { type: Boolean, default: false },
})

const emit = defineEmits(['piece-toggled'])

const selectedPiece = ref(null)
const togglingIds = ref(new Set())

const nextUpId = computed(() => {
    const next = props.pieces.find(p => !p.done)
    return next ? next.id : null
})
const completedCount = computed(() => props.pieces.filter(p => p.done).length)
const progressPercent = computed(() => props.pieces.length ? (completedCount.value / props.pieces.length) * 100 : 0)
const allDone = computed(() => props.sessionFinished || (props.pieces.length > 0 && completedCount.value === props.pieces.length))

async function toggle(piece) {
    if (props.disabled || !props.sessionId || togglingIds.value.has(piece.id)) return
    togglingIds.value.add(piece.id)
    try {
        const data = await api.post(`/api/dashboard/sessions/${props.sessionId}/toggle-piece`, {
            piece_id: piece.id,
        })
        emit('piece-toggled', {
            pieceId: data.piece_id,
            completed: data.completed,
            piecesCompleted: data.pieces_completed,
        })
    } finally {
        togglingIds.value.delete(piece.id)
    }
}

function toggleDoneById(id) {
    const piece = props.pieces.find(p => p.id === id)
    if (piece) toggle(piece)
}
</script>
