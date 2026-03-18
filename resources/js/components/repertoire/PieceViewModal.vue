<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="piece"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @mousedown.self="close"
            >
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" />

                <!-- Modal panel -->
                <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-4 sm:p-6 max-h-[85vh] overflow-y-auto">
                    <!-- Title (read-only) -->
                    <h2 class="w-full text-xl sm:text-2xl font-serif font-bold uppercase tracking-wide text-stone-800 text-center px-2 py-1">
                        {{ piece.title }}
                    </h2>
                    <!-- Composer (read-only) -->
                    <p class="w-full text-base sm:text-lg text-stone-700 text-center mt-1 px-2 py-0.5">
                        {{ piece.composer }}
                    </p>

                    <!-- Details -->
                    <div class="mt-4 sm:mt-6 space-y-4 sm:space-y-5">
                        <!-- Status (editable) -->
                        <div
                            v-if="piece.status"
                            class="flex items-center gap-3"
                        >
                            <span class="text-base font-semibold text-stone-700">Status</span>
                            <select
                                v-model="status"
                                class="text-base font-semibold px-3 py-1 rounded-lg border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-amber-300/50"
                                :class="currentStatusClass"
                            >
                                <option value="learning">
                                    Learning
                                </option>
                                <option value="learned">
                                    Learned
                                </option>
                                <option value="polishing">
                                    Polishing
                                </option>
                                <option value="shelved">
                                    Shelved
                                </option>
                            </select>
                        </div>

                        <!-- Links (read-only) -->
                        <div v-if="piece.links && piece.links.length">
                            <span class="block text-base font-semibold text-stone-700 mb-1">Reference Links</span>
                            <div class="space-y-2">
                                <div
                                    v-for="(link, i) in piece.links"
                                    :key="i"
                                    class="flex items-center gap-2 px-3 py-2 bg-stone-50 rounded-lg"
                                >
                                    <svg
                                        class="w-5 h-5 text-stone-400 shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                        />
                                    </svg>
                                    <span class="text-base text-amber-700 truncate">{{ link }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes (editable) -->
                        <div>
                            <span class="block text-base font-semibold text-stone-700 mb-1">Notes</span>
                            <textarea
                                v-model="notes"
                                rows="3"
                                placeholder="Write down practice notes, performance tips, things to work on..."
                                class="w-full px-3 py-2 text-base text-stone-600 bg-stone-50 border border-stone-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-400 resize-y"
                            />
                        </div>

                        <!-- Sheet Music PDFs (read-only) -->
                        <div v-if="piece.files && piece.files.length">
                            <span class="block text-base font-semibold text-stone-700 mb-2">Sheet Music</span>
                            <div
                                class="grid gap-3"
                                :class="piece.files.length === 1 ? 'grid-cols-1 max-w-xs mx-auto' : 'grid-cols-2'"
                            >
                                <div
                                    v-for="(file, i) in piece.files"
                                    :key="i"
                                    class="rounded-lg border border-stone-200 bg-stone-50 overflow-hidden cursor-pointer hover:border-amber-300 hover:shadow-md transition-all"
                                    @click="openPdf(i)"
                                >
                                    <!-- PDF preview thumbnail -->
                                    <div class="aspect-3/4 flex flex-col items-center justify-center p-4 bg-white">
                                        <div class="w-full space-y-2 px-3">
                                            <div class="h-2.5 bg-stone-200 rounded-full w-3/4" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-full" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-full" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-5/6" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-full" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-2/3" />
                                            <div class="mt-4 flex justify-center">
                                                <svg
                                                    class="w-10 h-10 text-stone-300"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"
                                                    />
                                                </svg>
                                            </div>
                                            <div class="h-1.5 bg-stone-100 rounded-full w-full" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-4/5" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-full" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-3/5" />
                                        </div>
                                    </div>

                                    <!-- File info bar -->
                                    <div class="px-3 py-2 border-t border-stone-200 flex items-center gap-1.5">
                                        <svg
                                            class="w-3.5 h-3.5 text-red-400 shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                        >
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z" />
                                        </svg>
                                        <span class="text-sm text-stone-600 truncate flex-1">{{ file.name }}</span>
                                        <span
                                            v-if="file.pages"
                                            class="text-xs text-stone-400"
                                        >{{ file.pages }}p</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- No files message -->
                        <div
                            v-else
                            class="text-center py-3"
                        >
                            <p class="text-base text-stone-400 italic">
                                No sheet music uploaded
                            </p>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex gap-2.5 sm:gap-3 justify-center pt-4 sm:pt-6">
                        <button
                            class="px-6 sm:px-8 py-2 sm:py-2.5 text-sm sm:text-base font-semibold text-stone-600 bg-stone-100 rounded-xl hover:bg-stone-200 transition-colors duration-150"
                            @click="close"
                        >
                            Close
                        </button>
                        <button
                            v-if="practiceActive"
                            class="px-6 sm:px-8 py-2 sm:py-2.5 text-sm sm:text-base font-semibold rounded-xl transition-all duration-200"
                            :class="piece.done
                                ? 'text-white bg-red-500 hover:bg-red-600 hover:scale-[1.02]'
                                : 'text-white bg-emerald-500 hover:bg-emerald-600 hover:scale-[1.02]'"
                            @click="toggleAndClose"
                        >
                            {{ piece.done ? 'Mark as incomplete' : 'Mark as done' }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>

    <PdfViewer
        v-if="piece"
        :files="pdfViewerOpen ? piece.files : []"
        :start-index="pdfStartIndex"
        @close="pdfViewerOpen = false"
    />
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import PdfViewer from '../ui/PdfViewer.vue'

const props = defineProps({
    piece: { type: Object, default: null },
    practiceActive: { type: Boolean, default: false },
})

const emit = defineEmits(['close', 'toggle-done'])

const status = ref('learning')
const notes = ref('')
const pdfViewerOpen = ref(false)
const pdfStartIndex = ref(0)

watch(() => props.piece, (p) => {
    if (p) {
        status.value = p.status || 'learning'
        notes.value = p.notes || ''
    }
}, { immediate: true })

function openPdf(index) {
    pdfStartIndex.value = index
    pdfViewerOpen.value = true
}


function close() {
    emit('close')
}

function toggleAndClose() {
    emit('toggle-done', props.piece.id)
    close()
}

const currentStatusClass = computed(() => {
    const classes = {
        learning: 'text-blue-600 bg-blue-50',
        learned: 'text-emerald-600 bg-emerald-50',
        polishing: 'text-amber-600 bg-amber-50',
        shelved: 'text-stone-500 bg-stone-100',
    }
    return classes[status.value] || ''
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
