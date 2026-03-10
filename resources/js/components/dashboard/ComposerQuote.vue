<template>
  <div class="mt-8 text-center px-6">
    <p class="text-sm italic font-semibold text-slate-400">"{{ quote.text }}"</p>
    <p class="text-sm font-semibold text-slate-300 mt-1">&mdash; {{ quote.author }}</p>
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
