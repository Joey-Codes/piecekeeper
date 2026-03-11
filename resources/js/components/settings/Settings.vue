<template>
    <div class="min-h-screen bg-white">
        <div class="relative max-w-4xl mx-auto px-6 py-12">
            <header class="mb-10">
                <h1 class="text-3xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    <span class="text-amber-700">Settings</span>
                </h1>
                <p class="font-semibold text-stone-600 mt-1">
                    Customize your practice experience
                </p>
            </header>

            <!-- Practice Schedule -->
            <section class="card px-6 py-6 mb-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-sm">
                        <span class="text-white text-sm">&#128197;</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-serif font-bold uppercase tracking-wide text-stone-800">
                            Practice Schedule
                        </h2>
                        <p class="text-md font-semibold text-stone-500">
                            How often and how much you want to practice
                        </p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Pieces per day -->
                    <div>
                        <label class="block text-md font-semibold text-stone-600 mb-2">Pieces per session</label>
                        <div class="flex items-center gap-3">
                            <button
                                class="w-9 h-9 rounded-lg border border-stone-300 bg-white text-stone-500 hover:bg-stone-50 hover:text-stone-700 transition-colors flex items-center justify-center text-lg"
                                @click="piecesPerDay = Math.max(1, piecesPerDay - 1)"
                            >
                                &minus;
                            </button>
                            <span class="text-2xl font-bold text-stone-800 w-10 text-center">{{ piecesPerDay }}</span>
                            <button
                                class="w-9 h-9 rounded-lg border border-stone-300 bg-white text-stone-500 hover:bg-stone-50 hover:text-stone-700 transition-colors flex items-center justify-center text-lg"
                                @click="piecesPerDay = Math.min(20, piecesPerDay + 1)"
                            >
                                +
                            </button>
                            <span class="text-md font-semibold text-stone-600 ml-2">{{ piecesPerDay === 1 ? 'piece' : 'pieces' }}</span>
                        </div>
                    </div>

                    <!-- Practice frequency -->
                    <div>
                        <label class="block text-sm font-semibold text-stone-600 mb-2">Practice frequency</label>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="option in frequencyOptions"
                                :key="option.value"
                                class="px-4 py-2 text-sm font-semibold rounded-lg border transition-all duration-200"
                                :class="frequency === option.value
                                    ? 'border-amber-400 bg-amber-500 text-white shadow-sm'
                                    : 'border-stone-200 bg-white text-stone-700 hover:bg-stone-50 hover:text-stone-700'"
                                @click="frequency = option.value"
                            >
                                {{ option.label }}
                            </button>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="mt-2 px-4 py-3 bg-stone-50 rounded-xl border border-stone-200">
                        <p class="text-md font-semibold text-stone-600">
                            <span class="font-semibold uppercase text-amber-600">Your schedule:</span>
                            {{ piecesPerDay }} {{ piecesPerDay === 1 ? 'piece' : 'pieces' }} {{ frequencyLabel }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- Placeholder sections for future settings -->
            <section class="card px-6 py-6 mb-6 opacity-50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-linear-to-br from-stone-300 to-stone-400 flex items-center justify-center shadow-sm">
                        <span class="text-white text-sm">&#9881;</span>
                    </div>
                    <div>
                        <h2 class="text-base font-serif font-bold uppercase tracking-wide text-stone-800">
                            Preferences
                        </h2>
                        <p class="text-sm font-semibold text-stone-400">
                            Coming soon
                        </p>
                    </div>
                </div>
            </section>

            <section class="card px-6 py-6 opacity-50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-linear-to-br from-stone-300 to-stone-400 flex items-center justify-center shadow-sm">
                        <span class="text-white text-sm">&#128100;</span>
                    </div>
                    <div>
                        <h2 class="text-base font-serif font-bold uppercase tracking-wide text-stone-800">
                            Account
                        </h2>
                        <p class="text-sm font-semibold text-stone-400">
                            Coming soon
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const piecesPerDay = ref(3)
const frequency = ref('every_day')

const frequencyOptions = [
    { value: 'every_day', label: 'Every day' },
    { value: 'every_2_days', label: 'Every 2 days' },
    { value: 'every_3_days', label: 'Every 3 days' },
    { value: 'weekdays', label: 'Weekdays only' },
    { value: 'weekends', label: 'Weekends only' },
]

const frequencyLabel = computed(() => {
    const option = frequencyOptions.find(o => o.value === frequency.value)
    const label = option?.label.toLowerCase() || ''
    if (label === 'every day') return 'every day'
    return label
})
</script>
