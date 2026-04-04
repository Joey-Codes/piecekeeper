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
                                maxlength="100"
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
                                maxlength="100"
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
                                <span class="text-xs font-normal text-stone-600 ml-1">{{ links.filter(l => l.trim()).length }}/5</span>
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
                                <span class="text-xs font-normal text-stone-600 ml-1">{{ totalFileCount }}/5</span>
                            </label>

                            <!-- File list -->
                            <div
                                v-if="existingFiles.length || newFiles.length"
                                class="space-y-2 mb-3"
                            >
                                <!-- Existing (uploaded) files -->
                                <div
                                    v-for="(file, i) in existingFiles"
                                    :key="'existing-' + i"
                                    class="group/file flex items-center gap-2 px-3 py-2 rounded-lg border border-amber-400 bg-stone-50 cursor-pointer hover:border-amber-300 transition-colors"
                                    @click="openExistingPdf(i)"
                                >
                                    <svg
                                        class="w-4 h-4 text-amber-600 shrink-0"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                    >
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z" />
                                    </svg>
                                    <span class="text-sm text-stone-700 truncate flex-1">{{ file.name }}</span>
                                    <button
                                        type="button"
                                        class="shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-stone-400 hover:text-red-500 hover:bg-red-50 transition-colors sm:opacity-0 sm:group-hover/file:opacity-100"
                                        title="Remove file"
                                        @click.stop="removeExistingFile(i)"
                                    >
                                        &times;
                                    </button>
                                </div>

                                <!-- New (local) files -->
                                <div
                                    v-for="(file, i) in newFiles"
                                    :key="'new-' + i"
                                    class="group/file flex items-center gap-2 px-3 py-2 rounded-lg border border-dashed border-amber-300 bg-amber-50/30 cursor-pointer hover:border-amber-400 transition-colors"
                                    @click="openNewPdf(i)"
                                >
                                    <svg
                                        class="w-4 h-4 text-amber-400 shrink-0"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                    >
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z" />
                                    </svg>
                                    <span class="text-sm text-amber-700 truncate flex-1">{{ file.name }}</span>
                                    <span class="text-[10px] font-medium text-amber-500 bg-amber-100 px-1.5 py-0.5 rounded">NEW</span>
                                    <button
                                        type="button"
                                        class="shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-stone-400 hover:text-red-500 hover:bg-red-50 transition-colors opacity-0 group-hover/file:opacity-100"
                                        title="Remove file"
                                        @click.stop="removeNewFile(i)"
                                    >
                                        &times;
                                    </button>
                                </div>
                            </div>

                            <!-- Upload button -->
                            <label
                                v-if="totalFileCount < 5"
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
                                    {{ totalFileCount > 0 ? 'Add more PDFs...' : 'Upload PDFs...' }}
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
                                :disabled="saving"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-linear-to-r from-amber-500 to-orange-400 rounded-xl hover:from-amber-600 hover:to-orange-500 hover:scale-[1.02] transition-all duration-150 disabled:opacity-60 disabled:pointer-events-none"
                            >
                                {{ saving ? 'Saving...' : 'Save Changes' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </Teleport>

    <PdfViewer
        :files="pdfViewerFiles"
        :start-index="pdfStartIndex"
        @close="pdfViewerOpen = false"
    />

    <ConfirmModal
        :show="showConfirmDelete"
        title="Remove File?"
        :message="`Remove <strong>&quot;${fileToDelete?.name ?? ''}&quot;</strong>? This cannot be undone.`"
        confirm-label="Remove"
        @confirm="confirmDeleteFile"
        @cancel="showConfirmDelete = false"
    />
</template>

<script setup>
import { ref, computed, watch, onBeforeUnmount } from 'vue'
import api from '@/api'
import PdfViewer from '../ui/PdfViewer.vue'
import ConfirmModal from '../ui/ConfirmModal.vue'

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
const existingFiles = ref([])
const newFiles = ref([])
const fileInput = ref(null)
const pdfViewerOpen = ref(false)
const pdfStartIndex = ref(0)
const pdfViewerMode = ref('existing')
const saving = ref(false)
const showConfirmDelete = ref(false)
const fileToDelete = ref(null)

const totalFileCount = computed(() => existingFiles.value.length + newFiles.value.length)

let objectUrls = []
const pdfViewerFiles = computed(() => {
    objectUrls.forEach(u => URL.revokeObjectURL(u))
    objectUrls = []
    if (!pdfViewerOpen.value) return []
    if (pdfViewerMode.value === 'existing') {
        return existingFiles.value.map(f => ({ name: f.name, url: f.url }))
    }
    return newFiles.value.map(f => {
        const objectUrl = URL.createObjectURL(f)
        objectUrls.push(objectUrl)
        return { name: f.name, objectUrl }
    })
})

onBeforeUnmount(() => {
    objectUrls.forEach(u => URL.revokeObjectURL(u))
})

watch(() => props.piece, (p) => {
    if (p) {
        title.value = p.title || ''
        composer.value = p.composer || ''
        status.value = p.status || 'Learning'
        links.value = p.reference_links && p.reference_links.length ? [...p.reference_links] : ['']
        notes.value = p.notes || ''
        existingFiles.value = p.sheet_music ? [...p.sheet_music] : []
        newFiles.value = []
    }
}, { immediate: true })

function addLink() {
    if (links.value.length < 5) links.value.push('')
}

function removeLink(index) {
    links.value.splice(index, 1)
}

function openExistingPdf(index) {
    pdfViewerMode.value = 'existing'
    pdfStartIndex.value = index
    pdfViewerOpen.value = true
}

function openNewPdf(index) {
    pdfViewerMode.value = 'new'
    pdfStartIndex.value = index
    pdfViewerOpen.value = true
}

function handleFiles(e) {
    const selected = Array.from(e.target.files)
    const remaining = 5 - totalFileCount.value
    newFiles.value.push(...selected.slice(0, remaining))
    if (fileInput.value) fileInput.value.value = ''
}

function removeExistingFile(index) {
    fileToDelete.value = existingFiles.value[index]
    showConfirmDelete.value = true
}

async function confirmDeleteFile() {
    showConfirmDelete.value = false
    try {
        await api.delete(`/api/pieces/${props.piece.id}/sheet-music`, { path: fileToDelete.value.path })
        const idx = existingFiles.value.findIndex(f => f.path === fileToDelete.value.path)
        if (idx !== -1) existingFiles.value.splice(idx, 1)
    } catch (e) {
        console.error('Failed to delete file:', e)
    }
    fileToDelete.value = null
}

function removeNewFile(index) {
    newFiles.value.splice(index, 1)
}

async function handleSave() {
    if (!title.value.trim()) return

    const trimmedLinks = links.value.map(l => l.trim()).filter(l => l.length > 0)

    saving.value = true
    try {
        let response = await api.put(`/api/pieces/${props.piece.id}`, {
            title: title.value.trim(),
            composer: composer.value.trim() || null,
            status: status.value,
            reference_links: trimmedLinks.length > 0 ? trimmedLinks : null,
            notes: notes.value.trim() || null,
        })

        if (newFiles.value.length > 0) {
            const formData = new FormData()
            newFiles.value.forEach(f => formData.append('files[]', f))
            try {
                response = await api.upload(`/api/pieces/${props.piece.id}/sheet-music`, formData)
            } catch (uploadErr) {
                console.error('PDF upload failed:', uploadErr)
            }
        }

        emit('save', response.data)
    } catch (e) {
        console.error('Failed to update piece:', e)
    } finally {
        saving.value = false
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
