<template>
    <div class="landing-page">
        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-linear-to-b from-amber-50 via-white to-white">
            <!-- Decorative piano keys along the top -->
            <div class="absolute top-0 left-0 right-0 flex justify-center opacity-[0.07] pointer-events-none">
                <div
                    v-for="i in 28"
                    :key="'key-'+i"
                    class="flex-shrink-0"
                >
                    <div
                        v-if="isBlackKey(i)"
                        class="w-4 h-20 bg-stone-900 -mx-2 relative z-10 rounded-b"
                    />
                    <div
                        v-else
                        class="w-8 h-32 bg-stone-400 border-r border-stone-300"
                    />
                </div>
            </div>

            <!-- Floating musical symbols -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <span
                    v-for="(note, i) in heroNotes"
                    :key="'hero-note-'+i"
                    class="absolute text-amber-400/20 font-serif select-none"
                    :style="note.style"
                    :class="note.animClass"
                >{{ note.symbol }}</span>
            </div>

            <div class="relative max-w-6xl mx-auto px-6 pt-32 pb-24 text-center">
                <!-- Logo -->
                <div class="flex items-center justify-center gap-3 mb-8">
                    <div class="w-14 h-14 rounded-xl bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center shadow-lg shadow-amber-200/50">
                        <span class="text-white text-3xl">&#9835;</span>
                    </div>
                </div>

                <h1 class="font-serif text-5xl sm:text-6xl md:text-7xl font-bold text-stone-800 mb-6 leading-tight">
                    Master your
                    <span class="text-transparent bg-clip-text bg-linear-to-r from-amber-500 to-orange-500">piano repertoire</span>
                </h1>

                <p class="text-xl text-stone-700 max-w-2xl mx-auto mb-10 leading-relaxed">
                    The smart practice companion that helps you organize your repertoire,
                    build consistent habits, and watch your progress grow — one piece at a time.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                    <button
                        class="group px-8 py-4 bg-linear-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-2xl shadow-lg shadow-amber-300/40 hover:shadow-xl hover:shadow-amber-300/50 hover:-translate-y-0.5 transition-all duration-200 text-lg"
                        @click="$emit('get-started')"
                    >
                        Start Practicing Free
                        <span class="inline-block ml-2 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </button>
                    <button
                        class="px-8 py-4 bg-white border-2 border-stone-200 text-stone-600 font-semibold rounded-2xl hover:border-amber-300 hover:text-amber-700 transition-all duration-200 text-lg"
                        @click="scrollToFeatures"
                    >
                        See How It Works
                    </button>
                </div>

                <!-- Hero visual: stylized dashboard preview -->
                <div class="relative max-w-3xl mx-auto">
                    <div class="bg-white rounded-3xl shadow-2xl shadow-stone-200/60 border border-stone-100 p-6 sm:p-8">
                        <!-- Mini dashboard mockup -->
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-3 h-3 rounded-full bg-rose-400" />
                            <div class="w-3 h-3 rounded-full bg-amber-400" />
                            <div class="w-3 h-3 rounded-full bg-emerald-400" />
                            <div class="flex-1 h-6 bg-stone-100 rounded-full max-w-xs" />
                        </div>
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="bg-linear-to-br from-amber-50 to-orange-50 rounded-2xl p-4 text-center border border-amber-100">
                                <div class="text-2xl font-bold text-amber-600">
                                    12
                                </div>
                                <div class="text-xs text-stone-500 mt-1">
                                    Pieces
                                </div>
                            </div>
                            <div class="bg-linear-to-br from-orange-50 to-amber-50 rounded-2xl p-4 text-center border border-orange-100">
                                <div class="text-2xl font-bold text-orange-600 flex items-center justify-center gap-1">
                                    7 <span class="text-base">&#x1F525;</span>
                                </div>
                                <div class="text-xs text-stone-500 mt-1">
                                    Day Streak
                                </div>
                            </div>
                            <div class="bg-linear-to-br from-violet-50 to-purple-50 rounded-2xl p-4 text-center border border-violet-100">
                                <div class="text-2xl font-bold text-violet-600">
                                    4.2h
                                </div>
                                <div class="text-xs text-stone-500 mt-1">
                                    This Week
                                </div>
                            </div>
                        </div>
                        <!-- Mini practice list -->
                        <div class="space-y-3">
                            <div
                                v-for="(piece, i) in mockPieces"
                                :key="i"
                                class="flex items-center gap-3 p-3 rounded-xl"
                                :class="piece.done ? 'bg-emerald-50 border border-emerald-100' : 'bg-stone-50 border border-stone-100'"
                            >
                                <div
                                    class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold"
                                    :class="piece.done ? 'bg-emerald-500 text-white' : 'bg-stone-200 text-stone-400'"
                                >
                                    <span v-if="piece.done">&#10003;</span>
                                    <span v-else>{{ i + 1 }}</span>
                                </div>
                                <div class="flex-1 text-left">
                                    <div
                                        class="text-sm font-semibold"
                                        :class="piece.done ? 'text-emerald-700' : 'text-stone-700'"
                                    >
                                        {{ piece.name }}
                                    </div>
                                    <div
                                        class="text-xs"
                                        :class="piece.done ? 'text-emerald-500' : 'text-stone-400'"
                                    >
                                        {{ piece.composer }}
                                    </div>
                                </div>
                                <div
                                    class="text-xs px-2 py-1 rounded-full font-medium"
                                    :class="piece.done ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600'"
                                >
                                    {{ piece.done ? 'Done' : 'Up next' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Glow effect behind the card -->
                    <div class="absolute -inset-4 bg-linear-to-r from-amber-200/30 via-orange-200/20 to-violet-200/30 rounded-[2rem] -z-10 blur-2xl" />
                </div>
            </div>

            <!-- Wave divider -->
            <div class="absolute bottom-0 left-0 right-0">
                <svg
                    viewBox="0 0 1440 60"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-full"
                >
                    <path
                        d="M0 60V30C240 0 480 0 720 30C960 60 1200 60 1440 30V60H0Z"
                        fill="white"
                    />
                </svg>
            </div>
        </section>

        <!-- Features Intro -->
        <section
            ref="featuresSection"
            class="py-20 bg-white"
        >
            <div class="max-w-6xl mx-auto px-6 text-center">
                <p class="text-amber-600 font-semibold tracking-wide uppercase text-sm mb-3">
                    Why Piano Piece Manager?
                </p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-stone-800 mb-4">
                    Everything you need to improve
                </h2>
                <p class="text-stone-500 text-lg max-w-xl mx-auto">
                    Built by pianists, for pianists. We make it easy to stay on top of your pieces and see real progress.
                </p>
            </div>
        </section>

        <!-- Feature 1: Side-by-side (image left, text right) -->
        <section class="py-16 bg-white">
            <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
                <!-- Visual -->
                <div class="relative">
                    <div class="bg-linear-to-br from-amber-50 to-orange-50 rounded-3xl p-8 border border-amber-100">
                        <!-- Animated practice checklist visual -->
                        <div class="space-y-4">
                            <div class="text-md font-semibold text-amber-700 mb-2 flex items-center gap-2">
                                <span class="text-xl">&#9835;</span> Today's Practice
                            </div>
                            <div
                                v-for="(item, i) in featureList1"
                                :key="i"
                                class="flex items-center gap-3 bg-white rounded-xl p-3 shadow-sm border border-amber-50"
                            >
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center"
                                    :class="item.done ? 'bg-linear-to-br from-emerald-400 to-emerald-500 text-white' : 'bg-stone-100 text-stone-400'"
                                >
                                    <svg
                                        v-if="item.done"
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="3"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                    <span
                                        v-else
                                        class="text-xs font-bold"
                                    >{{ i + 1 }}</span>
                                </div>
                                <span
                                    class="text-sm font-medium"
                                    :class="item.done ? 'text-emerald-700 line-through' : 'text-stone-700'"
                                >
                                    {{ item.text }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative note -->
                    <div class="absolute -top-4 -right-4 w-12 h-12 bg-linear-to-br from-amber-400 to-orange-400 rounded-xl flex items-center justify-center text-white text-xl shadow-lg rotate-12">
                        &#119070;
                    </div>
                </div>
                <!-- Text -->
                <div>
                    <p class="text-amber-600 font-semibold text-lg mb-2 uppercase tracking-wide">
                        Smart Practice Schedule
                    </p>
                    <h3 class="font-serif text-3xl font-bold text-stone-800 mb-4">
                        Know exactly what to practice
                    </h3>
                    <p class="text-stone-700 text-lg leading-relaxed mb-6">
                        No more having to keep your repertoire in your head or with a pen and paper. We generate a daily practice
                        schedule tailored to your preferences, rotating pieces so nothing gets neglected.
                    </p>
                    <ul class="space-y-3">
                        <li
                            v-for="(point, i) in points1"
                            :key="i"
                            class="flex items-start gap-3"
                        >
                            <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg
                                    class="w-3.5 h-3.5 text-amber-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2.5"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                            <span class="text-stone-700">{{ point }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Feature 2: Side-by-side (text left, image right) -->
        <section class="py-16 bg-stone-50">
            <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
                <!-- Text -->
                <div class="order-2 md:order-1">
                    <p class="text-violet-600 font-semibold text-lg mb-2 uppercase tracking-wide">
                        Track Your Growth
                    </p>
                    <h3 class="font-serif text-3xl font-bold text-stone-800 mb-4">
                        Don't just repeat, improve over time
                    </h3>
                    <p class="text-stone-700 text-lg leading-relaxed mb-6">
                        We provide insights into your practice to help you recognize trends and 
                        other factors you can use to improve your performance over time. No more
                        mindless repetition, track and celebrate your progress!
                    </p>
                    <ul class="space-y-3">
                        <li
                            v-for="(point, i) in points2"
                            :key="i"
                            class="flex items-start gap-3"
                        >
                            <div class="w-6 h-6 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg
                                    class="w-3.5 h-3.5 text-violet-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2.5"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                            <span class="text-stone-700">{{ point }}</span>
                        </li>
                    </ul>
                </div>
                <!-- Visual -->
                <div class="order-1 md:order-2 relative">
                    <div class="bg-linear-to-br from-violet-50 to-purple-50 rounded-3xl p-8 border border-violet-100">
                        <!-- Streak heatmap mockup -->
                        <div class="text-sm font-semibold text-violet-700 mb-4 flex items-center gap-2">
                            <span class="text-lg">&#x1F4C8;</span> Your Progress
                        </div>
                        <div class="grid grid-cols-7 gap-1.5 mb-6">
                            <div
                                v-for="(val, i) in streakData"
                                :key="'streak-'+i"
                                class="aspect-square rounded-md transition-colors"
                                :class="streakColor(val)"
                            />
                        </div>
                        <!-- Mini stats -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white rounded-xl p-3 text-center border border-violet-50">
                                <div class="text-xl font-bold text-violet-600">
                                    85%
                                </div>
                                <div class="text-xs text-stone-400">
                                    Consistency
                                </div>
                            </div>
                            <div class="bg-white rounded-xl p-3 text-center border border-violet-50">
                                <div class="text-xl font-bold text-amber-600 flex items-center justify-center gap-1">
                                    14 &#x1F525;
                                </div>
                                <div class="text-xs text-stone-400">
                                    Best Streak
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative note -->
                    <div class="absolute -bottom-3 -left-3 w-10 h-10 bg-linear-to-br from-violet-400 to-purple-400 rounded-lg flex items-center justify-center text-white text-sm shadow-lg -rotate-12">
                        &#9833;
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature 3: Side-by-side (image left, text right) -->
        <section class="py-16 bg-white">
            <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
                <!-- Visual -->
                <div class="relative">
                    <div class="bg-linear-to-br from-emerald-50 to-teal-50 rounded-3xl p-8 border border-emerald-100">
                        <!-- Repertoire management mockup -->
                        <div class="text-sm font-semibold text-emerald-700 mb-4 flex items-center gap-2">
                            <span class="text-lg">&#127926;</span> My Repertoire
                        </div>
                        <div class="space-y-3">
                            <div
                                v-for="(piece, i) in repertoireDemo"
                                :key="i"
                                class="flex items-center gap-3 bg-white rounded-xl p-3 shadow-sm border border-emerald-50"
                            >
                                <div
                                    class="w-2 h-8 rounded-full"
                                    :class="piece.color"
                                />
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-stone-700">
                                        {{ piece.name }}
                                    </div>
                                    <div class="text-xs text-stone-400">
                                        {{ piece.composer }}
                                    </div>
                                </div>
                                <span
                                    class="text-xs px-2.5 py-1 rounded-full font-medium"
                                    :class="piece.badgeClass"
                                >
                                    {{ piece.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative -->
                    <div class="absolute -bottom-4 -right-4 w-11 h-11 bg-linear-to-br from-emerald-400 to-teal-400 rounded-xl flex items-center justify-center text-white text-lg shadow-lg rotate-6">
                        &#9834;
                    </div>
                </div>
                <!-- Text -->
                <div>
                    <p class="text-emerald-600 font-semibold text-lg mb-2 uppercase tracking-wide">
                        Repertoire Management
                    </p>
                    <h3 class="font-serif text-3xl font-bold text-stone-800 mb-4">
                        Your entire library, organized
                    </h3>
                    <p class="text-stone-700 text-lg leading-relaxed mb-6">
                        Keep track of the pieces you're learning, ones you've mastered, ones you've maybe
                        forgotten over time, and ones you want to learn. Filter by status, search instantly,
                        and never lose track of where you are.
                    </p>
                    <ul class="space-y-3">
                        <li
                            v-for="(point, i) in points3"
                            :key="i"
                            class="flex items-start gap-3"
                        >
                            <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg
                                    class="w-3.5 h-3.5 text-emerald-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2.5"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                            <span class="text-stone-700">{{ point }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Big Centered Section: Piano keyboard visual -->
        <section class="py-24 bg-linear-to-b from-stone-50 to-white relative overflow-hidden">
            <!-- Decorative staff lines -->
            <div class="absolute inset-0 flex flex-col justify-center pointer-events-none opacity-[0.04]">
                <div
                    v-for="i in 5"
                    :key="'staff-'+i"
                    class="h-px bg-stone-900 my-3 mx-12"
                />
            </div>

            <div class="relative max-w-4xl mx-auto px-6 text-center">
                <div class="mb-8">
                    <!-- Piano keyboard illustration -->
                    <div class="flex justify-center items-end gap-0 mx-auto w-fit">
                        <div
                            v-for="(key, i) in pianoKeysDisplay"
                            :key="'piano-'+i"
                            class="relative"
                        >
                            <div
                                v-if="key.type === 'white'"
                                class="w-8 sm:w-10 h-28 sm:h-36 border border-stone-200 rounded-b-lg transition-colors duration-300"
                                :class="key.highlight ? 'bg-linear-to-b from-amber-100 to-amber-200 border-amber-300' : 'bg-white'"
                            />
                            <div
                                v-else
                                class="absolute -top-0 w-5 sm:w-6 h-18 sm:h-24 bg-stone-800 rounded-b-md z-10 -ml-2.5 sm:-ml-3"
                                :class="key.highlight ? 'bg-linear-to-b from-amber-600 to-amber-800' : ''"
                            />
                        </div>
                    </div>
                </div>

                <h2 class="font-serif text-3xl sm:text-5xl font-bold text-stone-800 mb-6">
                    Practice smarter,<br>
                    <span class="text-transparent bg-clip-text bg-linear-to-r from-amber-500 via-orange-500 to-violet-500">
                        not just harder
                    </span>
                </h2>
                <p class="text-stone-700 text-lg max-w-2xl mx-auto leading-relaxed">
                    Every great pianist knows that quality practice matters more than quantity.
                    Building good habits, reflecting on progress, and consistent, focused work
                    leads to real imrpovement. Let Piano Piece Manager help you on that journey!
                </p>
            </div>
        </section>

        <!-- Stats / Social Proof Section -->
        <section class="py-20 bg-white">
            <div class="max-w-5xl mx-auto px-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div
                        v-for="(stat, i) in stats"
                        :key="i"
                        class="p-6"
                    >
                        <div
                            class="text-3xl sm:text-4xl font-bold mb-2"
                            :class="stat.color"
                        >
                            {{ stat.value }}
                        </div>
                        <div class="text-sm text-stone-500">
                            {{ stat.label }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial / Quote Section (centered) -->
        <section class="py-20 bg-linear-to-br from-amber-50 via-orange-50 to-amber-50 relative overflow-hidden">
            <!-- Decorative large quote marks -->
            <div class="absolute top-8 left-8 text-amber-200/40 text-[120px] font-serif leading-none pointer-events-none">
                &ldquo;
            </div>
            <div class="absolute bottom-8 right-8 text-amber-200/40 text-[120px] font-serif leading-none pointer-events-none">
                &rdquo;
            </div>

            <div class="relative max-w-3xl mx-auto px-6 text-center">
                <blockquote class="font-serif text-2xl sm:text-3xl text-stone-700 leading-relaxed mb-8 italic">
                    &ldquo;The beautiful thing about learning is that nobody can take it away from you.&rdquo;
                </blockquote>
                <div class="flex items-center justify-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-linear-to-br from-amber-400 to-orange-400 flex items-center justify-center text-white text-sm">
                        &#9835;
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-stone-700">
                            B.B. King
                        </div>
                        <div class="text-sm text-stone-500">
                            Musician
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section class="py-24 bg-white relative overflow-hidden">
            <!-- Subtle floating notes -->
            <div class="absolute inset-0 pointer-events-none">
                <span
                    v-for="(note, i) in ctaNotes"
                    :key="'cta-note-'+i"
                    class="absolute text-amber-300/15 font-serif select-none"
                    :style="note.style"
                >{{ note.symbol }}</span>
            </div>

            <div class="relative max-w-3xl mx-auto px-6 text-center">
                <div class="w-16 h-16 rounded-2xl bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center mx-auto mb-8 shadow-lg shadow-amber-200/50">
                    <span class="text-white text-3xl">&#9835;</span>
                </div>
                <h2 class="font-serif text-3xl sm:text-5xl font-bold text-stone-800 mb-6">
                    Ready to transform your practice?
                </h2>
                <p class="text-stone-500 text-lg mb-10 max-w-xl mx-auto">
                    Join and start building the practice routine you've always wanted.
                    It's free, it's simple, and your piano will thank you.
                </p>
                <button
                    class="group px-10 py-5 bg-linear-to-r from-amber-500 to-orange-500 text-white font-bold rounded-2xl shadow-lg shadow-amber-300/40 hover:shadow-xl hover:shadow-amber-300/50 hover:-translate-y-0.5 transition-all duration-200 text-xl"
                    @click="$emit('get-started')"
                >
                    Get Started &mdash; It's Free
                    <span class="inline-block ml-2 group-hover:translate-x-1 transition-transform">&rarr;</span>
                </button>
            </div>
        </section>

        <!-- Landing Footer -->
        <footer class="bg-stone-900 text-stone-400 py-12">
            <div class="max-w-6xl mx-auto px-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center">
                            <span class="text-white text-sm">&#9835;</span>
                        </div>
                        <span class="font-serif font-semibold text-stone-300">
                            Piano <span class="text-amber-400">Piece Manager</span>
                        </span>
                    </div>
                    <div class="text-sm">
                        &copy; {{ currentYear }} Piano Piece Manager. Made with &#9829; for pianists.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue'

defineEmits(['get-started'])

const currentYear = new Date().getFullYear()
const featuresSection = ref(null)

function scrollToFeatures() {
    featuresSection.value?.scrollIntoView({ behavior: 'smooth' })
}

// Hero floating notes data
const heroNotes = [
    { symbol: '♫', style: 'top: 15%; left: 8%; font-size: 2.5rem; transform: rotate(-15deg);', animClass: 'animate-float-slow' },
    { symbol: '♪', style: 'top: 25%; right: 12%; font-size: 2rem; transform: rotate(10deg);', animClass: 'animate-float-medium' },
    { symbol: '𝄞', style: 'top: 40%; left: 5%; font-size: 3rem; transform: rotate(5deg);', animClass: 'animate-float-medium' },
    { symbol: '♯', style: 'top: 60%; right: 8%; font-size: 1.8rem; transform: rotate(-8deg);', animClass: 'animate-float-slow' },
    { symbol: '♭', style: 'top: 70%; left: 15%; font-size: 2rem; transform: rotate(12deg);', animClass: 'animate-float-medium' },
    { symbol: '♪', style: 'top: 10%; left: 30%; font-size: 1.5rem; transform: rotate(20deg);', animClass: 'animate-float-slow' },
    { symbol: '♫', style: 'top: 50%; right: 18%; font-size: 1.8rem; transform: rotate(-5deg);', animClass: 'animate-float-medium' },
]

// CTA floating notes
const ctaNotes = [
    { symbol: '♫', style: 'top: 10%; left: 5%; font-size: 3rem; transform: rotate(-10deg);' },
    { symbol: '♪', style: 'top: 20%; right: 10%; font-size: 2.5rem; transform: rotate(15deg);' },
    { symbol: '𝄞', style: 'bottom: 15%; left: 10%; font-size: 4rem; transform: rotate(5deg);' },
    { symbol: '♯', style: 'bottom: 25%; right: 15%; font-size: 2rem; transform: rotate(-12deg);' },
]

// Mock data for hero dashboard
const mockPieces = [
    { name: 'Clair de Lune', composer: 'Debussy', done: true },
    { name: 'Nocturne Op. 9 No. 2', composer: 'Chopin', done: true },
    { name: 'Gymnopédie No. 1', composer: 'Satie', done: false },
]

// Feature 1 checklist items
const featureList1 = [
    { text: 'Clair de Lune — Debussy', done: true },
    { text: 'Prelude in C Major — Bach', done: true },
    { text: 'Moonlight Sonata Mvt. 1 — Beethoven', done: false },
    { text: 'Ballade No. 1 — Chopin', done: false },
]

// Feature section bullet points
const points1 = [
    'Auto-generated daily practice lists',
    'Rotate through your full repertoire on a set basis',
    'Check off pieces as you go',
]

const points2 = [
    'Daily streak tracking to build consistency',
    'Record notes as you practice',
    'Detailed summaries of your sessions and playing',
]

const points3 = [
    'Upload sheet music and links along with every piece',
    'Maintain a wishlist of what to learn next',
    'Search and filter your entire library instantly',
]

// Streak heatmap mock data (28 days)
const streakData = [
    3, 2, 3, 0, 2, 3, 1,
    2, 3, 3, 2, 0, 1, 3,
    3, 2, 0, 3, 3, 2, 3,
    1, 3, 3, 2, 3, 3, 2,
]

function streakColor(val) {
    if (val === 0) return 'bg-stone-100'
    if (val === 1) return 'bg-amber-200'
    if (val === 2) return 'bg-amber-400'
    return 'bg-amber-600'
}

// Repertoire demo items
const repertoireDemo = [
    { name: 'Clair de Lune', composer: 'Debussy', status: 'Learning', color: 'bg-blue-400', badgeClass: 'bg-blue-100 text-blue-600' },
    { name: 'Liebestraum No. 3', composer: 'Liszt', status: 'Polishing', color: 'bg-amber-400', badgeClass: 'bg-amber-100 text-amber-600' },
    { name: 'Prelude in C Major', composer: 'Bach', status: 'Learned', color: 'bg-emerald-400', badgeClass: 'bg-emerald-100 text-emerald-600' },
    { name: 'Fantaisie-Impromptu', composer: 'Chopin', status: 'Wishlist', color: 'bg-violet-400', badgeClass: 'bg-violet-100 text-violet-600' },
]

// Piano keys display helper
function isBlackKey(i) {
    const mod = i % 7
    return mod === 2 || mod === 4 || mod === 0 // Simplified pattern
}

// Piano keys for centered section
const pianoKeysDisplay = (() => {
    const keys = []
    // Two octaves of piano keys with some highlighted
    const pattern = ['white', 'black', 'white', 'black', 'white', 'white', 'black', 'white', 'black', 'white', 'black', 'white']
    const highlights = [0, 4, 7, 12, 16, 19] // C, E, G pattern (chord)
    for (let i = 0; i < 24; i++) {
        keys.push({
            type: pattern[i % 12],
            highlight: highlights.includes(i)
        })
    }
    return keys
})()

// Stats data
const stats = [
    { value: '100%', label: 'Free to use', color: 'text-amber-600' },
    { value: '∞', label: 'Pieces to track', color: 'text-violet-600' },
    { value: '5min', label: 'To get started', color: 'text-emerald-600' },
    { value: '♫', label: 'Pure musical joy', color: 'text-orange-500' },
]
</script>

<style scoped>
@keyframes float-slow {
    0%, 100% { transform: translateY(0) rotate(var(--rotation, 0deg)); }
    50% { transform: translateY(-15px) rotate(var(--rotation, 0deg)); }
}

@keyframes float-medium {
    0%, 100% { transform: translateY(0) rotate(var(--rotation, 0deg)); }
    50% { transform: translateY(-10px) rotate(var(--rotation, 0deg)); }
}

.animate-float-slow {
    animation: float-slow 6s ease-in-out infinite;
}

.animate-float-medium {
    animation: float-medium 4s ease-in-out infinite;
}
</style>
