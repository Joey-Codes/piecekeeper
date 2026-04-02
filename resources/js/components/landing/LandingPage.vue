<template>
    <div class="landing-page">
        <!-- Header -->
        <header class="absolute top-0 left-0 right-0 z-20 px-6 py-5">
            <div class="max-w-6xl mx-auto flex items-center">
                <div
                    class="flex items-center gap-2.5 cursor-pointer"
                    @click="router.push({ name: 'signup' })"
                >
                    <div class="w-9 h-9 rounded-lg bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center shadow-sm">
                        <span class="text-white text-lg">&#9835;</span>
                    </div>
                    <span class="font-serif font-semibold text-lg text-stone-800">
                        Piece<span class="text-amber-500">Keeper</span>
                    </span>
                </div>
                <router-link
                    :to="{ name: 'login' }"
                    class="ml-auto flex items-center gap-1.5 sm:gap-2 text-sm sm:text-base font-semibold text-stone-800 hover:text-amber-600 transition-colors"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 sm:w-5 sm:h-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle
                            cx="12"
                            cy="8"
                            r="4"
                        />
                        <path d="M20 21a8 8 0 0 0-16 0" />
                    </svg>
                    LOGIN
                </router-link>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative overflow-hidden">
            <!-- Decorative piano keys along the top -->
            <div class="absolute top-0 left-0 right-0 flex justify-center opacity-[0.07] pointer-events-none">
                <div
                    v-for="i in 29"
                    :key="'key-'+i"
                    class="shrink-0"
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
                <div class="flex items-center justify-center gap-3 mb-2 sm:mb-8">
                    <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center shadow-lg shadow-amber-200/50">
                        <span class="text-white text-2xl sm:text-3xl">&#9835;</span>
                    </div>
                </div>

                <h1 class="font-serif text-4xl sm:text-6xl md:text-7xl font-bold text-stone-800 mb-6 leading-tight">
                    Master your
                    <span class="text-transparent bg-clip-text bg-linear-to-r from-amber-500 to-orange-500">piano repertoire</span>
                </h1>

                <p class="text-lg sm:text-2xl text-stone-700 max-w-4xl mx-auto mb-10 leading-relaxed">
                    Piecekeeper is a smart practice companion that helps you organize your repertoire,
                    build consistent habits, and watch your progress grow - one piece at a time.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                    <button
                        class="group px-6 py-3 sm:px-8 sm:py-4 bg-linear-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-2xl shadow-lg shadow-amber-300/40 hover:shadow-xl hover:shadow-amber-300/50 hover:-translate-y-0.5 transition-all duration-200 text-base sm:text-lg"
                        @click="router.push({ name: 'signup' })"
                    >
                        Start Practicing Free
                        <span class="inline-block ml-2 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </button>
                </div>

                <!-- Hero visual: stylized dashboard preview -->
                <div class="relative max-w-3xl mx-auto">
                    <div class="bg-white rounded-3xl shadow-2xl shadow-stone-200/60 border border-amber-300 p-6 sm:p-8">
                        <!-- Mini dashboard mockup -->
                        <div class="flex items-center gap-2 mb-6">
                            <span class="text-lg">&#128197;</span>
                            <h3 class="text-sm font-serif font-bold uppercase tracking-wide text-stone-700">
                                Today's Practice
                            </h3>
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
                    <div class="absolute -inset-4 bg-linear-to-r from-amber-200/30 via-orange-200/20 to-violet-200/30 rounded-4xl -z-10 blur-2xl" />
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
            class="py-20 bg-linear-to-br from-amber-500 to-orange-500 relative overflow-hidden"
        >
            <div class="absolute inset-0 pointer-events-none">
                <span
                    v-for="(note, i) in featureIntroNotes"
                    :key="'fi-'+i"
                    class="absolute text-white/15 font-serif select-none"
                    :style="note.style"
                    :class="note.animClass"
                >{{ note.symbol }}</span>
            </div>
            <div class="relative max-w-6xl mx-auto px-6 text-center">
                <h2 class="font-serif text-3xl sm:text-5xl font-bold text-white mb-4">
                    Your practice, perfected
                </h2>
                <p class="text-white text-lg sm:text-2xl max-w-xl mx-auto">
                    See how PieceKeeper keeps you on track.
                </p>
            </div>
        </section>

        <!-- Feature 1: Side-by-side (image left, text right) -->
        <section class="py-24 md:py-40 bg-linear-to-br from-amber-50/40 via-orange-50/20 to-rose-50/30">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-8 sm:gap-16 items-center">
                <!-- Visual -->
                <div class="relative">
                    <div class="bg-linear-to-br from-amber-100 to-orange-100 rounded-3xl p-5 sm:p-8 md:p-10 border-2 border-amber-300">
                        <!-- Animated practice checklist visual -->
                        <div class="space-y-3 sm:space-y-4 md:space-y-5">
                            <div class="text-sm sm:text-base font-semibold text-amber-700 mb-2 flex items-center gap-2">
                                <span class="text-lg sm:text-xl">&#9835;</span> Today's Practice
                            </div>
                            <div
                                v-for="(item, i) in featureList1"
                                :key="i"
                                class="flex items-center gap-2 sm:gap-3 bg-white rounded-xl p-2.5 sm:p-3 md:p-4 shadow-sm border border-amber-100"
                            >
                                <div
                                    class="w-6 h-6 sm:w-8 sm:h-8 rounded-full flex items-center justify-center"
                                    :class="item.done ? 'bg-linear-to-br from-emerald-400 to-emerald-500 text-white' : 'bg-stone-100 text-stone-400'"
                                >
                                    <svg
                                        v-if="item.done"
                                        class="w-3 h-3 sm:w-4 sm:h-4"
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
                                        class="text-[0.65rem] sm:text-xs font-bold"
                                    >{{ i + 1 }}</span>
                                </div>
                                <span
                                    class="text-xs sm:text-sm font-medium"
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
                    <p class="text-amber-600 font-semibold text-md sm:text-xl mb-2 uppercase tracking-wide">
                        Smart Practice Schedule
                    </p>
                    <h3 class="font-serif text-2xl sm:text-4xl font-bold text-stone-800 mb-4">
                        Know exactly what to practice
                    </h3>
                    <p class="text-stone-800 text-lg sm:text-2xl leading-relaxed mb-6">
                        No more manual upkeep. A daily schedule tailored to you, rotating 
                        pieces so nothing slips through the cracks.
                    </p>
                    <ul class="space-y-3">
                        <li
                            v-for="(point, i) in points1"
                            :key="i"
                            class="flex items-start gap-3"
                        >
                            <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center shrink-0 mt-0.5">
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
                            <span class="sm:text-lg text-amber-700">{{ point }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Feature 2: Side-by-side (text left, image right) -->
        <section class="py-24 md:py-40 bg-linear-to-br from-violet-50/40 via-purple-50/20 to-pink-50/30">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-8 sm:gap-16 items-center">
                <!-- Text -->
                <div class="order-2 md:order-1">
                    <p class="text-violet-600 font-semibold text-md sm:text-xl mb-2 uppercase tracking-wide">
                        Track Your Growth
                    </p>
                    <h3 class="font-serif text-2xl sm:text-4xl font-bold text-stone-800 mb-4">
                        Don't just repeat, improve over time
                    </h3>
                    <p class="text-stone-800 text-lg sm:text-2xl leading-relaxed mb-6">
                        Track trends in your practice and celebrate real progress, not just repetition.
                    </p>
                    <ul class="space-y-3">
                        <li
                            v-for="(point, i) in points2"
                            :key="i"
                            class="flex items-start gap-3"
                        >
                            <div class="w-6 h-6 rounded-full bg-violet-100 flex items-center justify-center shrink-0 mt-0.5">
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
                            <span class="sm:text-lg text-violet-700">{{ point }}</span>
                        </li>
                    </ul>
                </div>
                <!-- Visual -->
                <div class="order-1 md:order-2 relative">
                    <div class="bg-linear-to-br from-violet-100 to-purple-100 rounded-3xl p-5 sm:p-8 border-2 border-violet-300">
                        <!-- Mini calendar mockup -->
                        <div class="text-xs sm:text-sm font-semibold text-violet-700 mb-3 sm:mb-4 flex items-center gap-2">
                            <span class="text-base sm:text-lg">&#x1F4C8;</span> March 2026
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center mb-1">
                            <span
                                v-for="d in ['S','M','T','W','T','F','S']"
                                :key="d"
                                class="text-[0.6rem] font-bold text-violet-400 uppercase tracking-wider py-1"
                            >{{ d }}</span>
                        </div>
                        <div class="grid grid-cols-7 gap-1 mb-4 sm:mb-6">
                            <div
                                v-for="(val, i) in calendarPreviewDays"
                                :key="'cal-'+i"
                                class="aspect-square rounded-lg border flex flex-col items-start p-1 relative"
                                :class="val.practiced ? 'border-amber-300 bg-linear-to-br from-amber-100 to-orange-100' : val.day ? 'border-stone-200 bg-white' : 'border-transparent'"
                            >
                                <span
                                    v-if="val.day"
                                    class="text-[0.65rem] font-bold"
                                    :class="val.today ? 'text-white bg-amber-500 w-4 h-4 rounded-full flex items-center justify-center' : 'text-stone-400'"
                                >{{ val.day }}</span>
                                <span
                                    v-if="val.practiced"
                                    class="w-5 h-5 rounded-full bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white self-center mx-auto mt-auto mb-1"
                                    style="font-size: 0.65rem;"
                                >&#10003;</span>
                            </div>
                        </div>
                        <!-- Mini stats -->
                        <div class="grid grid-cols-2 gap-2 sm:gap-3">
                            <div class="bg-white rounded-xl p-2.5 sm:p-3 text-center border border-violet-100">
                                <div class="text-lg sm:text-xl font-bold text-violet-600">
                                    85%
                                </div>
                                <div class="text-[0.65rem] sm:text-xs text-stone-400">
                                    Consistency
                                </div>
                            </div>
                            <div class="bg-white rounded-xl p-2.5 sm:p-3 text-center border border-violet-100">
                                <div class="text-lg sm:text-xl font-bold text-amber-600 flex items-center justify-center gap-1">
                                    14 &#x1F525;
                                </div>
                                <div class="text-[0.65rem] sm:text-xs text-stone-400">
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
        <section class="py-24 md:py-40 bg-linear-to-br from-emerald-50/40 via-teal-50/20 to-cyan-50/30">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-8 sm:gap-16 items-center">
                <!-- Visual -->
                <div class="relative">
                    <div class="bg-linear-to-br from-emerald-100 to-teal-100 rounded-3xl p-5 sm:p-8 md:p-10 border-2 border-emerald-300">
                        <!-- Repertoire management mockup -->
                        <div class="text-xs sm:text-sm font-semibold text-emerald-700 mb-3 sm:mb-4 flex items-center gap-2">
                            <span class="text-base sm:text-lg">&#127926;</span> My Repertoire
                        </div>
                        <div class="space-y-2 sm:space-y-3 md:space-y-4">
                            <div
                                v-for="(piece, i) in repertoireDemo"
                                :key="i"
                                class="flex items-center gap-2 sm:gap-3 bg-white rounded-xl p-2.5 sm:p-3 md:p-4 shadow-sm border border-emerald-100"
                            >
                                <div
                                    class="w-1.5 h-6 sm:w-2 sm:h-8 rounded-full"
                                    :class="piece.color"
                                />
                                <div class="flex-1">
                                    <div class="text-xs sm:text-sm font-semibold text-stone-700">
                                        {{ piece.name }}
                                    </div>
                                    <div class="text-[0.65rem] sm:text-xs text-stone-400">
                                        {{ piece.composer }}
                                    </div>
                                </div>
                                <span
                                    class="text-[0.65rem] sm:text-xs px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full font-medium"
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
                    <p class="text-emerald-600 font-semibold text-md sm:text-xl mb-2 uppercase tracking-wide">
                        Repertoire Management
                    </p>
                    <h3 class="font-serif text-2xl sm:text-4xl font-bold text-stone-800 mb-4">
                        Your entire library, organized
                    </h3>
                    <p class="text-stone-800 text-lg sm:text-2xl leading-relaxed mb-6">
                        Organize every piece by status - learning, mastered, forgotten, or on your wishlist. 
                        Search and filter your repertoire instantly.
                    </p>
                    <ul class="space-y-3">
                        <li
                            v-for="(point, i) in points3"
                            :key="i"
                            class="flex items-start gap-3"
                        >
                            <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg
                                    class="w-3.5 h-3.5 text-emerald-700"
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
                            <span class="sm:text-lg text-emerald-800">{{ point }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Big Centered Section: Piano keyboard visual -->
        <section class="py-24 md:py-40 bg-stone-900 relative overflow-hidden">
            <div class="relative max-w-4xl mx-auto px-6 text-center">
                <div class="mb-8">
                    <!-- Piano keyboard illustration -->
                    <div class="inline-flex justify-center items-start mx-auto max-w-full overflow-hidden">
                        <template
                            v-for="key in pianoKeysLayout"
                            :key="'piano-'+key.index"
                        >
                            <div
                                v-if="key.type === 'white'"
                                class="w-8 sm:w-10 h-28 sm:h-36 border rounded-t-lg transition-all duration-300 -ml-px"
                                :class="pianoHighlights.has(key.index) ? 'bg-amber-300 border-amber-500' : 'bg-stone-100 border-stone-600'"
                            />
                            <div
                                v-else
                                class="w-5 sm:w-6 h-18 sm:h-24 rounded-t-md z-10 -mx-2.5 sm:-mx-3 relative border transition-all duration-300"
                                :class="pianoHighlights.has(key.index) ? 'bg-amber-700 border-amber-700' : 'bg-stone-950 border-stone-700'"
                            />
                        </template>
                    </div>
                </div>

                <h2 class="font-serif text-3xl sm:text-5xl font-bold text-white mb-6">
                    Practice smarter,<br>
                    <span class="text-transparent bg-clip-text bg-linear-to-r from-amber-500 via-orange-500 to-violet-500">
                        not just harder
                    </span>
                </h2>
                <p class="text-stone-300 text-xl sm:text-2xl max-w-2xl mx-auto leading-relaxed">
                    Quality practice matters more than quantity.
                    PieceKeeper is designed to make every session count.
                </p>
            </div>
        </section>

        <!-- Stats / Social Proof Section -->
        <section class="py-28 md:py-40 bg-linear-to-br from-violet-100 via-orange-100 to-amber-100 relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <span
                    v-for="(note, i) in statsNotes"
                    :key="'sn-'+i"
                    class="absolute text-white/30 font-serif select-none scale-50 sm:scale-100"
                    :style="note.style"
                    :class="note.animClass"
                >{{ note.symbol }}</span>
            </div>
            <div class="relative max-w-7xl mx-auto px-6">
                <h2 class="font-serif text-2xl sm:text-4xl font-bold text-stone-800 text-center mb-12">
                    No catches, just practice
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-10 text-center">
                    <div
                        v-for="(stat, i) in stats"
                        :key="i"
                        class="py-4 px-2 sm:p-6 rounded-2xl bg-white/60 backdrop-blur-md"
                    >
                        <div
                            class="font-bold mb-2 h-10 sm:h-12 flex items-end justify-center"
                            :class="[stat.color, stat.sizeClass || 'text-2xl sm:text-4xl']"
                        >
                            {{ stat.value }}
                        </div>
                        <div class="text-md sm:text-xl font-semibold text-stone-800 sm:whitespace-nowrap">
                            {{ stat.label }}
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
                    Ready to transform your playing?
                </h2>
                <p class="text-stone-800 text-lg sm:text-xl mb-10 max-w-xl mx-auto">
                    Join and start building the practice routine you've always wanted.
                    It's free, it's simple, and your piano will thank you.
                </p>
                <button
                    class="group px-6 py-3 sm:px-10 sm:py-5 bg-linear-to-r from-amber-500 to-orange-500 text-white font-bold rounded-2xl shadow-lg shadow-amber-300/40 hover:shadow-xl hover:shadow-amber-300/50 hover:-translate-y-0.5 transition-all duration-200 text-base sm:text-lg"
                    @click="goToSignup"
                >
                    Get Started,  It's Free!
                    <span class="inline-block ml-2 group-hover:translate-x-1 transition-transform">&rarr;</span>
                </button>
            </div>
        </section>

        <AppFooter />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import AppFooter from '../AppFooter.vue'

const router = useRouter()

const featuresSection = ref(null)

function goToSignup() {
    window.scrollTo(0, 0)
    router.push({ name: 'signup' })
}

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

// Stats section floating notes
// Features intro floating notes
const featureIntroNotes = [
    { symbol: '♪', style: 'top: 15%; left: 8%; font-size: 2.5rem; transform: rotate(-12deg);', animClass: 'animate-float-slow' },
    { symbol: '♫', style: 'top: 20%; right: 10%; font-size: 3rem; transform: rotate(10deg);', animClass: 'animate-float-medium' },
    { symbol: '𝄞', style: 'bottom: 15%; left: 15%; font-size: 3rem; transform: rotate(5deg);', animClass: 'animate-float-medium' },
    { symbol: '♭', style: 'bottom: 20%; right: 12%; font-size: 2rem; transform: rotate(-8deg);', animClass: 'animate-float-slow' },
]

const statsNotes = [
    { symbol: '♪', style: 'top: 5%; left: 2%; font-size: 3rem; transform: rotate(-10deg);', animClass: 'animate-float-slow' },
    { symbol: '♫', style: 'top: 5%; right: 2%; font-size: 3.5rem; transform: rotate(12deg);', animClass: 'animate-float-medium' },
    { symbol: '𝄞', style: 'bottom: 5%; left: 3%; font-size: 3.5rem; transform: rotate(5deg);', animClass: 'animate-float-medium' },
    { symbol: '♯', style: 'bottom: 5%; right: 2%; font-size: 2.5rem; transform: rotate(-8deg);', animClass: 'animate-float-slow' },
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
    'No pre-planning needed, just sit down and play',
    'Fully customizable rotation settings',
    'Check off pieces as you go',
]

const points2 = [
    'Comprehensive insights into your play',
    'Record notes as you practice',
    'See your progress over weeks and months',
]

const points3 = [
    'Attach sheet music and links to any piece',
    'Maintain a wishlist of what to learn next',
    'Search and filter your entire library instantly',
]

// Mini calendar preview data (March 2026 starts on Sunday)
const practicedPreviewDays = new Set([3, 5, 7, 8, 10, 12, 14, 15, 17])
const calendarPreviewDays = (() => {
    const days = []
    // March 2026 starts on Sunday (offset 0), show first 3 weeks
    for (let d = 1; d <= 21; d++) {
        days.push({ day: d, practiced: practicedPreviewDays.has(d), today: d === 17 })
    }
    return days
})()

// Repertoire demo items
const repertoireDemo = [
    { name: 'Clair de Lune', composer: 'Debussy', status: 'Learning', color: 'bg-blue-400', badgeClass: 'bg-blue-50 text-blue-700' },
    { name: 'Liebestraum No. 3', composer: 'Liszt', status: 'Polishing', color: 'bg-amber-400', badgeClass: 'bg-amber-50 text-amber-700' },
    { name: 'Prelude in C Major', composer: 'Bach', status: 'Mastered', color: 'bg-emerald-400', badgeClass: 'bg-emerald-50 text-emerald-700' },
    { name: 'Fantaisie-Impromptu', composer: 'Chopin', status: 'Relearning', color: 'bg-violet-400', badgeClass: 'bg-violet-50 text-violet-700' },
]

// Piano keys display helper
function isBlackKey(i) {
    const mod = (i - 1) % 12
    return mod === 1 || mod === 3 || mod === 6 || mod === 8 || mod === 10
}

// Piano keys for centered section — animated highlights
const keyPattern = ['white', 'black', 'white', 'black', 'white', 'white', 'black', 'white', 'black', 'white', 'black', 'white']

// Sequence simulating a piece — mix of single notes, intervals, and chords with varied timing
// Each entry: [highlightedKeys, durationMs]
const furEliseSequence = [
    // Für Elise - main theme
    [[16],          300],  // E5
    [[15],          300],  // D#5
    [[16],          300],  // E5
    [[15],          300],  // D#5
    [[16],          300],  // E5
    [[11],          300],  // B4
    [[14],          300],  // D5
    [[12],          300],  // C5
    [[9],           600],  // A4
    [[],            150],
    [[0],           300],  // C4
    [[4],           300],  // E4
    [[9],           300],  // A4
    [[11],          600],  // B4
    [[],            150],
    [[3],           300],  // D#4 (Eb)
    [[7],           300],  // G4
    [[11],          300],  // B4
    [[12],          600],  // C5
    [[],            150],
    [[0],           300],  // C4
    [[16],          300],  // E5
    [[15],          300],  // D#5
    [[16],          300],  // E5
    [[15],          300],  // D#5
    [[16],          300],  // E5
    [[11],          300],  // B4
    [[14],          300],  // D5
    [[12],          300],  // C5
    [[9],           600],  // A4
    [[],            150],
    [[0],           300],  // C4
    [[4],           300],  // E4
    [[9],           300],  // A4
    [[11],          300],  // B4
    [[],            150],
    [[4],           300],  // E4
    [[12],          300],  // C5
    [[11],          300],  // B4
    [[9],           900],  // A4 — resolve, held
    [[],           3000],  // rest before loop
]

const newYorkSequence = [
    // New York, New York (1.5x tempo)
    // Phrase A x2
    [[14, 18], 450],  // D F#
    [[14, 18], 450],  // D F#
    [[13, 18], 450],  // C# F#
    [[14, 18], 225],  // D F#  — quick
    [[11, 18], 675],  // B F#  — held
    [[],       225],
    [[14, 18], 450],
    [[14, 18], 450],
    [[13, 18], 450],
    [[14, 18], 225],
    [[11, 18], 675],
    [[],       225],
    // Phrase B x2
    [[14, 19], 450],  // D G
    [[14, 19], 450],  // D G
    [[13, 19], 450],  // C# G
    [[14, 19], 225],  // D G  — quick
    [[11, 19], 675],  // B G  — held
    [[],       225],
    [[14, 19], 450],
    [[14, 19], 450],
    [[13, 19], 450],
    [[14, 19], 225],
    [[11, 19], 675],
    [[],      3000],  // rest before loop
]

// Randomly pick a tune on each page load
const highlightSequence = Math.random() < 0.5 ? furEliseSequence : newYorkSequence

const pianoHighlights = ref(new Set(highlightSequence[0][0]))

const pianoKeysLayout = (() => {
    const keys = []
    for (let i = 0; i < 24; i++) {
        keys.push({ type: keyPattern[i % 12], index: i })
    }
    return keys
})()

let pianoTimeout = null
let seqIndex = 0

function playNext() {
    seqIndex = (seqIndex + 1) % highlightSequence.length
    const [highlights, duration] = highlightSequence[seqIndex]
    const newSet = new Set(highlights)

    // If any keys are repeated from the previous step, briefly lift them so
    // the user sees the key "re-struck" rather than just held
    const repeated = highlights.filter(k => pianoHighlights.value.has(k))
    if (repeated.length > 0) {
        const gap = 120
        // Show only the non-repeated keys during the brief gap
        pianoHighlights.value = new Set(highlights.filter(k => !pianoHighlights.value.has(k)))
        pianoTimeout = setTimeout(() => {
            pianoHighlights.value = newSet
            pianoTimeout = setTimeout(playNext, Math.max(duration - gap, 30))
        }, gap)
    } else {
        pianoHighlights.value = newSet
        pianoTimeout = setTimeout(playNext, duration)
    }
}

onMounted(() => {
    const [, duration] = highlightSequence[0]
    pianoTimeout = setTimeout(playNext, duration)
})

onUnmounted(() => {
    clearTimeout(pianoTimeout)
})

// Stats data
const stats = [
    { value: '100%', label: 'Free to use', color: 'text-amber-600' },
    { value: '∞', label: 'Pieces to track', color: 'text-violet-600', sizeClass: 'text-3xl sm:text-6xl leading-none' },
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
