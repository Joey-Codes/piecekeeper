<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @mousedown.self="$emit('close')"
            >
                <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" />

                <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                    <button
                        class="absolute top-4 right-4 text-stone-400 hover:text-stone-600 transition-colors"
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

                    <h3 class="text-xl font-serif font-bold uppercase tracking-wide text-stone-800 text-center mb-5">
                        What Each Status Means
                    </h3>

                    <ul class="space-y-3">
                        <li
                            v-for="status in statuses"
                            :key="status.name"
                            class="flex items-start gap-3"
                        >
                            <span
                                class="w-2 h-2 rounded-full shrink-0 mt-1.5"
                                :class="status.color"
                            />
                            <div>
                                <span class="text-sm font-semibold text-stone-800">{{ status.name }}</span>
                                <p class="text-sm text-stone-500 mt-0.5">
                                    {{ status.description }}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
defineProps({
    show: { type: Boolean, default: false },
})

defineEmits(['close'])

const statuses = [
    {
        name: 'Learning',
        color: 'bg-blue-400',
        description: 'A piece you are currently learning for the first time.',
    },
    {
        name: 'Polishing',
        color: 'bg-amber-400',
        description: 'You know the piece but are refining it to performance level.',
    },
    {
        name: 'Mastered',
        color: 'bg-emerald-400',
        description: 'The piece is performance-ready. You can play it confidently and consistently from memory or sheet music.',
    },
    {
        name: 'Relearning',
        color: 'bg-violet-400',
        description: 'A piece you previously knew but are picking back up after a break.',
    },
    {
        name: 'Shelved',
        color: 'bg-stone-400',
        description: 'A piece you\'ve set aside for now or may have forgotten how to play. It won\'t appear in your practice rotation or schedule until made active again.',
    },
]
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
