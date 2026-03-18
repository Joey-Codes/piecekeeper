<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="open"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @mousedown.self="cancel"
            >
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" />

                <!-- Modal panel -->
                <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-4 sm:p-6">
                    <!-- Title -->
                    <h3 class="text-lg sm:text-xl font-serif font-bold uppercase tracking-wide text-stone-800 text-center mb-4 sm:mb-5">
                        {{ modalTitle }}
                    </h3>

                    <form
                        class="space-y-4"
                        @submit.prevent="handleSubmit"
                    >
                        <!-- Title field -->
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">
                                Title <span class="text-red-400">*</span>
                            </label>
                            <input
                                ref="titleInput"
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

                        <!-- Reference Link -->
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">
                                Reference Link
                            </label>
                            <div class="relative">
                                <svg
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400"
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
                                    v-model="link"
                                    type="text"
                                    placeholder="https://youtube.com/..."
                                    class="w-full pl-9 pr-3 py-2 text-sm bg-white/70 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-400"
                                >
                            </div>
                        </div>

                        <!-- Sheet Music Upload -->
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">
                                Sheet Music
                            </label>
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
                                    {{ fileLabel }}
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

                            <!-- File chips -->
                            <div
                                v-if="files.length"
                                class="flex flex-wrap gap-2 mt-2"
                            >
                                <span
                                    v-for="(file, i) in files"
                                    :key="i"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium bg-amber-50 text-amber-700 rounded-md"
                                >
                                    <svg
                                        class="w-3 h-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                    {{ file.name }}
                                    <button
                                        type="button"
                                        class="ml-0.5 text-amber-400 hover:text-amber-600"
                                        @click="removeFile(i)"
                                    >
                                        &times;
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 pt-2">
                            <button
                                type="button"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold text-stone-600 bg-stone-100 rounded-xl hover:bg-stone-200 transition-colors duration-150"
                                @click="cancel"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-linear-to-r from-amber-500 to-orange-400 rounded-xl hover:from-amber-600 hover:to-orange-500 hover:scale-[1.02] transition-all duration-150"
                            >
                                {{ submitLabel }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, computed, watch, nextTick } from 'vue'

const props = defineProps({
    open: { type: Boolean, default: false },
    modalTitle: { type: String, default: 'Add Piece' },
    submitLabel: { type: String, default: 'Add Piece' },
})

const emit = defineEmits(['submit', 'close'])

const title = ref('')
const composer = ref('')
const link = ref('')
const files = ref([])
const titleInput = ref(null)
const fileInput = ref(null)

const fileLabel = computed(() => {
    if (files.value.length === 0) return 'Upload PDFs...'
    return `${files.value.length} file${files.value.length > 1 ? 's' : ''} selected`
})

watch(() => props.open, (isOpen) => {
    if (isOpen) {
        nextTick(() => titleInput.value?.focus())
    }
})

function handleFiles(e) {
    const selected = Array.from(e.target.files)
    files.value.push(...selected)
    if (fileInput.value) fileInput.value.value = ''
}

function removeFile(index) {
    files.value.splice(index, 1)
}

function handleSubmit() {
    if (!title.value.trim()) return

    emit('submit', {
        title: title.value.trim(),
        composer: composer.value.trim() || null,
        link: link.value.trim() || null,
        files: [...files.value],
    })

    resetForm()
}

function cancel() {
    resetForm()
    emit('close')
}

function resetForm() {
    title.value = ''
    composer.value = ''
    link.value = ''
    files.value = []
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
