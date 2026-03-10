<template>
  <button
    @click="$emit('toggle')"
    class="relative w-7 h-9 shrink-0 flex items-center justify-center cursor-pointer"
  >
    <!-- Unchecked: faint circle -->
    <div
      class="absolute inset-x-0.5 inset-y-1 rounded-lg border-2 transition-all duration-300"
      :class="checked ? 'border-transparent scale-90 opacity-0' : 'border-stone-800 hover:border-amber-400 scale-100 opacity-100'"
    />

    <!-- Treble clef SVG with draw animation -->
    <svg
      viewBox="0 0 50 100"
      class="w-5 h-9 transition-opacity duration-200"
      :class="checked ? 'opacity-100' : 'opacity-0'"
      fill="none"
    >
      <path
        ref="clefPath"
        d="M25 84
           C24 87 20 90 17 89
           C14 87 17 84 25 84
           L25 18
           C25 7 42 5 42 16
           C42 27 30 28 25 34
           C19 41 8 50 8 62
           C8 72 18 76 26 72
           C34 68 33 58 27 55
           C21 52 17 57 20 62"
        stroke="currentColor"
        stroke-width="4"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="treble-clef-path"
        :class="checked ? 'text-amber-600 draw-in' : 'text-stone-300 draw-out'"
      />
    </svg>
  </button>
</template>

<script setup>
import { ref, onMounted } from 'vue'

defineProps({
  checked: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['toggle'])

const clefPath = ref(null)

onMounted(() => {
  if (clefPath.value) {
    const length = clefPath.value.getTotalLength()
    clefPath.value.style.setProperty('--path-length', length)
  }
})
</script>

<style scoped>
.treble-clef-path {
  stroke-dasharray: var(--path-length);
  stroke-dashoffset: var(--path-length);
  transition: stroke-dashoffset 0.8s ease-in-out, color 0.3s;
}

.treble-clef-path.draw-in {
  stroke-dashoffset: 0;
}

.treble-clef-path.draw-out {
  stroke-dashoffset: var(--path-length);
  transition: stroke-dashoffset 0.4s ease-in;
}
</style>
