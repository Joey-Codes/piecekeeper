<template>
    <Teleport to="body">
        <Transition name="viewer">
            <div
                v-if="files && files.length && currentIndex >= 0"
                ref="viewerEl"
                class="fixed inset-0 z-[60] flex flex-col bg-stone-900"
                tabindex="0"
                @keydown.left="prev"
                @keydown.right="next"
                @keydown.escape="$emit('close')"
            >
                <!-- Top bar -->
                <div class="grid grid-cols-3 items-center px-5 py-3 bg-stone-900/95 border-b border-stone-700/50">
                    <!-- Left: file info -->
                    <div class="flex items-center gap-2 min-w-0">
                        <svg
                            class="w-4 h-4 text-red-400 shrink-0"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                        >
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z" />
                        </svg>
                        <span class="text-sm text-stone-300 truncate">{{ currentFile?.name || '' }}</span>
                        <span
                            v-if="totalPages > 0"
                            class="text-xs text-stone-500 shrink-0"
                        >{{ totalPages }} {{ totalPages === 1 ? 'page' : 'pages' }}</span>
                    </div>

                    <!-- Center: zoom controls -->
                    <div class="flex items-center justify-center gap-2">
                        <button
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-sm text-stone-400 hover:text-white hover:bg-stone-700 transition-colors font-medium"
                            title="Zoom out"
                            :disabled="zoom <= 0.5"
                            :class="{ 'opacity-30 pointer-events-none': zoom <= 0.5 }"
                            @click="zoomOut"
                        >
                            &minus;
                        </button>
                        <span class="text-sm text-stone-400 w-12 text-center select-none">{{ Math.round(zoom * 100) }}%</span>
                        <button
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-sm text-stone-400 hover:text-white hover:bg-stone-700 transition-colors font-medium"
                            title="Zoom in"
                            :disabled="zoom >= 3"
                            :class="{ 'opacity-30 pointer-events-none': zoom >= 3 }"
                            @click="zoomIn"
                        >
                            +
                        </button>
                    </div>

                    <!-- Right: file counter + close -->
                    <div class="flex items-center justify-end gap-4">
                        <span
                            v-if="files.length > 1"
                            class="text-sm text-stone-400"
                        >{{ currentIndex + 1 }} / {{ files.length }}</span>
                        <button
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-stone-400 hover:text-white hover:bg-stone-700 transition-colors"
                            title="Close"
                            @click="$emit('close')"
                        >
                            <svg
                                class="w-5 h-5"
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

                <!-- PDF content area -->
                <div
                    ref="scrollArea"
                    class="flex-1 relative overflow-y-auto flex items-start justify-center py-4 px-4 sm:px-8"
                >
                    <!-- Nav: Previous -->
                    <button
                        v-if="files.length > 1"
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-stone-800/80 hover:bg-stone-700 flex items-center justify-center text-stone-400 hover:text-white transition-colors z-10"
                        title="Previous PDF"
                        @click="prev"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </button>

                    <!-- Loading spinner -->
                    <div
                        v-if="loading"
                        class="flex flex-col items-center justify-center py-24 gap-3"
                    >
                        <div class="w-8 h-8 border-2 border-stone-600 border-t-amber-400 rounded-full animate-spin" />
                        <span class="text-sm text-stone-500">Loading PDF...</span>
                    </div>

                    <!-- Error state -->
                    <div
                        v-else-if="error"
                        class="flex flex-col items-center justify-center py-24 gap-3"
                    >
                        <span class="text-sm text-red-400">{{ error }}</span>
                    </div>

                    <!-- Rendered pages -->
                    <div
                        v-else
                        ref="pagesContainer"
                        class="flex flex-col items-center space-y-4"
                    >
                        <canvas
                            v-for="page in totalPages"
                            :key="`${currentIndex}-${page}`"
                            :ref="el => setCanvasRef(page, el)"
                            class="rounded-lg shadow-lg"
                        />
                    </div>

                    <!-- Nav: Next -->
                    <button
                        v-if="files.length > 1"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-stone-800/80 hover:bg-stone-700 flex items-center justify-center text-stone-400 hover:text-white transition-colors z-10"
                        title="Next PDF"
                        @click="next"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Bottom dot indicators -->
                <div
                    v-if="files.length > 1"
                    class="flex items-center justify-center gap-2 py-3 bg-stone-900/95 border-t border-stone-700/50"
                >
                    <button
                        v-for="(file, i) in files"
                        :key="i"
                        class="w-2 h-2 rounded-full transition-all duration-200"
                        :class="i === currentIndex ? 'bg-amber-400 scale-125' : 'bg-stone-600 hover:bg-stone-500'"
                        :title="file.name"
                        @click="currentIndex = i"
                    />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, computed, watch, nextTick, onBeforeUnmount } from 'vue'
import * as pdfjsLib from 'pdfjs-dist'

pdfjsLib.GlobalWorkerOptions.workerSrc = new URL(
    'pdfjs-dist/build/pdf.worker.mjs',
    import.meta.url
).href

const props = defineProps({
    files: { type: Array, default: () => [] },
    startIndex: { type: Number, default: 0 },
})

const emit = defineEmits(['close'])

const currentIndex = ref(props.startIndex)
const viewerEl = ref(null)
const scrollArea = ref(null)
const loading = ref(false)
const error = ref(null)
const totalPages = ref(0)
const zoom = ref(0.75)
const canvasRefs = {}

let currentPdf = null
let loadGeneration = 0

const currentFile = computed(() => props.files[currentIndex.value] || null)

function setCanvasRef(page, el) {
    if (el) canvasRefs[page] = el
}

function zoomIn() {
    if (zoom.value >= 3) return
    zoom.value = Math.round((zoom.value + 0.25) * 100) / 100
    rerender()
}

function zoomOut() {
    if (zoom.value <= 0.5) return
    zoom.value = Math.round((zoom.value - 0.25) * 100) / 100
    rerender()
}

async function rerender() {
    if (!currentPdf) return
    await nextTick()
    await renderAllPages(currentPdf)
}

async function loadPdf(file) {
    if (!file) return

    const generation = ++loadGeneration

    zoom.value = 0.75
    loading.value = true
    error.value = null
    totalPages.value = 0

    if (currentPdf) {
        currentPdf.destroy()
        currentPdf = null
    }

    try {
        const source = file.url || file.objectUrl
        if (!source) {
            error.value = 'No URL available for this PDF.'
            loading.value = false
            return
        }

        const pdf = await pdfjsLib.getDocument(source).promise
        if (generation !== loadGeneration) return

        currentPdf = pdf
        totalPages.value = pdf.numPages
        loading.value = false

        await nextTick()
        if (generation !== loadGeneration) return

        await renderAllPages(pdf)
    } catch (e) {
        if (generation !== loadGeneration) return
        console.error('Failed to load PDF:', e)
        error.value = 'Failed to load PDF.'
        loading.value = false
    }
}

async function renderAllPages(pdf) {
    const containerWidth = scrollArea.value?.clientWidth
        ? scrollArea.value.clientWidth - 64
        : 768

    for (let i = 1; i <= pdf.numPages; i++) {
        const page = await pdf.getPage(i)
        const canvas = canvasRefs[i]
        if (!canvas) continue

        const viewport = page.getViewport({ scale: 1 })
        const scale = (containerWidth * window.devicePixelRatio * zoom.value) / viewport.width
        const scaledViewport = page.getViewport({ scale })

        canvas.width = scaledViewport.width
        canvas.height = scaledViewport.height
        canvas.style.width = `${scaledViewport.width / window.devicePixelRatio}px`
        canvas.style.height = `${scaledViewport.height / window.devicePixelRatio}px`

        const ctx = canvas.getContext('2d')
        await page.render({ canvasContext: ctx, viewport: scaledViewport }).promise
    }
}

watch(() => props.startIndex, (val) => {
    currentIndex.value = val
})

watch(() => props.files, (newFiles) => {
    if (newFiles && newFiles.length) {
        currentIndex.value = props.startIndex
        nextTick(() => viewerEl.value?.focus())
        loadPdf(newFiles[currentIndex.value])
    }
})

watch(currentIndex, () => {
    if (scrollArea.value) scrollArea.value.scrollTop = 0
    if (props.files[currentIndex.value]) {
        loadPdf(props.files[currentIndex.value])
    }
})

onBeforeUnmount(() => {
    if (currentPdf) {
        currentPdf.destroy()
        currentPdf = null
    }
})

function prev() {
    if (props.files.length <= 1) return
    currentIndex.value = (currentIndex.value - 1 + props.files.length) % props.files.length
}

function next() {
    if (props.files.length <= 1) return
    currentIndex.value = (currentIndex.value + 1) % props.files.length
}
</script>

<style scoped>
.viewer-enter-active,
.viewer-leave-active {
  transition: opacity 0.2s ease;
}
.viewer-enter-from,
.viewer-leave-to {
  opacity: 0;
}
</style>
