<template>
    <section
        class="card overflow-hidden relative mb-6 sm:mb-8 hover:shadow-lg transition-shadow duration-300"
        :class="allDone ? 'border-emerald-200' : 'border-orange-200'"
    >
        <!-- Card header -->
        <div class="px-5 sm:px-6 py-5 sm:py-5 border-b border-stone-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
            <div class="flex items-center gap-2.5 sm:gap-3">
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center shadow-sm shrink-0">
                    <span class="text-white text-xs sm:text-sm">&#9835;</span>
                </div>
                <h2 class="text-base sm:text-xl font-serif font-semibold uppercase text-stone-800">
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
                <button
                    v-if="!disabled && !allDone && pieces.length > 0 && !markingAll"
                    class="text-xs px-3 py-1.5 rounded-xl font-semibold whitespace-nowrap bg-linear-to-r from-emerald-500 to-green-500 text-white shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-200"
                    @click="showMarkAllConfirm = true"
                >
                    Mark all done
                </button>
                <span
                    v-if="markingAll"
                    class="text-xs px-2.5 py-1.5 text-stone-400 whitespace-nowrap"
                >
                    Marking...
                </span>
            </div>
        </div>

        <!-- Disabled hint banner -->
        <div
            v-if="disabled && !sessionFinished"
            class="px-5 sm:px-6 py-3 sm:py-2.5 bg-stone-50 border-b border-stone-200 flex items-center gap-2.5"
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
            <span class="text-sm sm:text-base text-stone-600">Press <strong class="text-amber-700">Start Practice</strong> above to check off pieces</span>
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
            class="divide-y divide-stone-100"
        >
            <div
                v-for="(piece, index) in pieces"
                :key="piece.id"
                class="flex items-center gap-3 sm:gap-4 pl-4 pr-5 sm:pl-5 sm:pr-7 py-5 sm:py-5 transition-colors duration-200 cursor-pointer"
                :class="piece.done
                    ? 'bg-stone-50/60'
                    : sessionFinished
                        ? 'opacity-50'
                        : 'hover:bg-stone-50'"
                @click="selectedPiece = piece"
            >
                <!-- Status color bar -->
                <div
                    class="w-1 self-stretch rounded-full shrink-0 transition-colors duration-200"
                    :class="statusBarClass(piece.status)"
                />

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
                        class="text-sm sm:text-base font-semibold transition-colors duration-200 truncate"
                        :class="piece.done ? 'text-emerald-700' : 'text-stone-800'"
                    >
                        {{ piece.title }}
                    </p>
                    <p
                        class="text-xs sm:text-sm mt-0.5 transition-colors duration-200"
                        :class="piece.done ? 'text-emerald-500' : 'text-stone-700'"
                    >
                        {{ piece.composer }}
                    </p>
                </div>

                <!-- Status tag -->
                <span
                    v-if="piece.done"
                    class="text-sm px-3 py-1 rounded-full font-semibold whitespace-nowrap bg-emerald-100 text-emerald-600"
                >
                    Done
                </span>
                <span
                    v-else-if="sessionFinished"
                    class="text-sm px-3 py-1 rounded-full font-semibold whitespace-nowrap bg-stone-100 text-stone-400"
                >
                    Skipped
                </span>
                <span
                    v-else-if="piece.id === nextUpId"
                    class="text-sm px-3 py-1 rounded-full font-semibold whitespace-nowrap bg-amber-100 text-amber-600"
                >
                    Up next
                </span>
            </div>
        </div>

        <!-- Footer -->
        <div
            class="px-5 sm:px-6 py-3.5 sm:py-4 border-t border-stone-200 transition-colors duration-300"
            :class="allDone ? 'bg-emerald-400' : 'bg-linear-to-r from-amber-400 to-orange-300'"
        >
            <p
                v-if="allDone"
                class="text-sm sm:text-lg text-white font-semibold text-center"
            >
                All done for today — great work! &#127929;
            </p>
            <p
                v-else
                class="text-sm sm:text-lg font-semibold text-white text-center"
            >
                {{ pieces.length - completedCount }} {{ pieces.length - completedCount === 1 ? 'piece' : 'pieces' }} remaining
            </p>
        </div>
    </section>

    <ConfirmModal
        :show="showMarkAllConfirm"
        title="Mark all pieces as done?"
        :message="`This will complete all <strong>${pieces.length - completedCount}</strong> remaining ${pieces.length - completedCount === 1 ? 'piece' : 'pieces'}.`"
        confirm-label="Mark all done"
        confirm-class="bg-linear-to-r from-emerald-500 to-teal-500 hover:shadow-md"
        @confirm="showMarkAllConfirm = false; markAllDone()"
        @cancel="showMarkAllConfirm = false"
    />

    <PieceViewModal
        :piece="selectedPiece"
        :practice-active="!disabled"
        @toggle-done="toggleDoneById"
        @updated="onPieceUpdated"
        @close="selectedPiece = null"
    />
</template>

<script setup>
import { ref, computed } from 'vue'
import LoadingSpinner from '../ui/LoadingSpinner.vue'
import TrebleClefCheck from '../ui/TrebleClefCheck.vue'
import ConfirmModal from '../ui/ConfirmModal.vue'
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
const markingAll = ref(false)
const showMarkAllConfirm = ref(false)

function statusBarClass(status) {
    const classes = {
        Learning: 'bg-blue-400',
        Polishing: 'bg-amber-400',
        Mastered: 'bg-emerald-400',
        Relearning: 'bg-violet-400',
        Shelved: 'bg-stone-400',
    }
    return classes[status] || 'bg-amber-400'
}

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

async function markAllDone() {
    const uncompleted = props.pieces.filter(p => !p.done)
    if (!uncompleted.length || props.disabled || !props.sessionId) return
    markingAll.value = true
    try {
        for (const piece of uncompleted) {
            await toggle(piece)
        }
    } finally {
        markingAll.value = false
    }
}

function toggleDoneById(id) {
    const piece = props.pieces.find(p => p.id === id)
    if (piece) toggle(piece)
}

function onPieceUpdated(updated) {
    const piece = props.pieces.find(p => p.id === updated.id)
    if (piece) piece.status = updated.status
}
</script>
