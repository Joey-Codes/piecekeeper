<template>
    <div>
        <!-- Action buttons -->
        <div class="flex justify-center gap-3 mb-4 sm:mb-6">
            <button
                class="px-5 sm:px-6 py-2 sm:py-2.5 text-sm sm:text-base font-semibold text-stone-600 bg-white border border-stone-200 rounded-xl shadow-sm hover:bg-stone-50 hover:shadow-md transition-all duration-200"
                :disabled="saving"
                @click="$emit('cancel')"
            >
                Cancel
            </button>
            <button
                class="px-5 sm:px-6 py-2 sm:py-2.5 text-sm font-semibold text-white bg-linear-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg shadow-amber-300/30 hover:shadow-xl hover:shadow-amber-300/40 hover:scale-[1.03] hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:pointer-events-none"
                :disabled="saving"
                @click="save"
            >
                {{ saving ? 'Saving...' : 'Save Order' }}
            </button>
        </div>

        <div class="flex items-start gap-3 bg-white border border-amber-200 rounded-xl px-4 py-3 mb-4 sm:mb-6 shadow-sm">
            <svg
                class="w-5 h-5 text-amber-500 shrink-0 mt-0.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            <div class="text-sm">
                <p class="font-semibold text-stone-700">
                    Drag and drop pieces to change your practice rotation order. This is the sequence in which your pieces will be listed during practice sessions.
                </p>
                <p class="font-medium text-stone-500 mt-1">
                    Note: Shelved pieces are not included in this list.
                </p>
            </div>
        </div>

        <!-- Draggable list -->
        <section class="card overflow-hidden hover:shadow-lg transition-shadow duration-300 border-orange-200">
            <draggable
                v-model:list="localPieces"
                item-key="id"
                handle=".drag-handle"
                :animation="200"
                ghost-class="opacity-30"
                tag="ul"
                class="divide-y divide-stone-100"
            >
                <template #item="{ element }">
                    <li class="pl-2 pr-3 sm:px-6 py-3 sm:py-4 flex items-center gap-2 sm:gap-4 bg-white hover:bg-amber-50/40 transition-colors duration-200">
                        <!-- Drag handle -->
                        <span class="drag-handle cursor-grab active:cursor-grabbing p-1 text-stone-300 hover:text-stone-500 transition-colors">
                            <svg
                                class="w-5 h-5"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    cx="9"
                                    cy="5"
                                    r="1.5"
                                />
                                <circle
                                    cx="15"
                                    cy="5"
                                    r="1.5"
                                />
                                <circle
                                    cx="9"
                                    cy="12"
                                    r="1.5"
                                />
                                <circle
                                    cx="15"
                                    cy="12"
                                    r="1.5"
                                />
                                <circle
                                    cx="9"
                                    cy="19"
                                    r="1.5"
                                />
                                <circle
                                    cx="15"
                                    cy="19"
                                    r="1.5"
                                />
                            </svg>
                        </span>

                        <!-- Status bar -->
                        <div
                            class="w-1 self-stretch rounded-full shrink-0"
                            :class="statusBarClass(element.status)"
                        />

                        <!-- Piece info -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm sm:text-md font-semibold text-stone-800 truncate">
                                {{ element.title }}
                            </p>
                            <p
                                v-if="element.composer"
                                class="text-xs sm:text-sm text-stone-700 mt-0.5"
                            >
                                {{ element.composer }}
                            </p>
                        </div>

                        <!-- Status badge (read-only) -->
                        <span
                            class="text-xs sm:text-sm font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-lg border"
                            :class="statusClass(element.status)"
                        >
                            {{ element.status }}
                        </span>
                    </li>
                </template>
            </draggable>
        </section>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import draggable from 'vuedraggable'

const props = defineProps({
    pieces: {
        type: Array,
        required: true,
    },
})

const emit = defineEmits(['save', 'cancel'])

const localPieces = ref([])
const saving = ref(false)

watch(() => props.pieces, (val) => {
    localPieces.value = val.map(p => ({ ...p }))
}, { immediate: true })

function save() {
    saving.value = true
    emit('save', localPieces.value.map(p => p.id))
}

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

function statusClass(status) {
    const classes = {
        Learning: 'text-blue-700 bg-blue-50 border-blue-200/60',
        Polishing: 'text-amber-700 bg-amber-50 border-amber-200/60',
        Mastered: 'text-emerald-700 bg-emerald-50 border-emerald-200/60',
        Relearning: 'text-violet-700 bg-violet-50 border-violet-200/60',
        Shelved: 'text-stone-600 bg-stone-100 border-stone-200/60',
    }
    return classes[status] || ''
}
</script>
