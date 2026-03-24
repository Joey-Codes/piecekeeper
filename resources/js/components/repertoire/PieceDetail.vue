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
                <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto p-4 sm:p-6">
                    <!-- Title -->
                    <h3 class="text-lg sm:text-xl font-serif font-bold uppercase tracking-wide text-stone-800 text-center mb-4 sm:mb-5">
                        Piece Details
                    </h3>

                    <form
                        class="space-y-4"
                        @submit.prevent="handleSave"
                    >
                        <!-- Title field -->
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">
                                Title <span class="text-red-400">*</span>
                            </label>
                            <input
                                v-model="title"
                                type="text"
                                placeholder="e.g. Clair de Lune"
                                required
                                class="w-full px-3 py-2 text-sm bg-white/70 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-400"
                            >
                        </div>

                        <!-- Composer field -->
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">
                                Composer
                            </label>
                            <input
                                v-model="composer"
                                type="text"
                                placeholder="e.g. Debussy"
                                class="w-full px-3 py-2 text-sm bg-white/70 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-400"
                            >
                        </div>

                        <!-- Status -->
                        <div v-if="showStatus">
                            <label class="block text-sm font-semibold text-stone-700 mb-1">
                                Status
                            </label>
                            <select
                                v-model="status"
                                class="w-full px-3 py-2 text-sm bg-white/70 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 cursor-pointer"
                            >
                                <option value="Learning">
                                    Learning
                                </option>
                                <option value="Polishing">
                                    Polishing
                                </option>
                                <option value="Mastered">
                                    Mastered
                                </option>
                                <option value="Relearning">
                                    Relearning
                                </option>
                                <option value="Shelved">
                                    Shelved
                                </option>
                            </select>
                        </div>

                        <!-- Reference Links -->
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">
                                Reference Links
                                <span class="text-xs font-normal text-stone-400 ml-1">{{ links.filter(l => l.trim()).length }}/5</span>
                            </label>
                            <div class="space-y-2">
                                <div
                                    v-for="(_, i) in links"
                                    :key="i"
                                    class="relative flex items-center gap-1.5"
                                >
                                    <svg
                                        class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400 pointer-events-none"
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
                                    <input
                                        v-model="links[i]"
                                        type="text"
                                        placeholder="https://youtube.com/..."
                                        class="w-full pl-9 pr-3 py-2 text-sm bg-white/70 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-400"
                                    >
                                    <button
                                        v-if="links.length > 1"
                                        type="button"
                                        class="shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-stone-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                                        @click="removeLink(i)"
                                    >
                                        &times;
                                    </button>
                                </div>
                            </div>
                            <button
                                v-if="links.length < 5"
                                type="button"
                                class="mt-1.5 text-xs font-semibold text-amber-600 hover:text-amber-700 transition-colors"
                                @click="addLink"
                            >
                                + Add another link
                            </button>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">
                                Notes
                            </label>
                            <textarea
                                v-model="notes"
                                rows="3"
                                placeholder="Practice notes, performance tips, things to work on..."
                                class="w-full px-3 py-2 text-sm bg-white/70 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-400 resize-y"
                            />
                        </div>

                        <!-- Sheet Music -->
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-2">
                                Sheet Music
                                <span class="text-xs font-normal text-stone-400 ml-1">{{ files.length }}/5</span>
                            </label>

                            <!-- Existing file previews -->
                            <div
                                v-if="files.length"
                                class="grid grid-cols-2 gap-3 mb-3"
                            >
                                <div
                                    v-for="(file, i) in files"
                                    :key="i"
                                    class="group/file relative rounded-lg border border-stone-200 bg-stone-50 overflow-hidden cursor-pointer hover:border-amber-300 transition-colors"
                                    @click="openPdf(i)"
                                >
                                    <!-- PDF preview thumbnail -->
                                    <div class="aspect-3/4 flex flex-col items-center justify-center p-3 bg-white">
                                        <!-- Fake page lines -->
                                        <div class="w-full space-y-2 px-2">
                                            <div class="h-2.5 bg-stone-200 rounded-full w-3/4" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-full" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-full" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-5/6" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-full" />
                                            <div class="h-1.5 bg-stone-100 rounded-full w-2/3" />
                                            <div class="mt-3 flex justify-center">
                                                <svg
                                                    class="w-8 h-8 text-stone-300"
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
                                        </div>
                                    </div>

                                    <!-- File info bar -->
                                    <div class="px-2.5 py-2 border-t border-stone-200 flex items-center gap-1.5">
                                        <svg
                                            class="w-3.5 h-3.5 text-red-400 shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                        >
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z" />
                                        </svg>
                                        <span class="text-xs text-stone-600 truncate flex-1">{{ file.name }}</span>
                                        <span
                                            v-if="file.pages"
                                            class="text-[10px] text-stone-400"
                                        >{{ file.pages }}p</span>
                                    </div>

                                    <!-- Remove overlay -->
                                    <button
                                        type="button"
                                        class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-white/90 shadow-sm border border-stone-200 flex items-center justify-center opacity-0 group-hover/file:opacity-100 transition-opacity text-stone-400 hover:text-red-500 hover:border-red-200"
                                        title="Remove file"
                                        @click="removeFile(i)"
                                    >
                                        <svg
                                            class="w-3.5 h-3.5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Upload button -->
                            <label
                                class="flex items-center gap-2 px-3 py-2 text-sm border border-dashed border-stone-300 rounded-lg cursor-pointer hover:border-amber-400 hover:bg-amber-50/30 transition-colors"
                            >
                                <svg
                                    class="w-4 h-4 text-stone-400 shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                    />
                                </svg>
                                <span class="text-stone-500 truncate">
                                    {{ files.length ? 'Add more PDFs...' : 'Upload PDFs...' }}
                                </span>
                                <input
                                    ref="fileInput"
                                    type="file"
                                    accept=".pdf"
                                    multiple
                                    class="hidden"
                                    @change="handleFiles"
                                >
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 pt-2">
                            <button
                                type="button"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold text-stone-600 bg-stone-100 rounded-xl hover:bg-stone-200 transition-colors duration-150"
                                @click="close"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-linear-to-r from-amber-500 to-orange-400 rounded-xl hover:from-amber-600 hover:to-orange-500 hover:scale-[1.02] transition-all duration-150"
                            >
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </Teleport>

    <PdfViewer
        :files="pdfViewerOpen ? files : []"
        :start-index="pdfStartIndex"
        @close="pdfViewerOpen = false"
    />
</template>

<script setup>
import { ref, watch } from 'vue'
import api from '@/api'
import PdfViewer from '../ui/PdfViewer.vue'

const props = defineProps({
    piece: { type: Object, default: null },
    showStatus: { type: Boolean, default: true },
})

const emit = defineEmits(['save', 'close'])

const title = ref('')
const composer = ref('')
const status = ref('Learning')
const links = ref([''])
const notes = ref('')
const files = ref([])
const fileInput = ref(null)
const pdfViewerOpen = ref(false)
const pdfStartIndex = ref(0)

watch(() => props.piece, (p) => {
    if (p) {
        title.value = p.title || ''
        composer.value = p.composer || ''
        status.value = p.status || 'Learning'
        links.value = p.reference_links && p.reference_links.length ? [...p.reference_links] : ['']
        notes.value = p.notes || ''
        files.value = p.files ? [...p.files] : []
    }
}, { immediate: true })

function addLink() {
    if (links.value.length < 5) links.value.push('')
}

function removeLink(index) {
    links.value.splice(index, 1)
}

function openPdf(index) {
    pdfStartIndex.value = index
    pdfViewerOpen.value = true
}

function handleFiles(e) {
    const selected = Array.from(e.target.files)
    files.value.push(...selected)
    if (fileInput.value) fileInput.value.value = ''
}

function removeFile(index) {
    files.value.splice(index, 1)
}

async function handleSave() {
    if (!title.value.trim()) return

    const trimmedLinks = links.value.map(l => l.trim()).filter(l => l.length > 0)

    try {
        const response = await api.put(`/api/pieces/${props.piece.id}`, {
            title: title.value.trim(),
            composer: composer.value.trim() || null,
            status: status.value,
            reference_links: trimmedLinks.length > 0 ? trimmedLinks : null,
            notes: notes.value.trim() || null,
        })

        emit('save', response.data)
    } catch (e) {
        console.error('Failed to update piece:', e)
    }
}

function close() {
    emit('close')
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
