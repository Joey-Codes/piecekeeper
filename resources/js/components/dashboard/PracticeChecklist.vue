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
            v-if="disabled"
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

        <!-- Piece list -->
        <div class="p-3 sm:p-4 space-y-2 sm:space-y-3">
            <div
                v-for="(piece, index) in pieces"
                :key="piece.id"
                class="flex items-center gap-2.5 sm:gap-3 p-2.5 sm:p-3 rounded-xl transition-all duration-300 cursor-pointer"
                :class="piece.done ? 'bg-emerald-50 border border-emerald-100' : 'bg-stone-50 border border-stone-100 hover:border-stone-200'"
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
                        class="text-xs sm:text-sm font-semibold transition-all duration-300 truncate"
                        :class="piece.done ? 'text-emerald-700' : 'text-stone-700'"
                    >
                        {{ piece.title }}
                    </p>
                    <p
                        class="text-xs mt-0.5 transition-all duration-300"
                        :class="piece.done ? 'text-emerald-500' : 'text-stone-400'"
                    >
                        {{ piece.composer }}
                    </p>
                </div>

                <!-- Done / Up next tag -->
                <div
                    v-if="piece.done"
                    class="text-xs px-2 py-1 rounded-full font-medium whitespace-nowrap bg-emerald-100 text-emerald-600"
                >
                    Done
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
import TrebleClefCheck from '../ui/TrebleClefCheck.vue'
import PieceViewModal from '../repertoire/PieceViewModal.vue'

const props = defineProps({
    disabled: { type: Boolean, default: false },
})

const selectedPiece = ref(null)

const pieces = ref([
    { id: 1, title: 'Clair de Lune', composer: 'Debussy', status: 'Polishing', done: false, links: ['https://youtube.com/watch?v=example1'], notes: "Play it slower", files: [{ name: 'Clair_de_Lune.pdf', pages: 6 }] },
    { id: 2, title: 'Nocturne Op. 9 No. 2', composer: 'Chopin', status: 'Learning', done: false, files: [{ name: 'Nocturne_Op9_No2.pdf', pages: 4 }, { name: 'Nocturne_Annotations.pdf', pages: 2 }] },
    { id: 3, title: 'Gymnopédie No. 1', composer: 'Satie', status: 'Learned', done: false, files: [] },
    { id: 4, title: 'Prelude in C Major', composer: 'Bach', status: 'Learned', done: false, files: [{ name: 'Prelude_C_Major_BWV846.pdf', pages: 3 }] },
    { id: 5, title: 'Moonlight Sonata, Mvt. 1', composer: 'Beethoven', status: 'Learning', done: false, files: [] },
])

const nextUpId = computed(() => {
    const next = pieces.value.find(p => !p.done)
    return next ? next.id : null
})
const completedCount = computed(() => pieces.value.filter(p => p.done).length)
const progressPercent = computed(() => (completedCount.value / pieces.value.length) * 100)
const allDone = computed(() => completedCount.value === pieces.value.length)    

function toggle(piece) {
    if (props.disabled) return
    piece.done = !piece.done
}   

function toggleDoneById(id) {
    const piece = pieces.value.find(p => p.id === id)
    if (piece) toggle(piece)
}


</script>
