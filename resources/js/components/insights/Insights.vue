<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50/80 via-white to-stone-50 relative overflow-hidden">
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
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"
                        />
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-4xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    Practice <span class="bg-linear-to-r from-amber-600 to-orange-500 bg-clip-text text-transparent">Insights</span>
                </h1>
                <p class="text-sm sm:text-base font-medium text-stone-700 mt-1.5 sm:mt-2">
                    A snapshot of your piano journey
                </p>
            </header>

            <!-- Loading state -->
            <LoadingSpinner
                v-if="loading"
                message="Loading insights..."
            />

            <template v-else>
                <!-- Stats grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                    <div
                        v-for="stat in stats"
                        :key="stat.label"
                        class="flex items-center gap-4 rounded-2xl border bg-white px-4 py-4 sm:px-5 sm:py-5 shadow-md transition-shadow duration-200 hover:shadow-lg"
                        :class="stat.borderClass"
                    >
                        <div
                            class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center shrink-0"
                            :class="stat.bgClass"
                        >
                            <div
                                class="text-white w-5 h-5 sm:w-6 sm:h-6"
                                v-html="stat.icon"
                            />
                        </div>
                        <div class="min-w-0">
                            <p
                                class="text-xs font-bold uppercase tracking-wider leading-none mb-1.5"
                                :class="stat.labelClass"
                            >
                                {{ stat.label }}
                            </p>
                            <p
                                class="font-bold text-stone-800 leading-tight truncate"
                                :class="typeof stat.value === 'string' ? 'text-base sm:text-lg' : 'text-xl sm:text-2xl'"
                            >
                                {{ stat.value }}<span
                                    v-if="stat.unit"
                                    class="text-xs sm:text-sm font-semibold text-stone-600 ml-0.5"
                                >{{ stat.unit }}</span>
                            </p>
                            <p class="text-xs sm:text-sm text-stone-600 font-medium leading-tight mt-0.5">
                                {{ stat.detail }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Monthly breakdown -->
                <header
                    id="calendar"
                    class="mt-20 mb-10 flex flex-col items-center"
                >
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-linear-to-br from-violet-400 to-purple-500 flex items-center justify-center shadow-md mb-3.5 sm:mb-4">
                        <svg
                            class="w-4.5 h-4.5 sm:w-6 sm:h-6 text-white"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                            />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-3xl font-serif font-bold uppercase tracking-wide text-stone-800">
                        This Month's<span class="bg-linear-to-r from-violet-500 to-purple-500 bg-clip-text text-transparent"> Progress</span>
                    </h2>
                    <p class="text-sm sm:text-base font-medium text-stone-700 mt-1.5 sm:mt-2">
                        Your daily activity at a glance. Click any session to view details.
                    </p>
                </header>

                <section class="rounded-xl border border-stone-200 bg-white overflow-hidden transition-shadow duration-200 hover:shadow-md">
                    <!-- Legend -->
                    <div class="px-4 sm:px-6 pt-4 sm:pt-5 flex items-center justify-end gap-3 sm:gap-4 text-[10px] sm:text-xs font-semibold text-stone-400">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-linear-to-br from-violet-400 to-purple-500" />
                            <span>Today</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-linear-to-br from-amber-400 to-orange-500" />
                            <span>Practiced</span>
                        </div>
                    </div>

                    <!-- Calendar body -->
                    <div class="px-2 sm:px-5 pb-3 sm:pb-5">
                        <Calendar
                            :attributes="calendarAttributes"
                            :max-date="new Date()"
                            class="custom-calendar"
                            expanded
                            borderless
                            @did-move="onCalendarMove"
                        >
                            <template #day-content="{ day }">
                                <div
                                    class="w-full h-full flex flex-col items-center py-1 sm:py-1.5 rounded-lg transition-colors duration-150"
                                    :class="[
                                        getDayBoxClass(day),
                                        practicedDates.has(day.id) ? 'cursor-pointer' : ''
                                    ]"
                                    @click="practicedDates.has(day.id) && openSessionDetail(day.id)"
                                >
                                    <span
                                        class="text-[10px] sm:text-xs font-bold leading-none w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center rounded-full"
                                        :class="isToday(day) ? 'bg-linear-to-br from-violet-400 to-purple-500 text-white shadow-sm' : 'text-stone-500'"
                                    >{{ day.day }}</span>
                                    <div class="flex-1 flex items-center justify-center mt-0.5">
                                        <span
                                            v-if="practicedDates.has(day.id)"
                                            class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-sm"
                                        >
                                            <svg
                                                class="w-2.5 h-2.5 sm:w-3 sm:h-3"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="3"
                                            ><path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M4.5 12.75l6 6 9-13.5"
                                            /></svg>
                                        </span>
                                    </div>
                                </div>
                            </template>
                        </Calendar>
                    </div>
                </section>
            </template>
        </div>

        <!-- Session Detail Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div
                    v-if="sessionModal.show"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    @mousedown.self="sessionModal.show = false"
                >
                    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" />

                    <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-5 sm:p-6">
                        <!-- Loading -->
                        <div
                            v-if="sessionModal.loading"
                            class="flex flex-col items-center py-8"
                        >
                            <div class="w-8 h-8 border-3 border-amber-400 border-t-transparent rounded-full animate-spin" />
                            <p class="text-sm text-stone-500 mt-3">
                                Loading session...
                            </p>
                        </div>

                        <template v-else-if="sessionModal.session">
                            <!-- Header -->
                            <div class="text-center mb-5">
                                <div class="mx-auto w-12 h-12 rounded-xl bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-sm mb-3">
                                    <svg
                                        class="w-6 h-6 text-white"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                                        />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-serif font-bold uppercase tracking-wide text-stone-800">
                                    {{ formatModalDate(sessionModal.session.date) }}
                                </h3>
                                <div class="text-2xl font-bold text-stone-800 mt-2">
                                    {{ formatDuration(sessionModal.session.duration_seconds) }}
                                </div>
                            </div>

                            <!-- Piece list -->
                            <div class="space-y-2 mb-5">
                                <div
                                    v-for="piece in sessionModal.session.pieces"
                                    :key="piece.id"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl"
                                    :class="piece.completed
                                        ? 'bg-emerald-50 border border-emerald-100'
                                        : 'bg-stone-50 border border-stone-200'"
                                >
                                    <div
                                        class="w-5 h-5 rounded-full flex items-center justify-center shrink-0"
                                        :class="piece.completed ? 'bg-emerald-400' : 'bg-stone-300'"
                                    >
                                        <svg
                                            v-if="piece.completed"
                                            class="w-3 h-3 text-white"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="3"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M5 13l4 4L19 7"
                                            />
                                        </svg>
                                        <svg
                                            v-else
                                            class="w-3 h-3 text-white"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="3"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M5 12h14"
                                            />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span
                                            class="text-sm font-semibold truncate block"
                                            :class="piece.completed ? 'text-emerald-700' : 'text-stone-400'"
                                        >
                                            {{ piece.title }}
                                        </span>
                                        <span
                                            v-if="piece.composer"
                                            class="text-xs text-stone-400 truncate block"
                                        >
                                            {{ piece.composer }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary -->
                            <p class="text-center text-sm text-stone-800 mb-4">
                                {{ sessionModal.session.pieces_completed }}/{{ sessionModal.session.pieces_assigned }} pieces were completed
                            </p>

                            <button
                                class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-linear-to-r from-amber-500 to-orange-500 rounded-xl hover:shadow-md hover:scale-[1.01] transition-all duration-150"
                                @click="sessionModal.show = false"
                            >
                                Close
                            </button>
                        </template>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import FloatingNotes from '../ui/FloatingNotes.vue'
import LoadingSpinner from '../ui/LoadingSpinner.vue'
import { Calendar } from 'v-calendar'
import 'v-calendar/style.css'
import api from '../../api'

const loading = ref(true)
const data = ref(null)
const calendarDates = ref([])

const now = new Date()

onMounted(async () => {
    const [statsRes, calendarRes] = await Promise.all([
        api.get('/api/insights'),
        api.get(`/api/insights/calendar?year=${now.getFullYear()}&month=${now.getMonth() + 1}`),
    ])
    data.value = statsRes
    calendarDates.value = calendarRes.dates
    loading.value = false
})

async function onCalendarMove(pages) {
    const page = pages[0]
    const res = await api.get(`/api/insights/calendar?year=${page.year}&month=${page.month}`)
    calendarDates.value = res.dates
}

function formatHours(seconds) {
    const hours = seconds / 3600
    return hours % 1 === 0 ? hours : parseFloat(hours.toFixed(1))
}

function formatMinutes(seconds) {
    return Math.round(seconds / 60)
}

const stats = computed(() => {
    if (!data.value) return []
    const d = data.value
    return [
        {
            label: 'Lifetime Practice',
            value: formatHours(d.lifetime_seconds),
            unit: 'hrs',
            detail: `${d.lifetime_sessions} sessions total`,
            icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            bgClass: 'bg-linear-to-br from-rose-400 to-pink-500',
            borderClass: 'border-rose-200',
            labelClass: 'text-rose-600',
        },
        {
            label: 'Avg. Practice Session',
            value: formatMinutes(d.avg_session_seconds),
            unit: 'min',
            detail: d.lifetime_sessions > 0 ? `Across ${d.lifetime_sessions} sessions` : 'No sessions yet',
            icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>',
            bgClass: 'bg-linear-to-br from-sky-400 to-blue-500',
            borderClass: 'border-sky-200',
            labelClass: 'text-sky-600',
        },
        {
            label: 'This Week',
            value: formatHours(d.week_seconds),
            unit: 'hrs',
            detail: `${d.week_sessions} sessions`,
            icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>',
            bgClass: 'bg-linear-to-br from-amber-400 to-orange-500',
            borderClass: 'border-amber-200',
            labelClass: 'text-amber-600',
        },
        {
            label: 'Total Pieces',
            value: d.total_pieces,
            unit: null,
            detail: `${d.active_pieces} active`,
            icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V4.125A2.25 2.25 0 0017.868 1.9l-6.75 1.929a2.25 2.25 0 00-1.618 2.163V15m0 0v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 01-.99-3.467l2.31-.66A2.25 2.25 0 009.5 15.553z" /></svg>',
            bgClass: 'bg-linear-to-br from-violet-400 to-purple-500',
            borderClass: 'border-violet-200',
            labelClass: 'text-violet-600',
        },
        {
            label: 'Most Practiced',
            value: d.most_practiced?.title ?? '—',
            unit: null,
            detail: d.most_practiced ? `${d.most_practiced.session_count} sessions` : 'No sessions yet',
            icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-4.5A3.375 3.375 0 0012.75 11h-.5A3.375 3.375 0 009 14.25v4.5m7.5 0h-6m1.5-12V2.25m3.75 2.25L13.5 2.25m-3 2.25L12.75 2.25" /></svg>',
            bgClass: 'bg-linear-to-br from-emerald-400 to-teal-500',
            borderClass: 'border-emerald-200',
            labelClass: 'text-emerald-600',
        },
        {
            label: 'Learning',
            value: d.learning_pieces,
            unit: null,
            detail: 'Currently learning',
            icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>',
            bgClass: 'bg-linear-to-br from-blue-400 to-indigo-500',
            borderClass: 'border-blue-200',
            labelClass: 'text-blue-600',
        },
        {
            label: 'Polishing',
            value: d.polishing_pieces,
            unit: null,
            detail: 'Refining & perfecting',
            icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" /></svg>',
            bgClass: 'bg-linear-to-br from-pink-400 to-rose-500',
            borderClass: 'border-pink-200',
            labelClass: 'text-pink-600',
        },
        {
            label: 'Relearning',
            value: d.relearning_pieces,
            unit: null,
            detail: 'Picking back up',
            icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" /></svg>',
            bgClass: 'bg-linear-to-br from-orange-400 to-red-500',
            borderClass: 'border-orange-200',
            labelClass: 'text-orange-600',
        },
        {
            label: 'Mastered',
            value: d.mastered_pieces,
            unit: null,
            detail: `${d.mastered_this_month} this month`,
            icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15v-3.75m0 0h10.5m-10.5 0L12 7.5l5.25 3.75" /></svg>',
            bgClass: 'bg-linear-to-br from-yellow-400 to-amber-500',
            borderClass: 'border-yellow-200',
            labelClass: 'text-yellow-600',
        },
    ]
})

const practicedDates = computed(() => new Set(calendarDates.value))

const calendarAttributes = ref([])

// Session detail modal
const sessionModal = ref({
    show: false,
    loading: false,
    session: null,
})

async function openSessionDetail(dateId) {
    sessionModal.value = { show: true, loading: true, session: null }
    try {
        const res = await api.get(`/api/insights/session?date=${dateId}`)
        sessionModal.value.session = res.session
    } catch {
        sessionModal.value.show = false
    } finally {
        sessionModal.value.loading = false
    }
}

function formatModalDate(dateStr) {
    return new Date(dateStr + 'T00:00:00').toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
    })
}

function formatDuration(seconds) {
    const hrs = Math.floor(seconds / 3600)
    const mins = Math.floor((seconds % 3600) / 60)
    const secs = seconds % 60
    const pad = (n) => String(n).padStart(2, '0')
    if (hrs > 0) return `${hrs}:${pad(mins)}:${pad(secs)}`
    return `${pad(mins)}:${pad(secs)}`
}

const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`

function isToday(day) {
    return day.id === todayStr
}

function getDayBoxClass(day) {
    if (isToday(day)) return 'bg-violet-50/60'
    if (practicedDates.value.has(day.id)) return 'bg-amber-100/70'
    return 'bg-stone-50/80 hover:bg-stone-100/80'
}
</script>

<style scoped>
:deep(.vc-container) {
    font-family: ui-sans-serif, system-ui, sans-serif;
}

:deep(.vc-header) {
    padding: 0.25rem 0 1.75rem;
}

:deep(.vc-title) {
    font-size: 1.125rem;
    font-weight: 700;
    color: rgb(41 37 36);
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

:deep(.vc-arrow) {
    color: rgb(168 162 158);
    border-radius: 0.375rem;
    width: 1.75rem;
    height: 1.75rem;
}

:deep(.vc-arrow:hover) {
    background-color: rgb(245 245 244);
    color: rgb(68 64 60);
}

:deep(.vc-weekday) {
    font-weight: 600;
    font-size: 0.65rem;
    letter-spacing: 0.08em;
    color: rgb(168 162 158);
    text-transform: uppercase;
    padding-bottom: 0.5rem;
}

:deep(.vc-day) {
    padding: 0.1rem;
    min-height: 3.25rem;
}

@media (min-width: 640px) {
    :deep(.vc-day) {
        padding: 0.15rem;
        min-height: 4.5rem;
    }
}

:deep(.vc-day-content) {
    width: 100%;
    height: 100%;
}

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}
.modal-enter-active .relative,
.modal-leave-active .relative {
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
.modal-enter-from .relative {
    transform: scale(0.95);
}
.modal-leave-to .relative {
    transform: scale(0.95);
}
</style>
