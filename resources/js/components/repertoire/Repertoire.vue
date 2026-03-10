<template>
  <div class="min-h-screen bg-white">
    <div class="relative max-w-4xl mx-auto px-6 py-12">
      <header class="mb-10">
        <h1 class="text-3xl font-serif font-bold uppercase tracking-wide text-stone-800">
          My <span class="text-amber-700">Repertoire</span>
        </h1>
        <p class="font-semibold text-stone-600 mt-1">{{ pieces.length }} {{ pieces.length === 1 ? 'piece' : 'pieces' }} in your collection</p>
      </header>

      <!-- Add piece form -->
      <section class="card mb-8">
        <form @submit.prevent="addPiece" class="px-6 py-5 flex flex-wrap items-end gap-4">
          <div class="flex-1 min-w-45">
            <label class="block text-md font-semibold text-stone-800 mb-1">Title</label>
            <input
              v-model="newTitle"
              type="text"
              placeholder="e.g. Clair de Lune"
              required
              class="w-full px-3 py-2 text-sm bg-white/70 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-600"
            />
          </div>
          <div class="flex-1 min-w-37.5">
            <label class="block text-md font-semibold text-stone-800 mb-1">Composer</label>
            <input
              v-model="newComposer"
              type="text"
              placeholder="e.g. Debussy"
              class="w-full px-3 py-2 text-sm bg-white/70 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-600"
            />
          </div>
          <button
            type="submit"
            class="px-5 py-2 text-sm font-semibold text-white bg-linear-to-r from-amber-500 to-orange-400 rounded-lg shadow-sm hover:from-amber-600 hover:to-orange-500 transition-all"
          >
            + Add Piece
          </button> 
        </form>
      </section>

      <!-- Filters -->
      <div class="flex items-center gap-3 mb-6">
        <input
          v-model="search"
          type="text"
          placeholder="Search pieces..."
          class="flex-1 px-4 py-2 text-sm bg-white/60 border border-stone-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-600"
        />
        <select
          v-model="statusFilter"
          class="px-4 py-2 text-sm font-semibold bg-white/60 border border-stone-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 text-stone-700 appearance-none cursor-pointer bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2212%22%20height%3D%2212%22%20viewBox%3D%220%200%2012%2012%22%3E%3Cpath%20fill%3D%22%2378716c%22%20d%3D%22M2%204l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-size-[12px] bg-position-[right_12px_center] bg-no-repeat pr-9"
        >
          <option value="">All statuses</option>
          <option value="learning">Learning</option>
          <option value="learned">Learned</option>
          <option value="polishing">Polishing</option>
          <option value="shelved">Shelved</option>
        </select>
      </div>

      <!-- Piece list -->
      <section class="card overflow-hidden">
        <div v-if="filteredPieces.length === 0" class="px-6 py-12 text-center">
          <p class="text-stone-400 font-serif italic">
            {{ pieces.length === 0 ? 'No pieces yet — add your first one above!' : 'No pieces match your filters.' }}
          </p>
        </div>
        <ul v-else class="divide-y divide-stone-100">
          <li
            v-for="piece in filteredPieces"
            :key="piece.id"
            class="group px-6 py-4 flex items-center gap-4 hover:bg-stone-50 transition-colors duration-200"
          >
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-sm shrink-0 bg-linear-to-br" :class="statusIconClass(piece.status)">
              <span class="text-white text-sm">&#9835;</span>
            </div>

            <!-- Piece info -->
            <div class="flex-1 min-w-0">
              <p class="text-[15px] font-semibold text-stone-700">{{ piece.title }}</p>
              <p v-if="piece.composer" class="text-sm font-semibold text-stone-400 mt-0.5">{{ piece.composer }}</p>
            </div>

            <!-- Status badge -->
            <select
              :value="piece.status"
              @change="updateStatus(piece, $event.target.value)"
              class="text-sm font-semibold px-2 py-1 rounded-lg border-0 bg-transparent cursor-pointer focus:outline-none focus:ring-2 focus:ring-amber-300/50"
              :class="statusClass(piece.status)"
            >
              <option value="learning">Learning</option>
              <option value="learned">Learned</option>
              <option value="polishing">Polishing</option>
              <option value="shelved">Shelved</option>
            </select>

            <!-- Delete button -->
            <button
              @click="deletePiece(piece)"
              class="text-stone-300 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-all duration-200"
              title="Remove piece"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </li>
        </ul>
      </section>

      <!-- Want to Learn section -->
      <header class="mt-14 mb-6">
        <h2 class="text-2xl font-serif font-bold uppercase tracking-wide text-stone-800">
          Want to <span class="text-amber-700">Learn</span>
        </h2>
        <p class="font-semibold text-stone-600 mt-1">{{ wishlist.length }} {{ wishlist.length === 1 ? 'piece' : 'pieces' }} on your wishlist</p>
      </header>

      <section class="card mb-8">
        <form @submit.prevent="addWishlistPiece" class="px-6 py-5 flex flex-wrap items-end gap-4">
          <div class="flex-1 min-w-45">
            <label class="block text-md font-semibold text-stone-800 mb-1">Title</label>
            <input
              v-model="newWishTitle"
              type="text"
              placeholder="e.g. Ballade No. 1"
              required
              class="w-full px-3 py-2 text-sm bg-white/70 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-600"
            />
          </div>
          <div class="flex-1 min-w-37.5">
            <label class="block text-md font-semibold text-stone-800 mb-1">Composer</label>
            <input
              v-model="newWishComposer"
              type="text"
              placeholder="e.g. Chopin"
              class="w-full px-3 py-2 text-sm bg-white/70 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-600"
            />
          </div>
          <button
            type="submit"
            class="px-5 py-2 text-sm font-semibold text-white bg-linear-to-r from-amber-500 to-orange-400 rounded-lg shadow-sm hover:from-amber-600 hover:to-orange-500 transition-all"
          >
            + Add to Wishlist
          </button>
        </form>
      </section>

      <section class="card overflow-hidden">
        <div v-if="wishlist.length === 0" class="px-6 py-12 text-center">
          <p class="text-stone-400 font-serif italic">No pieces on your wishlist yet — add something you'd love to learn!</p>
        </div>
        <ul v-else class="divide-y divide-stone-100">
          <li
            v-for="item in wishlist"
            :key="item.id"
            class="group px-6 py-4 flex items-center gap-4 hover:bg-stone-50 transition-colors duration-200"
          >
            <div class="w-9 h-9 rounded-xl bg-linear-to-br from-stone-300 to-stone-400 flex items-center justify-center shadow-sm shrink-0">
              <span class="text-white text-sm">&#9834;</span>
            </div>

            <div class="flex-1 min-w-0">
              <p class="text-[15px] font-semibold text-stone-700">{{ item.title }}</p>
              <p v-if="item.composer" class="text-sm font-semibold text-stone-400 mt-0.5">{{ item.composer }}</p>
            </div>

            <!-- Start learning button -->
            <button
              @click="startLearning(item)"
              class="text-sm font-semibold px-3 py-1 rounded-lg text-amber-600 bg-amber-50 hover:bg-amber-300 transition-colors"
              title="Move to repertoire"
            >
              Start Learning
            </button>

            <!-- Delete button -->
            <button
              @click="deleteWishlistPiece(item)"
              class="text-stone-300 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-all duration-200"
              title="Remove from wishlist"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </li>
        </ul>
      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

let nextId = 6
let nextWishId = 3

const pieces = ref([
  { id: 1, title: 'Clair de Lune', composer: 'Debussy', status: 'polishing' },
  { id: 2, title: 'Nocturne Op. 9 No. 2', composer: 'Chopin', status: 'learning' },
  { id: 3, title: 'Gymnopédie No. 1', composer: 'Satie', status: 'learned' },
  { id: 4, title: 'Prelude in C Major', composer: 'Bach', status: 'learned' },
  { id: 5, title: 'Moonlight Sonata, Mvt. 1', composer: 'Beethoven', status: 'learning' },
])

const search = ref('')
const statusFilter = ref('')
const newTitle = ref('')
const newComposer = ref('')

const wishlist = ref([
  { id: 1, title: 'Ballade No. 1', composer: 'Chopin' },
  { id: 2, title: 'Liebestraum No. 3', composer: 'Liszt' },
])
const newWishTitle = ref('')
const newWishComposer = ref('')

const filteredPieces = computed(() => {
  let result = pieces.value

  if (search.value) {
    const q = search.value.toLowerCase()
    result = result.filter(p =>
      p.title.toLowerCase().includes(q) ||
      (p.composer && p.composer.toLowerCase().includes(q))
    )
  }

  if (statusFilter.value) {
    result = result.filter(p => p.status === statusFilter.value)
  }

  return result
})

function statusIconClass(status) {
  const classes = {
    learning: 'from-blue-500 to-blue-400',
    learned: 'from-emerald-500 to-emerald-400',
    polishing: 'from-amber-500 to-orange-400',
    shelved: 'from-stone-400 to-stone-500',
  }
  return classes[status] || 'from-amber-500 to-orange-400'
}

function statusClass(status) {
  const classes = {
    learning: 'text-blue-600 bg-blue-50',
    learned: 'text-emerald-600 bg-emerald-50',
    polishing: 'text-amber-600 bg-amber-50',
    shelved: 'text-stone-500 bg-stone-100',
  }
  return classes[status] || ''
}

function addPiece() {
  if (!newTitle.value.trim()) return

  pieces.value.unshift({
    id: nextId++,
    title: newTitle.value.trim(),
    composer: newComposer.value.trim() || null,
    status: 'learning',
  })

  newTitle.value = ''
  newComposer.value = ''
}

function updateStatus(piece, newStatus) {
  piece.status = newStatus
}

function deletePiece(piece) {
  if (!confirm(`Remove "${piece.title}" from your repertoire?`)) return
  pieces.value = pieces.value.filter(p => p.id !== piece.id)
}

function addWishlistPiece() {
  if (!newWishTitle.value.trim()) return

  wishlist.value.unshift({
    id: nextWishId++,
    title: newWishTitle.value.trim(),
    composer: newWishComposer.value.trim() || null,
  })

  newWishTitle.value = ''
  newWishComposer.value = ''
}

function startLearning(item) {
  pieces.value.unshift({
    id: nextId++,
    title: item.title,
    composer: item.composer,
    status: 'learning',
  })
  wishlist.value = wishlist.value.filter(w => w.id !== item.id)
}

function deleteWishlistPiece(item) {
  if (!confirm(`Remove "${item.title}" from your wishlist?`)) return
  wishlist.value = wishlist.value.filter(w => w.id !== item.id)
}
</script>
