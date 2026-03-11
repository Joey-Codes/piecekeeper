<template>
    <div class="mt-10 text-center px-8 py-6 relative">
        <!-- Decorative quote marks -->
        <span class="absolute top-1 left-4 text-5xl font-serif text-amber-300/25 leading-none select-none">&ldquo;</span>
        <span class="absolute bottom-1 right-4 text-5xl font-serif text-amber-300/25 leading-none select-none">&rdquo;</span>

        <p class="text-base italic font-medium text-stone-400 font-serif leading-relaxed max-w-lg mx-auto">
            "{{ quote.text }}"
        </p>
        <p class="text-sm font-semibold text-stone-300 mt-2">
            &mdash; {{ quote.author }}
        </p>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const quotes = [
    { text: 'The piano keys are black and white, but they sound like a million colors in your mind.', author: 'Maria Cristina Mena' },
    { text: 'To play a wrong note is insignificant; to play without passion is inexcusable.', author: 'Beethoven' },
    { text: 'The music is not in the notes, but in the silence between.', author: 'Mozart' },
    { text: 'Simplicity is the final achievement.', author: 'Chopin' },
]

const quote = ref(quotes[Math.floor(Math.random() * quotes.length)])
</script>
