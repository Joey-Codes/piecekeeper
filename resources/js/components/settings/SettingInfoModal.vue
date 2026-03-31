<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @mousedown.self="$emit('close')"
            >
                <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" />

                <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 sm:p-8">
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

                    <div class="flex justify-center mb-4">
                        <div class="w-10 h-10 rounded-xl bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-sm">
                            <svg
                                class="w-5 h-5 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"
                                />
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-lg sm:text-xl font-serif font-bold uppercase tracking-wide text-stone-800 text-center mb-4">
                        {{ title }}
                    </h3>

                    <p class="text-sm sm:text-base text-stone-600 leading-relaxed">
                        {{ description }}
                    </p>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, required: true },
    description: { type: String, required: true },
})

defineEmits(['close'])
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
