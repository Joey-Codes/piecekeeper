<template>
    <Teleport to="body">
        <Transition name="viewer">
            <div
                v-if="files && files.length && currentIndex >= 0"
                class="fixed inset-0 z-[60] flex flex-col bg-stone-900"
                @keydown.left="prev"
                @keydown.right="next"
                @keydown.escape="$emit('close')"
                tabindex="0"
                ref="viewerEl"
            >
                <!-- Top bar -->
                <div class="flex items-center justify-between px-5 py-3 bg-stone-900/95 border-b border-stone-700/50">
                    <div class="flex items-center gap-2 min-w-0">
                        <svg
                            class="w-4 h-4 text-red-400 shrink-0"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                        >
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z" />
                        </svg>
                        <span class="text-sm text-stone-300 truncate">{{ currentFile.name }}</span>
                        <span
                            v-if="currentFile.pages"
                            class="text-xs text-stone-500"
                        >{{ currentFile.pages }} pages</span>
                    </div>
                    <div class="flex items-center gap-4">
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
                <div class="flex-1 relative overflow-y-auto flex items-start justify-center py-8 px-16">
                    <!-- Nav: Previous -->
                    <button
                        v-if="files.length > 1"
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-stone-800/80 hover:bg-stone-700 flex items-center justify-center text-stone-400 hover:text-white transition-colors"
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

                    <!-- Fake PDF pages -->
                    <div class="w-full max-w-2xl space-y-4">
                        <div
                            v-for="page in pageCount"
                            :key="page"
                            class="bg-white rounded-lg shadow-lg p-8 space-y-3"
                        >
                            <!-- Page header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="h-3 bg-stone-300 rounded-full w-1/3" />
                                <span class="text-xs text-stone-300">{{ page }}</span>
                            </div>

                            <!-- Fake staff lines (music notation) -->
                            <div
                                v-for="staff in 4"
                                :key="staff"
                                class="py-2"
                            >
                                <div class="relative h-8 mb-3">
                                    <div
                                        v-for="line in 5"
                                        :key="line"
                                        class="absolute w-full border-t border-stone-200"
                                        :style="{ top: ((line - 1) * 25) + '%' }"
                                    />
                                    <!-- Fake notes scattered on staff -->
                                    <div class="absolute flex items-center gap-6 left-4 top-0 h-full">
                                        <div
                                            v-for="n in (5 + staff)"
                                            :key="n"
                                            class="w-2.5 h-2 bg-stone-400 rounded-full"
                                            :style="{ marginTop: ((n * 13 + staff * 7 + page * 3) % 24) + 'px' }"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom text lines -->
                            <div class="pt-2 space-y-2">
                                <div
                                    class="h-1.5 bg-stone-100 rounded-full"
                                    :style="{ width: (60 + (page * 11) % 30) + '%' }"
                                />
                                <div
                                    class="h-1.5 bg-stone-100 rounded-full"
                                    :style="{ width: (50 + (page * 7) % 40) + '%' }"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Nav: Next -->
                    <button
                        v-if="files.length > 1"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-stone-800/80 hover:bg-stone-700 flex items-center justify-center text-stone-400 hover:text-white transition-colors"
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
import { ref, computed, watch, nextTick } from 'vue'

const props = defineProps({
    files: { type: Array, default: () => [] },
    startIndex: { type: Number, default: 0 },
})

const emit = defineEmits(['close'])

const currentIndex = ref(props.startIndex)
const viewerEl = ref(null)

const currentFile = computed(() => props.files[currentIndex.value] || { name: '' })
const pageCount = computed(() => currentFile.value.pages || 3)

watch(() => props.startIndex, (val) => {
    currentIndex.value = val
})

watch(() => props.files, () => {
    if (props.files && props.files.length) {
        nextTick(() => viewerEl.value?.focus())
    }
})

watch(currentIndex, () => {
    nextTick(() => {
        const scrollArea = viewerEl.value?.querySelector('.overflow-y-auto')
        if (scrollArea) scrollArea.scrollTop = 0
    })
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
