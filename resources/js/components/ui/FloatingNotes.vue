<template>
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <span
            v-for="note in notes"
            :key="note.id"
            class="absolute"
            :class="note.color"
            :style="{
                top: note.top + '%',
                left: note.left + '%',
                fontSize: note.size + 'rem',
                transform: 'rotate(' + note.rotation + 'deg)',
                opacity: note.opacity,
            }"
            v-html="note.symbol"
        />
    </div>
</template>

<script setup>
const symbols = ['♫', '♪', '♭', '𝄞', '♯']
const colors = [
    'text-amber-400',
    'text-amber-500',
    'text-orange-300',
    'text-orange-400',
    'text-orange-500',
    'text-yellow-400',
]
const count = 20
const rand = (min, max) => Math.random() * (max - min) + min

const notes = Array.from({ length: count }, (_, i) => ({
    id: i,
    symbol: symbols[Math.floor(Math.random() * symbols.length)],
    color: colors[Math.floor(Math.random() * colors.length)],
    top: rand(2, 93),
    left: rand(3, 90),
    size: rand(2, 5.5),
    rotation: rand(-25, 25),
    opacity: rand(0.12, 0.25),
}))
</script>
