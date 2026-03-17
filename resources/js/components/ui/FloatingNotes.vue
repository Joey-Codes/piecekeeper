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
const rand = (min, max) => Math.random() * (max - min) + min

// Grid-based placement: divide viewport into cells, place one note per cell with jitter
const cols = 5
const rows = 4
const cellW = 100 / cols
const cellH = 100 / rows
const padding = 15 // % inset from cell edges to avoid overlap

const notes = Array.from({ length: cols * rows }, (_, i) => {
    const col = i % cols
    const row = Math.floor(i / cols)
    return {
        id: i,
        symbol: symbols[Math.floor(Math.random() * symbols.length)],
        color: colors[Math.floor(Math.random() * colors.length)],
        top: row * cellH + rand(padding, cellH - padding),
        left: col * cellW + rand(padding, cellW - padding),
        size: rand(2, 5.5),
        rotation: rand(-25, 25),
        opacity: rand(0.12, 0.25),
    }
})
</script>
