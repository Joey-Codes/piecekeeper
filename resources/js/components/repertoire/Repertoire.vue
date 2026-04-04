<template>
    <div class="min-h-screen bg-linear-to-b from-amber-100/70 via-orange-50/40 to-stone-100 relative overflow-hidden">
        <FloatingNotes />

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
            <header class="mb-8 sm:mb-12 flex flex-col items-center">
                <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-md mb-4 sm:mb-5">
                    <svg
                        class="w-5 h-5 sm:w-7 sm:h-7 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V4.125A2.25 2.25 0 0017.868 1.9l-6.75 1.929a2.25 2.25 0 00-1.618 2.163V15m0 0v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 01-.99-3.467l2.31-.66A2.25 2.25 0 009.5 15.553z"
                        />
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-4xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    My <span class="bg-linear-to-r from-amber-600 to-orange-500 bg-clip-text text-transparent">Repertoire</span>
                </h1>
                <template v-if="!loading">
                    <p class="text-sm sm:text-base font-medium text-stone-700 mt-1.5 sm:mt-2">
                        {{ pieces.length }} {{ pieces.length === 1 ? 'piece' : 'pieces' }} total
                    </p>

                    <!-- Tab switcher -->
                    <div
                        v-if="!reorderMode && !rotationIndexMode"
                        class="mt-5 sm:mt-6 flex bg-white/70 rounded-xl p-1 shadow-sm border border-stone-200/60 w-fit"
                    >
                        <button
                            class="px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold rounded-lg transition-all duration-200"
                            :class="activeTab === 'repertoire'
                                ? 'bg-linear-to-r from-amber-500 to-orange-500 text-white shadow-sm'
                                : 'text-stone-600 hover:text-stone-800 hover:bg-stone-100'"
                            @click="activeTab = 'repertoire'"
                        >
                            My Pieces
                            <span
                                class="ml-1.5 text-xs font-semibold px-1.5 py-0.5 rounded-md"
                                :class="activeTab === 'repertoire' ? 'bg-white/20' : 'bg-stone-200/60'"
                            >{{ repertoire.length }}</span>
                        </button>
                        <button
                            class="px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold rounded-lg transition-all duration-200"
                            :class="activeTab === 'wishlist'
                                ? 'bg-linear-to-r from-violet-500 to-purple-500 text-white shadow-sm'
                                : 'text-stone-600 hover:text-stone-800 hover:bg-stone-100'"
                            @click="activeTab = 'wishlist'"
                        >
                            Wishlist
                            <span
                                class="ml-1.5 text-xs font-semibold px-1.5 py-0.5 rounded-md"
                                :class="activeTab === 'wishlist' ? 'bg-white/20' : 'bg-stone-200/60'"
                            >{{ wishlist.length }}</span>
                        </button>
                    </div>
                </template>
            </header>

            <LoadingSpinner
                v-if="loading"
                message="Loading your repertoire..."
            />

            <template v-else>
                <template v-if="activeTab === 'repertoire' && !reorderMode && !rotationIndexMode">
                    <AddPieceForm
                        :open="showAddPiece"
                        modal-title="Add Piece"
                        submit-label="Add Piece"
                        @submit="addPiece"
                        @close="showAddPiece = false"
                    />

                    <!-- Filters & actions -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 mb-4 sm:mb-6">
                        <div class="flex gap-2.5 sm:gap-3 sm:flex-1">
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
                                    class="w-full pl-10 pr-4 py-2 sm:py-2.5 text-sm sm:text-md bg-white border border-stone-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-300/50 focus:border-amber-400 placeholder-stone-400 transition-shadow hover:shadow-md"
                                >
                            </div>
                            <button
                                class="sm:order-last px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-semibold text-white bg-linear-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg shadow-amber-300/30 hover:shadow-xl hover:shadow-amber-300/40 hover:scale-[1.03] hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap"
                                @click="showAddPiece = true"
                            >
                                + Add Piece
                            </button>
                        </div>
                        <div class="flex items-center gap-0.5">
                            <button
                                class="px-2.5 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold text-stone-700 hover:bg-stone-100 rounded-lg transition-colors duration-200 whitespace-nowrap"
                                @click="reorderMode = true"
                            >
                                Reorder
                            </button>
                            <span class="w-px h-4 bg-stone-200" />
                            <button
                                class="px-2.5 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold text-stone-700 hover:bg-stone-100 rounded-lg transition-colors duration-200 whitespace-nowrap"
                                @click="rotationIndexMode = true"
                            >
                                Up Next
                            </button>
                            <span class="w-px h-4 bg-stone-200" />
                            <select
                                v-model="statusFilter"
                                class="px-2.5 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold text-stone-700  bg-transparent hover:bg-stone-100 rounded-lg cursor-pointer focus:outline-none transition-colors duration-200 appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2210%22%20height%3D%2210%22%20viewBox%3D%220%200%2012%2012%22%3E%3Cpath%20fill%3D%22%23a8a29e%22%20d%3D%22M2%204l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-size-[10px] bg-position-[right_6px_center] bg-no-repeat pr-5"
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
                            <span class="w-px h-4 bg-stone-200" />
                            <button
                                class="p-1.5 sm:p-2 text-stone-700 hover:text-amber-500 hover:bg-stone-100 rounded-lg transition-colors duration-200"
                                title="What do statuses mean?"
                                @click="showStatusInfo = true"
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
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Piece list -->
                    <section class="card overflow-hidden hover:shadow-lg transition-shadow duration-300 border-amber-300">
                        <div
                            v-if="filteredPieces.length === 0"
                            class="px-6 py-16 text-center"
                        >
                            <span class="text-4xl text-amber-500 block mb-3">&#9835;</span>
                            <p class="text-amber-600 font-serif italic">
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
                                    class="text-xs sm:text-sm font-semibold px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full border-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-amber-300/50 transition-all duration-200 appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%228%22%20height%3D%228%22%20viewBox%3D%220%200%2012%2012%22%3E%3Cpath%20fill%3D%22currentColor%22%20d%3D%22M2%204l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-size-[8px] bg-position-[right_8px_center] bg-no-repeat pr-5"
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

                                <!-- Chevron hint -->
                                <svg
                                    class="w-4 h-4 text-stone-300 opacity-0 group-hover:opacity-100 transition-opacity duration-200 shrink-0 hidden sm:block"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>

                                <!-- Delete button -->
                                <button
                                    class="text-red-400 opacity-0 group-hover:opacity-100 transition-all duration-200 shrink-0"
                                    title="Remove piece"
                                    @click.stop="deletePiece(piece)"
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
                    v-if="activeTab === 'repertoire' && reorderMode"
                    :pieces="activePieces"
                    @save="saveReorder"
                    @cancel="reorderMode = false"
                />

                <!-- Rotation index mode -->
                <RotationIndex
                    v-if="activeTab === 'repertoire' && rotationIndexMode"
                    :pieces="activePieces"
                    :rotation-index="rotationIndex"
                    @save="saveRotationIndex"
                    @cancel="rotationIndexMode = false"
                />

                <!-- Want to Learn section -->
                <template v-if="activeTab === 'wishlist'">
                    <div class="flex gap-2.5 sm:gap-3 mb-4 sm:mb-6">
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
                                class="w-full pl-10 pr-4 py-2 sm:py-2.5 text-sm sm:text-md bg-white border border-stone-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-300/50 focus:border-violet-400 placeholder-stone-400 transition-shadow hover:shadow-md"
                            >
                        </div>
                        <button
                            class="px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-semibold text-white bg-linear-to-r from-violet-500 to-purple-500 rounded-xl shadow-lg shadow-violet-300/30 hover:shadow-xl hover:shadow-violet-300/40 hover:scale-[1.03] hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap"
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

                    <section class="card overflow-hidden hover:shadow-lg transition-shadow duration-300 border-violet-300">
                        <div
                            v-if="filteredWishlist.length === 0"
                            class="px-6 py-16 text-center"
                        >
                            <span class="text-4xl text-violet-500 block mb-3">&#9834;</span>
                            <p class="text-violet-600 font-serif italic">
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
                                    class="text-xs sm:text-sm font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-lg text-violet-700 bg-violet-50 border border-violet-200/60 hover:bg-violet-100 hover:border-violet-300 transition-all duration-200 whitespace-nowrap"
                                    title="Move to repertoire"
                                    @click.stop="startLearning(item)"
                                >
                                    Start Learning
                                </button>

                                <!-- Chevron hint -->
                                <svg
                                    class="w-4 h-4 text-stone-300 opacity-0 group-hover:opacity-100 transition-opacity duration-200 shrink-0 hidden sm:block"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>

                                <!-- Delete button -->
                                <button
                                    class="text-red-400 opacity-0 group-hover:opacity-100 transition-all duration-200 shrink-0"
                                    title="Remove from wishlist"
                                    @click.stop="deleteWishlistPiece(item)"
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
import RotationIndex from './RotationIndex.vue'
import StatusInfoModal from './StatusInfoModal.vue'


const pieces = ref([])
const loading = ref(true)

onMounted(async () => {
    try {
        const [piecesRes, userRes] = await Promise.all([
            api.get('/api/pieces'),
            api.get('/api/user'),
        ])
        pieces.value = piecesRes.data
        rotationIndex.value = userRes.data.rotation_index ?? 0
    } catch (e) {
        console.error('Failed to load data:', e)
    } finally {
        loading.value = false
    }
})

const activeTab = ref('repertoire')
const search = ref('')
const statusFilter = ref('')
const showAddPiece = ref(false)
const showAddWish = ref(false)
const selectedPiece = ref(null)
const selectedWishPiece = ref(null)
const reorderMode = ref(false)
const rotationIndexMode = ref(false)
const rotationIndex = ref(0)
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
        .filter(p => p.status !== 'Shelved')
        .sort((a, b) => {
            if (a.sort_order != null && b.sort_order != null) return a.sort_order - b.sort_order
            if (a.sort_order != null) return -1
            if (b.sort_order != null) return 1
            return 0
        })
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
        Learning: 'text-blue-700 bg-blue-50',
        Polishing: 'text-amber-700 bg-amber-50',
        Mastered: 'text-emerald-700 bg-emerald-50',
        Relearning: 'text-violet-700 bg-violet-50',
        Shelved: 'text-stone-600 bg-stone-100',
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

async function saveRotationIndex(newIndex) {
    try {
        await api.put('/api/user', { rotation_index: newIndex })
        rotationIndex.value = newIndex
        rotationIndexMode.value = false
    } catch (e) {
        console.error('Failed to save rotation index:', e)
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
        piece.sheet_music = data.sheet_music
    }
    selectedPiece.value = null
}

async function updateStatus(piece, newStatus) {
    const oldStatus = piece.status
    const oldSortOrder = piece.sort_order
    piece.status = newStatus

    try {
        const response = await api.put(`/api/pieces/${piece.id}`, { status: newStatus })
        piece.sort_order = response.data.sort_order ?? null
    } catch (e) {
        piece.status = oldStatus
        piece.sort_order = oldSortOrder
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
        item.sheet_music = data.sheet_music
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
