<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50/80 via-white to-stone-50 relative overflow-hidden">
        <FloatingNotes />

        <div class="relative max-w-4xl mx-auto px-6 py-12">
            <header class="mb-10 text-center">
                <h1 class="text-4xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    My <span class="bg-linear-to-r from-amber-600 to-orange-500 bg-clip-text text-transparent">Repertoire</span>
                </h1>
                <p class="font-semibold text-stone-500 mt-2">
                    {{ pieces.length }} {{ pieces.length === 1 ? 'piece' : 'pieces' }} in your collection
                </p>
                <div class="mt-3 mx-auto w-16 h-1 rounded-full bg-linear-to-r from-amber-400 to-orange-400 opacity-60" />
            </header>

            <!-- Add piece button -->
            <div class="mb-6 flex justify-center">
                <button
                    class="px-6 py-2.5 text-md font-semibold text-white bg-linear-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg shadow-amber-300/30 hover:shadow-xl hover:shadow-amber-300/40 hover:scale-[1.03] hover:-translate-y-0.5 transition-all duration-200"
                    @click="showAddPiece = true"
                >
                    + Add Piece
                </button>
            </div>

            <AddPieceForm
                :open="showAddPiece"
                modal-title="Add Piece"
                submit-label="Add Piece"
                @submit="addPiece"
                @close="showAddPiece = false"
            />

            <!-- Filters -->
            <div class="flex items-center gap-3 mb-6">
                <div class="relative flex-1">
                    <svg
                        class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search pieces..."
                        class="w-full pl-10 pr-4 py-2.5 text-md bg-white/80 border border-stone-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-400 transition-shadow hover:shadow-md"
                    >
                </div>
                <select
                    v-model="statusFilter"
                    class="px-4 py-2.5 text-md font-semibold bg-white/80 border border-stone-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 text-stone-600 appearance-none cursor-pointer bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2212%22%20height%3D%2212%22%20viewBox%3D%220%200%2012%2012%22%3E%3Cpath%20fill%3D%22%2378716c%22%20d%3D%22M2%204l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-size-[12px] bg-position-[right_12px_center] bg-no-repeat pr-9 transition-shadow hover:shadow-md"
                >
                    <option value="">
                        All statuses
                    </option>
                    <option value="learning">
                        Learning
                    </option>
                    <option value="learned">
                        Learned
                    </option>
                    <option value="polishing">
                        Polishing
                    </option>
                    <option value="shelved">
                        Shelved
                    </option>
                </select>
            </div>

            <!-- Piece list -->
            <section class="card overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div
                    v-if="filteredPieces.length === 0"
                    class="px-6 py-16 text-center"
                >
                    <span class="text-4xl text-stone-200 block mb-3">&#9835;</span>
                    <p class="text-stone-400 font-serif italic">
                        {{ pieces.length === 0 ? 'No pieces yet — add your first one above!' : 'No pieces match your filters.' }}
                    </p>
                </div>
                <ul
                    v-else
                    class="divide-y divide-stone-100"
                >
                    <li
                        v-for="piece in filteredPieces"
                        :key="piece.id"
                        class="group px-6 py-4 flex items-center gap-4 hover:bg-stone-50 transition-colors duration-200 cursor-pointer"
                        @click="selectedPiece = piece"
                    >
                        <div
                            class="w-1 self-stretch rounded-full shrink-0"
                            :class="statusBarClass(piece.status)"
                        />

                        <!-- Piece info -->
                        <div class="flex-1 min-w-0">
                            <p class="text-md font-semibold text-stone-800">
                                {{ piece.title }}
                            </p>
                            <p
                                v-if="piece.composer"
                                class="text-sm text-stone-700 mt-0.5"
                            >
                                {{ piece.composer }}
                            </p>
                        </div>

                        <!-- Status badge -->
                        <select
                            :value="piece.status"
                            class="text-sm font-semibold px-2 py-1 rounded-lg border-0 bg-transparent cursor-pointer focus:outline-none focus:ring-2 focus:ring-amber-300/50"
                            :class="statusClass(piece.status)"
                            @click.stop
                            @change="updateStatus(piece, $event.target.value)"
                        >
                            <option value="learning">
                                Learning
                            </option>
                            <option value="learned">
                                Learned
                            </option>
                            <option value="polishing">
                                Polishing
                            </option>
                            <option value="shelved">
                                Shelved
                            </option>
                        </select>

                        <!-- Delete button -->
                        <button
                            class="text-red-400 opacity-0 group-hover:opacity-100 transition-all duration-200"
                            title="Remove piece"
                            @click.stop="deletePiece(piece)"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>
                        </button>
                    </li>
                </ul>
            </section>

            <!-- Want to Learn section -->
            <header class="mt-16 mb-6 text-center">
                <h2 class="text-3xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    Want to <span class="bg-linear-to-r from-violet-500 to-purple-500 bg-clip-text text-transparent">Learn</span>
                </h2>
                <p class="font-semibold text-stone-500 mt-2">
                    {{ wishlist.length }} {{ wishlist.length === 1 ? 'piece' : 'pieces' }} on your wishlist
                </p>
                <div class="mt-3 mx-auto w-12 h-1 rounded-full bg-linear-to-r from-violet-400 to-purple-400 opacity-60" />
            </header>

            <div class="mb-6 flex justify-center">
                <button
                    class="px-6 py-2.5 text-md font-semibold text-white bg-linear-to-r from-violet-500 to-purple-500 rounded-xl shadow-lg shadow-violet-300/30 hover:shadow-xl hover:shadow-violet-300/40 hover:scale-[1.03] hover:-translate-y-0.5 transition-all duration-200"
                    @click="showAddWish = true"
                >
                    + Add to Wishlist
                </button>
            </div>

            <AddPieceForm
                :open="showAddWish"
                modal-title="Add to Wishlist"
                submit-label="Add to Wishlist"
                @submit="addWishlistPiece"
                @close="showAddWish = false"
            />

            <section class="card overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div
                    v-if="wishlist.length === 0"
                    class="px-6 py-16 text-center"
                >
                    <span class="text-4xl text-stone-200 block mb-3">&#9834;</span>
                    <p class="text-stone-400 font-serif italic">
                        No pieces on your wishlist yet — add something you'd love to learn!
                    </p>
                </div>
                <ul
                    v-else
                    class="divide-y divide-stone-100"
                >
                    <li
                        v-for="item in wishlist"
                        :key="item.id"
                        class="group px-6 py-4 flex items-center gap-4 hover:bg-stone-50 transition-colors duration-200 cursor-pointer"
                        @click="selectedWishPiece = item"
                    >
                        <div class="flex-1 min-w-0">
                            <p class="text-md font-semibold text-stone-800">
                                {{ item.title }}
                            </p>
                            <p
                                v-if="item.composer"
                                class="text-sm text-stone-600 mt-0.5"
                            >
                                {{ item.composer }}
                            </p>
                        </div>

                        <!-- Start learning button -->
                        <button
                            class="text-sm font-semibold px-3.5 py-1.5 rounded-lg text-amber-700 bg-amber-50 border border-amber-200/60 hover:bg-amber-100 hover:border-amber-300 transition-all duration-200"
                            title="Move to repertoire"
                            @click.stop="startLearning(item)"
                        >
                            Start Learning
                        </button>

                        <!-- Delete button -->
                        <button
                            class="text-red-400 opacity-0 group-hover:opacity-100 transition-all duration-200"
                            title="Remove from wishlist"
                            @click.stop="deleteWishlistPiece(item)"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>
                        </button>
                    </li>
                </ul>
            </section>
        </div>

        <PieceDetail
            :piece="selectedPiece"
            @save="savePiece"
            @close="selectedPiece = null"
        />

        <PieceDetail
            :piece="selectedWishPiece"
            :show-status="false"
            @save="saveWishPiece"
            @close="selectedWishPiece = null"
        />

        <ConfirmModal
            :show="showConfirmModal"
            :title="confirmModalTitle"
            :message="confirmModalMessage"
            confirm-label="Remove"
            @confirm="onConfirm"
            @cancel="onCancelConfirm"
        />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import ConfirmModal from '../ui/ConfirmModal.vue'
import FloatingNotes from '../ui/FloatingNotes.vue'
import AddPieceForm from './AddPieceForm.vue'
import PieceDetail from './PieceDetail.vue'

let nextId = 6
let nextWishId = 3

const pieces = ref([
    { id: 1, title: 'Clair de Lune', composer: 'Debussy', status: 'polishing', link: 'https://youtube.com/watch?v=example1', files: [{ name: 'Clair_de_Lune.pdf', pages: 6 }] },
    { id: 2, title: 'Nocturne Op. 9 No. 2', composer: 'Chopin', status: 'learning', files: [{ name: 'Nocturne_Op9_No2.pdf', pages: 4 }, { name: 'Nocturne_Annotations.pdf', pages: 2 }] },
    { id: 3, title: 'Gymnopédie No. 1', composer: 'Satie', status: 'learned', files: [] },
    { id: 4, title: 'Prelude in C Major', composer: 'Bach', status: 'learned', files: [{ name: 'Prelude_C_Major_BWV846.pdf', pages: 3 }] },
    { id: 5, title: 'Moonlight Sonata, Mvt. 1', composer: 'Beethoven', status: 'learning', files: [] },
])

const search = ref('')
const statusFilter = ref('')
const showAddPiece = ref(false)
const showAddWish = ref(false)
const selectedPiece = ref(null)
const selectedWishPiece = ref(null)

const wishlist = ref([
    { id: 1, title: 'Ballade No. 1', composer: 'Chopin' },
    { id: 2, title: 'Liebestraum No. 3', composer: 'Liszt' },
])

const showConfirmModal = ref(false)
const confirmModalTitle = ref('')
const confirmModalMessage = ref('')
const confirmModalAction = ref(null)

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

function statusBarClass(status) {
    const classes = {
        learning: 'bg-blue-400',
        learned: 'bg-emerald-400',
        polishing: 'bg-amber-400',
        shelved: 'bg-stone-400',
    }
    return classes[status] || 'bg-amber-400'
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

function addPiece(data) {
    pieces.value.unshift({
        id: nextId++,
        title: data.title,
        composer: data.composer,
        link: data.link,
        files: data.files,
        status: 'learning',
    })
    showAddPiece.value = false
}

function savePiece(data) {
    const piece = pieces.value.find(p => p.id === data.id)
    if (piece) {
        piece.title = data.title
        piece.composer = data.composer
        piece.status = data.status
        piece.link = data.link
        piece.notes = data.notes
        piece.files = data.files
    }
    selectedPiece.value = null
}

function updateStatus(piece, newStatus) {
    piece.status = newStatus
}

function requestConfirm(title, message, action) {
    confirmModalTitle.value = title
    confirmModalMessage.value = message
    confirmModalAction.value = action
    showConfirmModal.value = true
}

function onConfirm() {
    if (confirmModalAction.value) confirmModalAction.value()
    showConfirmModal.value = false
}

function onCancelConfirm() {
    showConfirmModal.value = false
}

function deletePiece(piece) {
    requestConfirm('Remove Piece', `Remove <strong>"${piece.title}"</strong> from your repertoire?`, () => {
        pieces.value = pieces.value.filter(p => p.id !== piece.id)
    })
}

function saveWishPiece(data) {
    const item = wishlist.value.find(w => w.id === data.id)
    if (item) {
        item.title = data.title
        item.composer = data.composer
        item.link = data.link
        item.notes = data.notes
        item.files = data.files
    }
    selectedWishPiece.value = null
}

function addWishlistPiece(data) {
    wishlist.value.unshift({
        id: nextWishId++,
        title: data.title,
        composer: data.composer,
        link: data.link,
        files: data.files,
    })
    showAddWish.value = false
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
    requestConfirm('Remove Piece', `Remove <strong>"${item.title}"</strong> from your wishlist?`, () => {
        wishlist.value = wishlist.value.filter(w => w.id !== item.id)
    })
}
</script>
