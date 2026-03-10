<template>
  <section class="card overflow-hidden relative mb-8">
    <!-- Card header -->
    <div class="px-6 py-5 border-b border-stone-200 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center shadow-sm">
          <span class="text-white text-sm">&#9835;</span>
        </div>
        <h2 class="text-base font-serif font-bold uppercase tracking-wide text-stone-800">Today's Practice<span v-if="allDone" class="text-emerald-500"> (Completed)</span></h2>
      </div>
      <div class="flex items-center gap-3">
        <span class="text-base font-semibold" :class="allDone ? 'text-emerald-500' : 'text-stone-800'">
          {{ completedCount }}/{{ pieces.length }}
        </span>
        <div class="w-44 h-4 bg-stone-100 rounded-full overflow-hidden">
          <div
            class="h-full rounded-full transition-all duration-500 ease-out"
            :class="allDone ? 'bg-emerald-400' : 'bg-linear-to-r from-amber-400 to-orange-400'"
            :style="{ width: progressPercent + '%' }"
          />
        </div>
      </div>
    </div>

    <!-- Disabled hint banner -->
    <div v-if="disabled" class="px-6 py-2.5 bg-stone-50 border-b border-stone-200 flex items-center gap-2">
      <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span class="text-md text-stone-600">Press <strong class="text-amber-700">Start Practice</strong> above to check off pieces</span>
    </div>

    <!-- Piece list -->
    <ul class="divide-y divide-stone-100">
      <li
        v-for="piece in pieces"
        :key="piece.id"
        class="group px-6 py-4 flex items-center gap-4 transition-colors duration-200"
        :class="piece.done ? 'bg-stone-50/60' : 'hover:bg-stone-50'"
      >
        <div :class="disabled ? 'opacity-30 pointer-events-none' : ''">
          <TrebleClefCheck :checked="piece.done" @toggle="toggle(piece)" />
        </div>

        <!-- Piece info -->
        <div class="flex-1 min-w-0">
          <p
            class="text-md font-semibold transition-all duration-300"
            :class="piece.done ? 'text-stone-400 line-through' : 'text-black'"
          >
            {{ piece.title }}
          </p>
          <p class="text-sm text-stone-600 mt-0.5">{{ piece.composer }}</p>
        </div>

        <!-- Musical accent -->
        <span
          class="text-lg transition-all duration-300"
          :class="piece.done ? 'text-emerald-300 opacity-100' : 'text-stone-300 opacity-0 group-hover:opacity-40'"
        >
          &#9834;
        </span>
      </li>
    </ul>

    <!-- Footer -->
    <div class="px-6 py-4 border-t border-stone-200 transition-colors duration-300" :class="allDone ? 'bg-emerald-400' : 'bg-linear-to-r from-amber-400 to-orange-300'">
      <p v-if="allDone" class="text-lg text-white font-semibold text-center">
        All done for today — great work! &#127929;
      </p>
      <p v-else class="text-lg font-semibold text-white text-center">
        {{ pieces.length - completedCount }} {{ pieces.length - completedCount === 1 ? 'piece' : 'pieces' }} remaining
      </p>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import TrebleClefCheck from '../ui/TrebleClefCheck.vue'

const props = defineProps({
  disabled: { type: Boolean, default: false },
})

const pieces = ref([
  { id: 1, title: 'Clair de Lune', composer: 'Debussy', done: false },
  { id: 2, title: 'Nocturne Op. 9 No. 2', composer: 'Chopin', done: false },
  { id: 3, title: 'Gymnopédie No. 1', composer: 'Satie', done: false },
  { id: 4, title: 'Prelude in C Major', composer: 'Bach', done: false },
  { id: 5, title: 'Moonlight Sonata, Mvt. 1', composer: 'Beethoven', done: false },
])

const completedCount = computed(() => pieces.value.filter(p => p.done).length)
const progressPercent = computed(() => (completedCount.value / pieces.value.length) * 100)
const allDone = computed(() => completedCount.value === pieces.value.length)

function toggle(piece) {
  if (props.disabled) return
  piece.done = !piece.done
}
</script>
