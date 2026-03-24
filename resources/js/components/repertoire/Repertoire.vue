<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50/80 via-white to-stone-50 relative overflow-hidden">
        <FloatingNotes />

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
            <LoadingSpinner
                v-if="loading"
                message="Loading your repertoire..."
            />

            <template v-else>
                <header class="mb-6 text-center">
                    <h1 class="text-2xl sm:text-4xl font-serif font-bold uppercase tracking-wide text-stone-800">
                        My <span class="bg-linear-to-r from-amber-600 to-orange-500 bg-clip-text text-transparent">Repertoire</span>
                    </h1>
                    <p class="text-sm sm:text-base font-semibold text-stone-500 mt-1.5 sm:mt-2">
                        {{ repertoire.length }} {{ repertoire.length === 1 ? 'piece' : 'pieces' }} in your collection
                    </p>
                    <div class="mt-2.5 sm:mt-3 mx-auto w-12 sm:w-16 h-1 rounded-full bg-linear-to-r from-amber-400 to-orange-400 opacity-60" />
                </header>

                <template v-if="!reorderMode">
                    <AddPieceForm
                        :open="showAddPiece"
                        modal-title="Add Piece"
                        submit-label="Add Piece"
                        @submit="addPiece"
                        @close="showAddPiece = false"
                    />

                    <!-- Filters & actions -->
                    <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-3 mb-4 sm:mb-6">
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
                                class="w-full pl-10 pr-4 py-2 sm:py-2.5 text-sm sm:text-md bg-white/80 border border-stone-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-400 transition-shadow hover:shadow-md"
                            >
                        </div>
                        <button
                            class="hidden sm:block px-4 sm:py-2.5 text-sm sm:text-md font-semibold text-stone-600 bg-white/80 border border-stone-200 rounded-xl shadow-sm hover:bg-stone-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-amber-300/50 transition-all duration-200 whitespace-nowrap"
                            @click="reorderMode = true"
                        >
                            Change Order
                        </button>
                        <div class="flex gap-2.5 sm:gap-3">
                            <button
                                class="sm:hidden px-4 py-2 text-sm font-semibold text-stone-600 bg-white/80 border border-stone-200 rounded-xl shadow-sm hover:bg-stone-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-amber-300/50 transition-all duration-200 whitespace-nowrap"
                                @click="reorderMode = true"
                            >
                                Change Order
                            </button>
                            <select
                                v-model="statusFilter"
                                class="flex-1 sm:flex-none px-4 py-2 sm:py-2.5 text-sm sm:text-md font-semibold bg-white/80 border border-stone-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 text-stone-600 appearance-none cursor-pointer bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2212%22%20height%3D%2212%22%20viewBox%3D%220%200%2012%2012%22%3E%3Cpath%20fill%3D%22%2378716c%22%20d%3D%22M2%204l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-size-[12px] bg-position-[right_12px_center] bg-no-repeat pr-9 transition-shadow hover:shadow-md text-center sm:text-left"
                            >
                                <option value="">
                                    All statuses
                                </option>
                                <option value="Learning">
                                    Learning
                                </option>
                                <option value="Polishing">
                                    Polishing
                                </option>
                                <option value="Mastered">
                                    Mastered
                                </option>
                                <option value="Relearning">
                                    Relearning
                                </option>
                                <option value="Shelved">
                                    Shelved
                                </option>
                            </select>
                            <button
                                class="p-2 sm:p-2.5 text-stone-400 hover:text-amber-500 bg-white/80 border border-stone-200 rounded-xl shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-amber-300/50 transition-all duration-200"
                                title="What do statuses mean?"
                                @click="showStatusInfo = true"
                            >
                                <svg
                                    class="w-4 h-4 sm:w-5 sm:h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </button>
                        </div>
                        <button
                            class="px-4 py-2 sm:py-2.5 text-sm sm:text-md font-semibold text-white bg-linear-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg shadow-amber-300/30 hover:shadow-xl hover:shadow-amber-300/40 hover:scale-[1.03] hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap"
                            @click="showAddPiece = true"
                        >
                            + Add Piece
                        </button>
                    </div>

                    <!-- Piece list -->
                    <section class="card overflow-hidden hover:shadow-lg transition-shadow duration-300 border-orange-200">
                        <div
                            v-if="filteredPieces.length === 0"
                            class="px-6 py-16 text-center"
                        >
                            <span class="text-4xl text-stone-200 block mb-3">&#9835;</span>
                            <p class="text-stone-400 font-serif italic">
                                {{ repertoire.length === 0 ? 'No pieces yet — add your first one above!' : 'No pieces match your filters.' }}
                            </p>
                        </div>
                        <ul
                            v-else
                            class="divide-y divide-stone-100"
                        >
                            <li
                                v-for="piece in filteredPieces"
                                :key="piece.id"
                                class="group pl-3 pr-1 sm:px-6 py-3 sm:py-4 flex items-center gap-2 sm:gap-4 hover:bg-stone-50 transition-colors duration-200 cursor-pointer"
                                @click="selectedPiece = piece"
                            >
                                <div
                                    class="w-1 self-stretch rounded-full shrink-0"
                                    :class="statusBarClass(piece.status)"
                                />

                                <!-- Piece info -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm sm:text-md font-semibold text-stone-800 truncate">
                                        {{ piece.title }}
                                    </p>
                                    <p
                                        v-if="piece.composer"
                                        class="text-xs sm:text-sm text-stone-700 mt-0.5"
                                    >
                                        {{ piece.composer }}
                                    </p>
                                </div>

                                <!-- Status badge -->
                                <select
                                    :value="piece.status"
                                    class="text-xs sm:text-sm font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-lg border cursor-pointer focus:outline-none focus:ring-2 focus:ring-amber-300/50 transition-all duration-200"
                                    :class="statusClass(piece.status)"
                                    @click.stop
                                    @change="updateStatus(piece, $event.target.value)"
                                >
                                    <option value="Learning">
                                        Learning
                                    </option>
                                    <option value="Polishing">
                                        Polishing
                                    </option>
                                    <option value="Mastered">
                                        Mastered
                                    </option>
                                    <option value="Relearning">
                                        Relearning
                                    </option>
                                    <option value="Shelved">
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
                                        class="w-5 h-5"
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
                </template>

                <!-- Reorder mode -->
                <ReorderList
                    v-else
                    :pieces="activePieces"
                    @save="saveReorder"
                    @cancel="reorderMode = false"
                />

                <!-- Want to Learn section -->
                <template v-if="!reorderMode">
                    <header class="mt-10 sm:mt-16 mb-4 sm:mb-6 text-center">
                        <h2 class="text-xl sm:text-3xl font-serif font-bold uppercase tracking-wide text-stone-800">
                            Want to <span class="bg-linear-to-r from-violet-500 to-purple-500 bg-clip-text text-transparent">Learn</span>
                        </h2>
                        <p class="text-sm sm:text-base font-semibold text-stone-500 mt-1.5 sm:mt-2">
                            {{ wishlist.length }} {{ wishlist.length === 1 ? 'piece' : 'pieces' }} on your wishlist
                        </p>
                        <div class="mt-2.5 sm:mt-3 mx-auto w-10 sm:w-12 h-1 rounded-full bg-linear-to-r from-violet-400 to-purple-400 opacity-60" />
                    </header>

                    <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-3 mb-4 sm:mb-6">
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
                                v-model="wishlistSearch"
                                type="text"
                                placeholder="Search wishlist..."
                                class="w-full pl-10 pr-4 py-2 sm:py-2.5 text-sm sm:text-md bg-white/80 border border-stone-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-300/50 focus:border-violet-400 placeholder-stone-400 transition-shadow hover:shadow-md"
                            >
                        </div>
                        <button
                            class="px-4 py-2 sm:py-2.5 text-sm sm:text-md font-semibold text-white bg-linear-to-r from-violet-500 to-purple-500 rounded-xl shadow-lg shadow-violet-300/30 hover:shadow-xl hover:shadow-violet-300/40 hover:scale-[1.03] hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap"
                            @click="showAddWish = true"
                        >
                            + Add to Wishlist
                        </button>
                    </div>

                    <AddPieceForm
                        :open="showAddWish"
                        modal-title="Add to Wishlist"
                        submit-label="Add to Wishlist"
                        wishlist
                        @submit="addWishlistPiece"
                        @close="showAddWish = false"
                    />

                    <section class="card overflow-hidden hover:shadow-lg transition-shadow duration-300 border-violet-200">
                        <div
                            v-if="filteredWishlist.length === 0"
                            class="px-6 py-16 text-center"
                        >
                            <span class="text-4xl text-stone-200 block mb-3">&#9834;</span>
                            <p class="text-stone-400 font-serif italic">
                                {{ wishlist.length === 0 ? 'No pieces on your wishlist yet — add something you\'d love to learn!' : 'No pieces match your search.' }}
                            </p>
                        </div>
                        <ul
                            v-else
                            class="divide-y divide-stone-100"
                        >
                            <li
                                v-for="item in filteredWishlist"
                                :key="item.id"
                                class="group px-4 sm:px-6 py-3 sm:py-4 flex items-center gap-3 sm:gap-4 hover:bg-stone-50 transition-colors duration-200 cursor-pointer"
                                @click="selectedWishPiece = item"
                            >
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm sm:text-md font-semibold text-stone-800 truncate">
                                        {{ item.title }}
                                    </p>
                                    <p
                                        v-if="item.composer"
                                        class="text-xs sm:text-sm text-stone-600 mt-0.5"
                                    >
                                        {{ item.composer }}
                                    </p>
                                </div>

                                <!-- Start learning button -->
                                <button
                                    class="text-xs sm:text-sm font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-lg text-amber-700 bg-amber-50 border border-amber-200/60 hover:bg-amber-100 hover:border-amber-300 transition-all duration-200 whitespace-nowrap"
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
                                        class="w-5 h-5"
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
                </template>
            </template>
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
            :confirm-label="confirmModalLabel"
            @confirm="onConfirm"
            @cancel="onCancelConfirm"
        />

        <StatusInfoModal
            :show="showStatusInfo"
            @close="showStatusInfo = false"
        />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/api'
import ConfirmModal from '../ui/ConfirmModal.vue'
import FloatingNotes from '../ui/FloatingNotes.vue'
import LoadingSpinner from '../ui/LoadingSpinner.vue'
import AddPieceForm from './AddPieceForm.vue'
import PieceDetail from './PieceDetail.vue'
import ReorderList from './ReorderList.vue'
import StatusInfoModal from './StatusInfoModal.vue'

// const pieces = ref([
//     { id: 1, title: 'Clair de Lune', composer: 'Debussy', status: 'polishing', links: ['https://youtube.com/watch?v=example1'], files: [{ name: 'Clair_de_Lune.pdf', pages: 6 }] },
//     { id: 2, title: 'Nocturne Op. 9 No. 2', composer: 'Chopin', status: 'learning', files: [{ name: 'Nocturne_Op9_No2.pdf', pages: 4 }, { name: 'Nocturne_Annotations.pdf', pages: 2 }] },
//     { id: 3, title: 'Gymnopédie No. 1', composer: 'Satie', status: 'learned', files: [] },
//     { id: 4, title: 'Prelude in C Major', composer: 'Bach', status: 'learned', files: [{ name: 'Prelude_C_Major_BWV846.pdf', pages: 3 }] },
//     { id: 5, title: 'Moonlight Sonata, Mvt. 1', composer: 'Beethoven', status: 'learning', files: [] },
// ])
const pieces = ref([])
const loading = ref(true)

onMounted(async () => {
    try {
        const response = await api.get('/api/pieces')
        pieces.value = response.data
    } catch (e) {
        console.error('Failed to load pieces:', e)
    } finally {
        loading.value = false
    }
})

const search = ref('')
const statusFilter = ref('')
const showAddPiece = ref(false)
const showAddWish = ref(false)
const selectedPiece = ref(null)
const selectedWishPiece = ref(null)
const reorderMode = ref(false)
const showStatusInfo = ref(false)
const wishlistSearch = ref('')

const repertoire = computed(() => pieces.value.filter(p => p.status !== 'Want to Learn'))
const wishlist = computed(() => pieces.value.filter(p => p.status === 'Want to Learn'))

const showConfirmModal = ref(false)
const confirmModalTitle = ref('')
const confirmModalMessage = ref('')
const confirmModalLabel = ref('Confirm')
const confirmModalAction = ref(null)

const activePieces = computed(() => {
    return repertoire.value
        .filter(p => p.sort_order != null)
        .sort((a, b) => a.sort_order - b.sort_order)
})

const filteredPieces = computed(() => {
    let result = repertoire.value

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

    return [...result].sort((a, b) => {
        if (a.sort_order != null && b.sort_order != null) return a.sort_order - b.sort_order
        if (a.sort_order != null) return -1
        if (b.sort_order != null) return 1
        return 0
    })
})

const filteredWishlist = computed(() => {
    if (!wishlistSearch.value) return wishlist.value

    const q = wishlistSearch.value.toLowerCase()
    return wishlist.value.filter(p =>
        p.title.toLowerCase().includes(q) ||
        (p.composer && p.composer.toLowerCase().includes(q))
    )
})

function statusBarClass(status) {
    const classes = {
        Learning: 'bg-blue-400',
        Polishing: 'bg-amber-400',
        Mastered: 'bg-emerald-400',
        Relearning: 'bg-violet-400',
        Shelved: 'bg-stone-400',
    }
    return classes[status] || 'bg-amber-400'
}

function statusClass(status) {
    const classes = {
        Learning: 'text-blue-700 bg-blue-50 border-blue-200/60',
        Polishing: 'text-amber-700 bg-amber-50 border-amber-200/60',
        Mastered: 'text-emerald-700 bg-emerald-50 border-emerald-200/60',
        Relearning: 'text-violet-700 bg-violet-50 border-violet-200/60',
        Shelved: 'text-stone-600 bg-stone-100 border-stone-200/60',
    }
    return classes[status] || ''
}

async function saveReorder(orderedIds) {
    try {
        await api.put('/api/pieces/reorder', { ids: orderedIds })
        orderedIds.forEach((id, index) => {
            const piece = pieces.value.find(p => p.id === id)
            if (piece) piece.sort_order = index + 1
        })
        reorderMode.value = false
    } catch (e) {
        console.error('Failed to save order:', e)
    }
}

function addPiece(piece) {
    pieces.value.unshift(piece)
    showAddPiece.value = false
}

function savePiece(data) {
    const piece = pieces.value.find(p => p.id === data.id)
    if (piece) {
        piece.title = data.title
        piece.composer = data.composer
        piece.status = data.status
        piece.reference_links = data.reference_links
        piece.notes = data.notes
        piece.files = data.files
    }
    selectedPiece.value = null
}

async function updateStatus(piece, newStatus) {
    const oldStatus = piece.status
    piece.status = newStatus

    try {
        await api.put(`/api/pieces/${piece.id}`, { status: newStatus })
    } catch (e) {
        piece.status = oldStatus
        console.error('Failed to update status:', e)
    }
}

function requestConfirm(title, message, action, label = 'Confirm') {
    confirmModalTitle.value = title
    confirmModalMessage.value = message
    confirmModalAction.value = action
    confirmModalLabel.value = label
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
    requestConfirm('Remove Piece', `Remove <strong>"${piece.title}"</strong> from your repertoire? Please note this action is irreversible!`, async () => {
        try {
            await api.delete(`/api/pieces/${piece.id}`)
            pieces.value = pieces.value.filter(p => p.id !== piece.id)
        } catch (e) {
            console.error('Failed to delete piece:', e)
        }
    }, 'Remove')
}

function saveWishPiece(data) {
    const item = wishlist.value.find(w => w.id === data.id)
    if (item) {
        item.title = data.title
        item.composer = data.composer
        item.reference_links = data.reference_links
        item.notes = data.notes
        item.files = data.files
    }
    selectedWishPiece.value = null
}

function addWishlistPiece(piece) {
    pieces.value.unshift(piece)
    showAddWish.value = false
}

function startLearning(item) {
    requestConfirm('Start Learning?', `Move <strong>"${item.title}"</strong> to your repertoire?`, async () => {
        const oldStatus = item.status
        item.status = 'Learning'

        try {
            await api.put(`/api/pieces/${item.id}`, { status: 'Learning' })
        } catch (e) {
            item.status = oldStatus
            console.error('Failed to start learning:', e)
        }
    }, 'Start Learning')
}

function deleteWishlistPiece(item) {
    requestConfirm('Remove Piece', `Remove <strong>"${item.title}"</strong> from your wishlist?`, async () => {
        try {
            await api.delete(`/api/pieces/${item.id}`)
            pieces.value = pieces.value.filter(p => p.id !== item.id)
        } catch (e) {
            console.error('Failed to delete piece:', e)
        }
    }, 'Remove')
}
</script>
