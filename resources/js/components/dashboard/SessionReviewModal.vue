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
                <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-5 sm:p-6">
                    <!-- Header -->
                    <div class="text-center mb-5">
                        <div class="mx-auto w-12 h-12 rounded-xl bg-linear-to-br from-emerald-400 to-green-500 flex items-center justify-center shadow-sm mb-3">
                            <svg
                                class="w-6 h-6 text-white"
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
                        </div>
                        <h3 class="text-xl font-serif font-bold uppercase tracking-wide text-stone-800">
                            Session Complete
                        </h3>
                        <div
                            v-if="!editing"
                            class="flex items-center justify-center gap-1.5 mt-1"
                        >
                            <span class="text-base text-stone-600">
                                Something not right?
                            </span>
                            <button
                                class="text-base font-semibold text-amber-600 hover:text-amber-700 underline underline-offset-2 transition-colors"
                                @click="startEditing"
                            >
                                Edit Session
                            </button>
                        </div>
                        <div
                            v-if="!editing"
                            class="text-2xl font-bold text-stone-800 mt-2"
                        >
                            {{ formattedDuration }}
                        </div>
                        <!-- Editable duration -->
                        <div
                            v-else
                            class="mt-3 flex items-center justify-center gap-2"
                        >
                            <div class="flex items-center gap-1">
                                <input
                                    v-model.number="editHours"
                                    type="number"
                                    min="0"
                                    max="23"
                                    class="w-16 text-center text-xl font-bold text-stone-800 border border-stone-300 rounded-lg px-2 py-1 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                                >
                                <span class="text-sm text-stone-500">
                                    hr
                                </span>
                            </div>
                            <span class="text-xl font-bold text-stone-400">
                                :
                            </span>
                            <div class="flex items-center gap-1">
                                <input
                                    v-model.number="editMinutes"
                                    type="number"
                                    min="0"
                                    max="59"
                                    class="w-16 text-center text-xl font-bold text-stone-800 border border-stone-300 rounded-lg px-2 py-1 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                                >
                                <span class="text-sm text-stone-500">
                                    min
                                </span>
                            </div>
                            <span class="text-xl font-bold text-stone-400">
                                :
                            </span>
                            <div class="flex items-center gap-1">
                                <input
                                    v-model.number="editSeconds"
                                    type="number"
                                    min="0"
                                    max="59"
                                    class="w-16 text-center text-xl font-bold text-stone-800 border border-stone-300 rounded-lg px-2 py-1 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                                >
                                <span class="text-sm text-stone-500">
                                    sec
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Piece list -->
                    <div class="space-y-2 mb-5">
                        <div
                            v-for="piece in displayPieces"
                            :key="piece.id"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-150"
                            :class="[
                                piece.done
                                    ? 'bg-emerald-50 border border-emerald-100'
                                    : 'bg-stone-50 border border-stone-200',
                                editing ? 'cursor-pointer hover:shadow-sm' : ''
                            ]"
                            @click="editing && toggleEditPiece(piece.id)"
                        >
                            <!-- Check / dash icon -->
                            <div
                                class="w-5 h-5 rounded-full flex items-center justify-center shrink-0"
                                :class="piece.done
                                    ? 'bg-emerald-400'
                                    : 'bg-stone-300'"
                            >
                                <svg
                                    v-if="piece.done"
                                    class="w-3 h-3 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="3"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="w-3 h-3 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="3"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 12h14"
                                    />
                                </svg>
                            </div>

                            <!-- Piece title -->
                            <span
                                class="text-sm font-semibold truncate"
                                :class="piece.done ? 'text-emerald-700' : 'text-stone-400'"
                            >
                                {{ piece.title }}
                            </span>
                        </div>
                    </div>

                    <!-- Summary -->
                    <p class="text-center text-sm text-stone-800 mb-4">
                        {{ displayCompletedCount }}/{{ pieces.length }} pieces completed
                    </p>

                    <!-- Edit mode: Save / Cancel -->
                    <div
                        v-if="editing"
                        class="flex gap-3"
                    >
                        <button
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-stone-600 bg-stone-100 rounded-xl hover:bg-stone-200 transition-colors duration-150"
                            @click="cancelEditing"
                        >
                            Cancel
                        </button>
                        <button
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-linear-to-r from-amber-500 to-orange-500 rounded-xl hover:shadow-md hover:scale-[1.01] transition-all duration-150 disabled:opacity-50 disabled:pointer-events-none"
                            :disabled="saving"
                            @click="saveEdits"
                        >
                            {{ saving ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>

                    <!-- View mode: Close -->
                    <button
                        v-else
                        class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-linear-to-r from-emerald-500 to-green-500 rounded-xl hover:shadow-md hover:scale-[1.01] transition-all duration-150"
                        @click="$emit('close')"
                    >
                        Close
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import api from '@/api'

const props = defineProps({
    show: { type: Boolean, default: false },
    sessionId: { type: Number, default: null },
    pieces: { type: Array, default: () => [] },
    durationSeconds: { type: Number, default: 0 },
})

const emit = defineEmits(['close', 'updated'])

const editing = ref(false)
const editHours = ref(0)
const editMinutes = ref(0)
const editSeconds = ref(0)
const editPieces = ref([])

const displayPieces = computed(() => editing.value ? editPieces.value : props.pieces)
const displayCompletedCount = computed(() => displayPieces.value.filter(p => p.done).length)

function startEditing() {
    editHours.value = Math.floor(props.durationSeconds / 3600)
    editMinutes.value = Math.floor((props.durationSeconds % 3600) / 60)
    editSeconds.value = props.durationSeconds % 60
    editPieces.value = props.pieces.map(p => ({ ...p }))
    editing.value = true
}

function cancelEditing() {
    editing.value = false
}

function toggleEditPiece(id) {
    const piece = editPieces.value.find(p => p.id === id)
    if (piece) piece.done = !piece.done
}

const saving = ref(false)

async function saveEdits() {
    if (!props.sessionId || saving.value) return
    saving.value = true
    try {
        const durationSeconds = (editHours.value * 3600) + (editMinutes.value * 60) + editSeconds.value
        const data = await api.put(`/api/dashboard/sessions/${props.sessionId}`, {
            duration_seconds: durationSeconds,
            pieces: editPieces.value.map(p => ({
                id: p.id,
                completed: p.done,
            })),
        })
        editing.value = false
        emit('updated', data.session)
    } finally {
        saving.value = false
    }
}

function clamp(val, min, max) {
    return Math.max(min, Math.min(max, Math.floor(val || 0)))
}

watch(editHours, (v) => { editHours.value = clamp(v, 0, 23) })
watch(editMinutes, (v) => { editMinutes.value = clamp(v, 0, 59) })
watch(editSeconds, (v) => { editSeconds.value = clamp(v, 0, 59) })

const formattedDuration = computed(() => {
    const total = props.durationSeconds
    const hrs = Math.floor(total / 3600)
    const mins = Math.floor((total % 3600) / 60)
    const secs = total % 60
    const pad = (n) => String(n).padStart(2, '0')

    if (hrs > 0) return `${hrs}:${pad(mins)}:${pad(secs)}`
    return `${pad(mins)}:${pad(secs)}`
})
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
